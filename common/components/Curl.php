<?php
namespace common\components;
use Campo\UserAgent;
use JonnyW\PhantomJs\Client;

/**
 * Created by PhpStorm.
 */
class Curl extends \CApplicationComponent
{
    private $proxy_list = [];
    private $use_proxy = false;
    private $proxy = null;
    private $cookieFile = null;

    public $pathToPhantomJS = null;
    public $pathToPhantomLoader = null;
    public $pathToConfig = null;


    /** @var \JonnyW\PhantomJs\Client _client */
    private $_client = null;

    public function init()
    {
        parent::init();
    }

    public function run($useProxy = false)
    {
        $this->_client = Client::getInstance();;

        if($useProxy !== false) {
            if($useProxy === true) {
                $this->populateProxyList();
                if(!empty($this->proxy_list))
                    $this->proxy = $this->proxy_list[array_rand($this->proxy_list)];
            } else
                $this->proxy = $useProxy;

            if($this->proxy) {
                $this->cookieFile = sprintf('%s/cookie/%s.txt', ROOT_DIR, $this->proxy);
                $this->_client->getEngine()->addOption(sprintf('--proxy=%s', $this->proxy));
                $this->_client->getEngine()->addOption(sprintf('--proxy-type=http', $this->proxy));
            }
        }

        if($this->cookieFile === null)
            $this->cookieFile = sprintf('%s/cookie/cookie.txt', ROOT_DIR);
        $this->_client->getEngine()->addOption(sprintf('--cookies-file=%s', $this->cookieFile));

        if($this->pathToConfig !== null)
            $this->_client->getEngine()->addOption(sprintf('--config=%s', $this->pathToConfig));

        //$this->_client->getEngine()->addOption('--disk-cache=true');
        //$this->_client->setBinDir(ROOT_DIR.'/bin');
        //var_dump($this->_client->getEngine()->getCommand());die;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setUseProxy($value = true)
    {
        $this->use_proxy = $value;
        if($value === true)
            $this->populateProxyList();

        return $this;
    }

    private function populateProxyList()
    {
        if(!empty($this->proxy_list))
            return $this;

        $criteria = new \CDbCriteria();
        $criteria->addCondition('is_enable = 1');
        /** @var \ProxyList[] $ProxyList */
        $ProxyList = \ProxyList::model()->findAll($criteria);
        foreach ($ProxyList as $Proxy)
            $this->proxy_list[] = sprintf('%s:%s', $Proxy->getIp(), $Proxy->getPort());

        return $this;
    }

    public function get($link, $delay = 0)
    {
        /**
         * @see JonnyW\PhantomJs\Message\Request
         **/
        $request = $this->_client->getMessageFactory()->createRequest($link, 'GET');
		$request->setTimeout(40000);
        if($delay > 0)
            $request->setDelay($delay);
        $request->addHeaders(array(
			//':authority'    => 'www.parimatch.com',
			//':method'    => 'GET',
			//':path'    => '/',
			//':scheme'    => 'https',
            'accept'    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            //'accept-encoding'    => 'gzip, deflate, br',
            'accept-language'    => 'en-US,en;q=0.9,ru;q=0.8,ja;q=0.7',
            'pragma'    => 'no-cache',
            'referer'           => 'https://www.google.com.ua/',
			'upgrade-insecure-requests' => 1,
			'user-agent'        => UserAgent::random(),
			'x-compress'		=> 'null',
        ));
        //$request->setTimeout(10);

        /**
         * @see JonnyW\PhantomJs\Message\Response
         **/
        $response = $this->_client->getMessageFactory()->createResponse();

        // Send the request
        $this->_client->send($request, $response);

        return $response->getContent();
    }

    public function getCanDelay($link, $delay)
    {
        $data = $this->get($link);
        if(preg_match('/challenge-form/ui', $data)) {
            $data = $this->get($link, $delay);
        }

        return $data;
    }
}