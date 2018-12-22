<?php
/**
 * Created by PhpStorm.
 */

class ProxyCommand extends CConsoleCommand
{
    public function actionJobs()
    {
        $jobby = new \Jobby\Jobby();

        $adapter_list = ['bestProxies'];
        foreach ($adapter_list as $adapter) {
            $command = sprintf('/usr/bin/php %s/console/yiic.php proxy %s', ROOT_DIR, $adapter);
            var_dump($command);
            $jobby->add(sprintf('Proxy_%s', $adapter), array(
                'command' => $command,
                'schedule' => '*/5 * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/update_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_1', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=0', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_2', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=1', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_3', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=2', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_4', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=3', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_5', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=4', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_6', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=5', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_7', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=6', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_8', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=7', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_9', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=8', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));

            $jobby->add(sprintf('Proxy_check_%s_10', $adapter), array(
                'command' => sprintf('/usr/bin/php %s/console/yiic.php proxy check --adapter=%s --count=9', ROOT_DIR, $adapter),
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/proxy/check_%s.log', ROOT_DIR, $adapter),
                'enabled' => true,
                'maxRuntime' => 3600 * 24,
            ));
        }

        $jobby->run();
        var_dump('run');
    }

    public function actionUser()
    {
        $data = Yii::app()->phantom->run()
            ->getCanDelay('http://whatsmyuseragent.com/', 5);
        var_dump($data);
    }

	public function actionBestProxies()
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('ip', 'port');
        /** @var ProxyList[] $ProxyList */
        $ProxyList = ProxyList::model()->findAll($criteria);
        $List = [];
        foreach ($ProxyList as $Proxy)
            $List[] = $Proxy->getIp();

        $curl = Yii::app()->curl;
        $proxy_list = $curl->get('http://api.best-proxies.ru/proxylist.txt?key=41cee5761f68a79aa7793c57e03fca64&speed=1,2,3&unique=1&level=1,2&limit=0');
        if(!$proxy_list)
            return;

		$rows = explode("\n", $proxy_list);
		var_dump('Count: ' . count($rows));
		foreach ($rows as $row) {
			$proxy = explode(':', $row);
            $ip = trim($proxy[0], " \n");
            $port = (int)trim($proxy[1], " \n");
            if(in_array($ip, $List)) {
                continue;
            }

            $model = new ProxyList();
            $model->setIp($ip)
                ->setPort($port)
                ->setProxySource('bestProxies')
                ->save();

            $List[] = $ip;
		}
    }

    public function actionAwmproxy()
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('ip', 'port');
        /** @var ProxyList[] $ProxyList */
        $ProxyList = ProxyList::model()->findAll($criteria);
        $List = [];
        foreach ($ProxyList as $Proxy)
            $List[] = $Proxy->getIp();

        $curl = Yii::app()->curl;

        $links = [
            'http://awmproxy.com/3001withoutruproxy.txt',
            'http://awmproxy.com/701withoutruproxy.txt',
            'http://awmproxy.com/999withoutruproxy.txt',
            'http://awmproxy.com/888withoutruproxy.txt',
            'http://awmproxy.com/777withoutruproxy.txt',
        ];
        $rows = [];
        foreach ($links as $link) {
            $proxy_list = $curl->get('http://awmproxy.com/701withoutruproxy.txt');
            if(!$proxy_list)
                continue;

            $rows = array_merge($rows, explode("\n", $proxy_list));
        }

        $i = 0;
        foreach ($rows as $row) {
            $proxy = explode(':', $row);
            if(!isset($proxy[0]) || !isset($proxy[1])) {
                continue;
            }
            
            $ip = trim($proxy[0], " \n");
            var_dump($ip);
            $port = (int)trim($proxy[1], " \n");
            if(in_array($ip, $List)) {
                continue;
            }

            $model = new ProxyList();
            $model->setIp($ip)
                ->setPort($port)
                ->setProxySource('awmproxy')
                ->save();
            $List[] = $ip;
            
            $i++;
        }
        var_dump($i);
    }
	
    public function actionHideMe()
    {
        $criteria = new CDbCriteria();
        $criteria->select = array('ip', 'port');
        /** @var ProxyList[] $ProxyList */
        $ProxyList = ProxyList::model()->findAll($criteria);
        $List = [];
        foreach ($ProxyList as $Proxy)
            $List[] = $Proxy->getIp();

        $curl = Yii::app()->curl;
        $proxy_list = $curl->get('http://hideme.ru/api/proxylist.php?out=js&code=110194341791793&maxtime=5000&anon=4');
        if(!$proxy_list)
            return;

        $proxy_list = CJSON::decode($proxy_list);
        var_dump('Count: ' . count($proxy_list));
        foreach ($proxy_list as $proxy) {
            if(in_array($proxy['ip'], $List) || strtolower($proxy['country_code']) == 'ru') {
                continue;
            }

            $model = new ProxyList();
            $model->setIp($proxy['ip'])
                ->setPort($proxy['port'])
                ->setDelay($proxy['delay'])
                ->setCountryCode($proxy['country_code'])
                ->setCountryName($proxy['country_name'])
                ->setProxySource('hideMe')
                ->save();
            $allready[] = $proxy['ip'];
        }
    }

    public function actionCheck($adapter, $count)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('proxy_source = :proxy_source and (in_process = 0 or update_at <= :datetime)');
        $criteria->limit = 1;
        $criteria->offset = $count;
        $criteria->order = 'attemt asc';
        $criteria->params = [
            ':proxy_source' => $adapter,
            ':datetime' => (new DateTime())->modify('-1 hour')->getTimestamp()
        ];
        /** @var ProxyList $Proxy */
        $Proxy = ProxyList::model()->find($criteria);
        if(!$Proxy) {
            return;
        }
        $Proxy->in_process = 1;
        $Proxy->save();

        $db = Yii::app()->db->beginTransaction();
        try {
            $criteria = new CDbCriteria();
            $criteria->addCondition('proxy_source = :proxy_source and (in_process = 0 or update_at <= :datetime)');
            $criteria->params = [
                ':proxy_source' => $adapter,
                ':datetime' => (new DateTime())->modify('-1 hour')->getTimestamp()
            ];
            /** @var ProxyList $Proxy */
            $Proxy = ProxyList::model()->find('port = :port and ip = :ip', [':port' => $Proxy->port, ':ip' => $Proxy->ip]);

            var_dump(sprintf('Proxy: %s:%s', $Proxy->getIp(), $Proxy->getPort()));
            $r = $this->checkProxy($Proxy->getIp(), $Proxy->getPort());
            var_dump(sprintf('Result: %s', $r ? 'TRUE' : 'FALSE'));
            if(!$r) $Proxy->addAttemt();
            else $Proxy->setAttemt(0);
            $Proxy->setIsEnable($r)
                ->setInProcess(0)
                ->save();

            $db->commit();
        } catch (Exception $ex) {
            $db->rollback();
        }

    }

    private function checkProxy($proxy, $port)
    {
        $data = Yii::app()->phantom->run(sprintf('%s:%s', $proxy, $port))
            ->getCanDelay('https://www.betolimp.com/ru/', 5);

        $r = (preg_match('/О Компании BetOlimp/ui', $data) && preg_match('/main\.betolimp\.com\/affiliates/ui', $data));
        if(!$r) {
        	\common\helpers\FileHelper::write($data, $proxy, 'log');
		}

        return $r;
    }
}