<?php
namespace common\factories\parser\parsers;
/**
 * Created by PhpStorm.
 */
abstract class Base
{
    /** @var null */
    private $_html = null;

    /** @var \phpQueryObject */
    private $_dom = null;

    /** @var \CList */
    protected $events = [];

    /**
     * @return array
     */
    abstract  public function getTdMapping();

    /**
     * @return array
     */
    abstract protected function getRatioField();

    /**
     * @return array
     */
    abstract protected function getPlaceholder();

    /**
     * @param boolean|string $html
     */
    public function __construct($html = false)
    {
        if($html !== false)
            $this->setHtml($html)
                ->setDom(\phpQuery::newDocument($html));
    }

    /**
     * @return \phpQueryObject
     */
    public function getDom()
    {
        return $this->_dom;
    }

    /**
     * @param \phpQueryObject $dom
     * @return $this
     */
    public function setDom($dom)
    {
        $this->_dom = $dom;
        return $this;
    }

    /**
     * @return null
     */
    public function getHtml()
    {
        return $this->_html;
    }

    /**
     * @param null $html
     * @return $this
     */
    public function setHtml($html)
    {
        $this->_html = $html;
        return $this;
    }

    /**
     * @return \CList
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param \CList $events
     * @return $this
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    protected function prepareMethod($field)
    {
        $method = 'get';
        foreach (explode('_', $field) as $item)
            $method .= ucfirst($item);

        return $method;
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     */
    protected function getNumber($td)
    {
        return ['number' => trim($td->text())];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     * @throws \Exception
     */
    protected function getDate($td)
    {
        if(!preg_match('/(\d{2})\/(\d{2})(\d{2}\:\d{2})/ui', $td->text(), $out))
            throw new \Exception('Неудалось найти дату у события');
        $date = sprintf('%s.%s.%s %s:00', $out[1], $out[2], date('Y'), $out[3]);
        $date_int = strtotime(sprintf('%s-%s-%s %s:00', date('Y'), $out[2], $out[1], $out[3]));

        return ['date_string' => $date, 'date_int' => $date_int];
    }

    /**
     * @param \phpQueryObject $td
     * @return array
     * @throws \Exception
     */
    protected function getTeams($td)
    {
        $span = $td->find('span.n');
        if($span->count()) {
            if(preg_match('/спец|предл/ui', $span->text())) {
                throw new \Exception('Специальное предложение. '.$span->text());
            }

            $span->remove();
        }

        $span = $td->find('span.tr');
        if($span->count()) {
            $span->remove();
        }

        $a = $td->find('a');
        if($a->count() == 2) {
            $a->eq(0)->remove();
        }
        $html = $td->html();

        $teams = [];
        foreach (explode('<br>', $html) as $key => $team)
            $teams[$key + 1] = trim(strip_tags($team));

        if(count($teams) != 2 || empty($teams[1]) || empty($teams[2]))
            throw new \Exception('Неудалось найти команды у события');

        return ['team_1' => $teams[1], 'team_2' => $teams[2]];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getForaVal($name, $item)
    {
		if(preg_match('/П1 с форой \((.+?)\)/ui', $name->text(), $out)) {
			return [
				'fora_val_1' => trim($out[1]),
				'fora_ratio_1' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
			];
		}
		if(preg_match('/П2 с форой \((.+?)\)/ui', $name->text(), $out)) {
			return [
				'fora_val_2' => trim($out[1]),
				'fora_ratio_2' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
			];
		}

        return [];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getForaRatio($name, $item)
    {
        return [];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getTotalVal($name, $item)
    {
		$return = [];
		if(!preg_match('/Тотал \((.+?)\) (?:мен|бол)/ui', $name->text(), $out)) {
			return $return;
		}
		$return['total_val'] = $out[1];

		if(strpos($name->text(), 'мен')) {
			$return['total_less'] = trim($item->find('.lineKoefDiv .lineKoef')->text());
		} else {
			$return['total_more'] = trim($item->find('.lineKoefDiv .lineKoef')->text());
		}

        return $return;
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getTotalMore($name, $item)
    {
        return [];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getTotalLess($name, $item)
    {
        return [];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getRatioP1($name, $item)
    {
		if(!preg_match('/победа первой/ui', $name->text())) {
			return [];
		}

        return [
        	'ratio_p1' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
		];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getRatioP2($name, $item)
    {
		if(!preg_match('/победа второй/ui', $name->text())) {
			return [];
		}

		return [
			'ratio_p2' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
		];
    }

	/**
	 * @param \phpQueryObject $name
	 * @param \phpQueryObject $item
	 * @return array
	 */
    protected function getItotalVal($name, $item)
    {
        return [];
    }

	/**
	 * @param \phpQueryObject $name
	 * @param \phpQueryObject $item
	 * @return array
	 */
    protected function getItotalMore($name, $item)
    {
        return [];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getItotalLess($name, $item)
    {
        return [];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getRatioX($name, $item)
    {
		if(!preg_match('/ничья/ui', $name->text())) {
			return [];
		}

		return [
			'ratio_x' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
		];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getRatio1x($name, $item)
    {
		if(!preg_match('/первая не проиграет/ui', $name->text())) {
			return [];
		}

		return [
			'ratio_1x' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
		];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getRatio12($name, $item)
    {
		if(!preg_match('/ничьей не будет/ui', $name->text())) {
			return [];
		}

		return [
			'ratio_12' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
		];
    }

    /**
     * @param \phpQueryObject $name
     * @param \phpQueryObject $item
     * @return array
     */
    protected function getRatioX2($name, $item)
    {
		if(!preg_match('/вторая не проиграет/ui', $name->text())) {
			return [];
		}

		return [
			'ratio_x2' => trim($item->find('.lineKoefDiv .lineKoef')->text()),
		];
    }

	/**
	 * @param \phpQueryObject $name
	 * @param \phpQueryObject $item
	 * @return array
	 */
    protected function getRatio20($name, $item)
    {
        return [];
    }

	/**
	 * @param \phpQueryObject $name
	 * @param \phpQueryObject $item
	 * @return array
	 */
    protected function getRatio21($name, $item)
    {
        return [];
    }

	/**
	 * @param \phpQueryObject $name
	 * @param \phpQueryObject $item
	 * @return array
	 */
    protected function getRatio02($name, $item)
    {
        return [];
    }

	/**
	 * @param \phpQueryObject $name
	 * @param \phpQueryObject $item
	 * @return array
	 */
    protected function getRatioPlus15($name, $item)
    {
        return [];
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function run()
    {
		$events = new \CList();

		$data = $this->getPlaceholder();
    	foreach ($this->getDom()->find('.sportmatchtable tr td.coef .line.coefkeeper') as $_item) {
			$_item = \phpQuery::pq($_item);
    		foreach ($this->getTdMapping() as $_field) {
    			if($_field === false) {
    				continue;
				}

				$method = $this->prepareMethod($_field);
				if(method_exists($this, $method)) {
					try {
						$name = $_item->find('.lineNameDiv .lineName');
						if(!$name->count()) {
							continue;
						}

						//var_dump('Index: '.$index. ' Method: '.$method);
						$data = \CMap::mergeArray($data, call_user_func_array([$this, $method], [$name, $_item]));
					} catch (\Exception $ex) {
						var_dump($ex->getMessage());
						continue 2;
					}
				}
			}
		}

		foreach ($data as $_n => $_v) {
			$data[$_n] = trim(strip_tags($_v));
		}

		$data = $this->prepareRatioList($data);
		if(!$events->contains($data)) {
			$events->add($data);
		}

		$this->setEvents($events);
		return $this;
    }

    protected function prepareRatioList($data)
    {
        $interest = array_intersect_key($data, array_fill_keys($this->getRatioField(), null));
        foreach ($interest as $key => $value) {
            unset($data[$key]);
        }

        $data['ratio_list'] = $interest;

        return $data;
    }

    protected function nextArray($next_count, &$tdMapping)
    {
        for($i = 0; $i < $next_count; $i++) {
            next($tdMapping);
        }
    }

}