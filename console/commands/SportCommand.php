<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 03.09.2014
 * Time: 23:29
 */

ini_set('memory_limit', '2048M');
set_time_limit(1800);

use \common\interfaces\iStatus;
use \common\components\NException;
use \common\helpers\fixed\Value as FixedValue;
use \common\helpers\SportHelper;
use common\helpers\SocketIOHelper;
class SportCommand extends CConsoleCommand
{
    public function actionJobs()
    {
        $jobby = new \Jobby\Jobby();

        //$sport_list = ['football', 'tennis', 'basketball', 'hokkey'];
		$sport_list = ['football'];
        foreach ($sport_list as $sport) {
            $command = sprintf('/usr/bin/php %s/console/yiic.php sport sport2 --sport_name=%s', ROOT_DIR, $sport);
            $jobby->add(sprintf('Sport_%s', $sport), array(
                'command' => $command,
                'schedule' => '* * * * *',
                'output' => sprintf('%s/console/runtime/jobby/sport/update_%s.log', ROOT_DIR, $sport),
                'enabled' => true,
                'maxRuntime' => 3600,
            ));
        }

        $jobby->run();
    }

    public function actionSport2($sport_name)
	{
		var_dump('started');
		$sport_type = SportHelper::getIDByTitle($sport_name);
		if(!$sport_type)
			return;

		try {
			$curl = \Yii::app()->phantom->run(true);
			$link = SportHelper::getLink($sport_type);
			var_dump($link);
			$data = $curl->getCanDelay($link, 8);
			if(!$data) {
				var_dump($data);
				return;
			}
		} catch (Exception $ex) {
			var_dump($ex->getMessage());
			return;
		}

		$sport_ids = [];
		$dom = \phpQuery::newDocument('<html>'.$data.'</html>');
		/** @var \simple_html_dom_node $t */
		foreach ($dom->find('.sport_list .sport_matches div.s_m_i_name a') as $match) {
			$match = \phpQuery::pq($match);

			$title = trim($match->text());
			if(preg_match('/Чемпионат мира/ui', $title) && !preg_match('/групповой этап/ui', $title)) {
				continue;
			}

			$criteria = new CDbCriteria();
			$criteria->select = ['id', 'link'];
			$criteria->addCondition('title = :title');
			$criteria->params = [':title' => $title];
			/** @var Sport $Sport */
			$Sport = Sport::model()->find($criteria);
			if(!$Sport) {
				$Sport = new Sport();
			}

			$temp = explode('/', $match->attr('href'));
			$sport_id = (int)$temp[count($temp) - 1];
			if($sport_id <= 0) {
				continue;
			}

			$link = sprintf('https://www.betolimp.com/%s', ltrim($match->attr('href'), '/'));
			try {
				var_dump($link);
				$data = $curl->getCanDelay($link, 8);
				if(!$data) {
					var_dump($data);
					continue;
				}
			} catch (Exception $ex) {
				var_dump($ex->getMessage());
				continue;
			}

			$dom = \phpQuery::newDocument('<html>'.$data.'</html>');
			$index = 0;
			foreach ($dom->find('.sportmatchheader div.c_t_name') as $_i => $_el) {
				$_el = \phpQuery::pq($_el);
				$t_sport_id = (int)$_el->attr('data-id');
				if($sport_id !== $t_sport_id) {
					continue;
				}

				$index = $_i;
				break;
			}

			$Sport
				->setTitle($title)
				->setLink($link)
				->setSportType($sport_type)
				->save();

			foreach ($dom->find('.sportmatchheader div.ch_list:eq('.$index.') .ch_line') as $_event) {
				$_event = \phpQuery::pq($_event);

				$date_el = $_event->find('.ch_l.c_date');
				if(!$date_el->count()) {
					var_dump('cant find date');
					continue;
				}
				$date_string = trim($date_el->text());

				$command_el = $_event->find('.ch_l.c_name');
				if(!$command_el->count()) {
					var_dump('cant find team names');
					continue;
				}
				$command_string = trim($command_el->text());

				$event_id = (int)$_event->attr('data-id');
				if($event_id <= 0) {
					var_dump('cant fine event id');
					continue;
				}

				$link = sprintf('https://www.betolimp.com/ru/sports/match/%d', $event_id);
				try {
					var_dump($link);
					$data = $curl->getCanDelay($link, 8);
					if(!$data) {
						var_dump($data);
						continue;
					}
				} catch (Exception $ex) {
					var_dump($ex->getMessage());
					continue;
				}

				$template_name = null;
				$Validator = \common\factories\parser\ParserValidate::getValidator($Sport->getSportType(), '<html>'.$data.'</html>');
				if($Validator === false) {
					var_dump('No parser for '.$Sport->getLink());
					continue;
				}

				$items = $Validator
					->getParser()
					->run()
					->getEvents();

				$template_name = $Validator->getTemplateName();
				unset($Validator);

				var_dump($Sport->getLink().' - Count: '.count($items));
				foreach ($items as $item) {
					if(!preg_match('/(\w+).+?(\d+) (\d+\:\d+)/ui', $date_string, $out)) {
						continue;
					}
					$month = 6;
					if($out[1] != 'Июн') {
						$month = 7;
					}
					$time = explode(':', $out[3]);

					$_date =  (new DateTime())
						->setTimezone(new DateTimeZone('+0200'))
						->setDate(date('Y'), $month, $out[2])
						->setTime($time[0], $time[1]);
					$item['date_int'] = $_date->getTimestamp();
					$item['date_string'] = $_date->format('d.m.Y H:i:s');

					if($item['date_int'] < time()) {
						continue;
					}
					$temp_command = explode('-', $command_string);
					$item = CMap::mergeArray($item, [
						'team_1' 		=> trim($temp_command[0]),
						'team_2' 		=> trim($temp_command[1]),
						'number' 		=> $event_id,
						'sport_id' 		=> $Sport->getId(),
						'sport_title' 	=> $Sport->getTitle()
					]);

					$tDb = Yii::app()->getDb()->beginTransaction();
					$d = false;
					$model = null;
					try {
						if(($team_id = $this->teamAlias($item['team_1'])) !== false)
							$item['team_1_id'] = $team_id;
						if(($team_id = $this->teamAlias($item['team_2'])) !== false)
							$item['team_2_id'] = $team_id;

						$criteria = new CDbCriteria();
						$criteria->addCondition('`t`.team_1 = :team_1');
						$criteria->addCondition('`t`.team_2 = :team_2');
						$criteria->addCondition('`t`.date_int = :time or `t`.number = :number or ((`t`.date_int - :time) < 144000 and (`t`.date_int - :time) > 0) or ((`t`.date_int - :time) > -144000 and (`t`.date_int - :time) < 0)');
						$criteria->addCondition('`t`.is_trash = 0');
						$criteria->addCondition('`t`.status != :finish');
						$criteria->with = ['ratioFixed'];
						$criteria->params = [
							':team_1'   => $item['team_1'],
							':team_2'   => $item['team_2'],
							':time'     => $item['date_int'],
							':number'   => $item['number'],
							':finish'   => iStatus::STATUS_FINISH
						];

						/** @var iSportEvent $model */
						$model = SportEvent::model()->find($criteria);
						if(!$model)
							$model = SportEvent::getInstance($Sport->getSportType());

						$model
							->setSportType($Sport->getSportType())
							->setEventTemplate($template_name);

						$FixedValue = new FixedValue();
						if($model->hasRelated('ratioFixed') && $model->getRatioFixed()) {
							foreach ($model->getRatioFixed() as $RatioFixed)
								$FixedValue->{$RatioFixed->getRatioName()}($RatioFixed->getRatioName(), $RatioFixed->getRatioValue());
						}

						foreach($item as $name => $value) {
							if($FixedValue->{$name} !== null)
								continue;

							$model->setAttribute($name, $value);
						}

						$old_ratio = [];
						if(!$model->getIsNewRecord()) {
							$criteria = new CDbCriteria();
							$criteria->select = ['type', 'value'];
							$criteria->addCondition('event_id = :event_id');
							$criteria->addCondition('_v = :_v');
							$criteria->params = [
								':event_id' => $model->getId(),
								':_v' => $model->getV(),
							];
							$last_ratio = \SportEventRatio::model()
								->getCommandBuilder()
								->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
								->queryAll();
							foreach ($last_ratio as $ratio)
								$old_ratio[$ratio['type']] = $ratio['value'];
						}

						$model->getNewRatio()->populateRecord($item['ratio_list']);
						$model->getOldRatio()->populateRecord($old_ratio);
						$model->save(false);

						//Запускаем чекер проверок и проверяем на проблемы, событие передается по ссылке
						$ProblemEventChecker = new \common\helpers\ProblemEventChecker($model);
						$ProblemEventChecker->setEventFixedValue($FixedValue)
							->setNewEvent($item)
							->setSportId($Sport->getId())
							->setSportTitle($Sport->getTitle());
						$problem_list = [
							SportEventProblem::PROBLEM_DATE,
							SportEventProblem::PROBLEM_FORA,
							SportEventProblem::PROBLEM_SPORT_ID,
						];
						$ProblemEventChecker->check($problem_list);

						$model->setProblemCount($ProblemEventChecker->getProblemCount())
							->setHaveProblem($ProblemEventChecker->isHaveAnyProblem());

						if($model->getStatus() != iStatus::STATUS_ENABLE
							&& \common\singletons\Settings::init()->isEnableAutoapprove()
							&& $model->canAuto()) {

							$model->setStatus(iStatus::STATUS_ENABLE);
						}

						if($model->getStatus() == iStatus::STATUS_ENABLE)
							$model->setNotAuto(false)->setNotAutoReason(null);

						if(empty($old_ratio) || ($d = $model->haveDiff())) {
							if($d) {
								$model->setV($model->getV() + 1);
								SportEventRatio::toOld($model->getId());
								SportEventRatio::toLast($model->getId());
							}

							$model->getNewRatio()->insert($model->getId(), $model->getV(), false);
						}

						if(!$model->save(false))
							throw new NException(
								"Не удалось сохранить событие {$item['number']} - {$Sport->getTitle()}", NException::ERROR_PARSE,
								[
									'errors' => $model->getErrors(),
									'attributes' => $model->getAttributes(),
								]);

						$tDb->commit();


						unset($ProblemEventChecker);
						unset($SportFactory);
						unset($old_ratio);
						unset($item);
						unset($FixedValue);
						unset($criteria);
						unset($tDb);
						//die('die-1');
					} catch (\Exception $ex) {
						$tDb->rollback();
						MException::logMongo($ex, 'cron_event');
						var_dump($ex->getMessage());
					}

					if($d && $model) {
						SocketIOHelper::eventChange(['items' => [$model->getId() => $model->prepareForSocket()]]);
					}
					unset($model);
				}

				$sport_ids[] = $Sport->getId();
				Sport::model()->updateByPk($Sport->getId(), ['event_count' => count($items), 'sport_template' => $template_name]);
			}

			var_dump('finish');
		}
	}

    public function actionSport($sport_name)
    {
        var_dump('started');
        $sport_type = SportHelper::getIDByTitle($sport_name);
        if(!$sport_type)
            return;

        try {
            $curl = \Yii::app()->phantom->run(true);
            $link = SportHelper::getLink($sport_type);
            var_dump($link);
            $data = $curl->getCanDelay($link, 8);
            if(!$data) {
                var_dump($data);
                return;
            }
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
            return;
        }

        $sport_ids = [];
        /** @var \simple_html_dom $dom */
        $dom = \Sunra\PhpSimple\HtmlDomParser::str_get_html('<html>'.$data.'</html>');
        /** @var \simple_html_dom_node $t */
        foreach ($dom->find('ul[id=sports] li a') as $t) {
            $title = $t->text();
            $link = 'https://www.parimatch.com/'.ltrim($t->getAttribute('href'), '/');
            
            $criteria = new CDbCriteria();
            $criteria->select = ['id', 'link'];
            $criteria->addCondition('title = :title');
            $criteria->params = [':title' => $title];
            /** @var Sport $Sport */
            $Sport = Sport::model()->find($criteria);
            if(!$Sport)
                $Sport = new Sport();

            if(preg_match('/Пари-Матч|Париматч/ui', $title)) {
                continue;
            }

            $template_name = null;
            $Sport
                ->setTitle($title)
                ->setLink($link)
                ->setSportType($sport_type)
                ->save();
            $data = $curl->getCanDelay($Sport->getLink(), 8);
            if(!$data) continue;

            $Validator = \common\factories\parser\ParserValidate::getValidator($Sport->getSportType(), '<html>'.$data.'</html>');
            if($Validator === false) {
                var_dump('No parser for '.$Sport->getLink());
                continue;
            }

            $items = $Validator
                ->getParser()
                ->run()
                ->getEvents();

            $template_name = $Validator->getTemplateName();
            unset($Validator);

            var_dump($Sport->getLink().' - Count: '.count($items));
            foreach ($items as $item) {
                $_date =  (new DateTime())
                    ->setTimestamp($item['date_int'])
                    ->setTimezone(new DateTimeZone('Europe/Kiev'));
                $item['date_int'] = $_date->getTimestamp();
                $item['date_string'] = $_date->format('d.m.Y H:i:s');

                if($item['date_int'] < time()
                    || preg_match('/Пари-Матч|Париматч/ui', $item['team_1'])
                    || preg_match('/Пари-Матч|Париматч/ui', $item['team_2'])) {
                    continue;
                }

                $item['sport_id'] = $Sport->getId();
                $item['sport_title'] = $Sport->getTitle();

                $tDb = Yii::app()->getDb()->beginTransaction();
                $d = false;
                $model = null;
                try {
                    if(($team_id = $this->teamAlias($item['team_1'])) !== false)
                        $item['team_1_id'] = $team_id;
                    if(($team_id = $this->teamAlias($item['team_2'])) !== false)
                        $item['team_2_id'] = $team_id;

                    $criteria = new CDbCriteria();
                    $criteria->addCondition('`t`.team_1 = :team_1');
                    $criteria->addCondition('`t`.team_2 = :team_2');
                    $criteria->addCondition('`t`.date_int = :time or `t`.number = :number or ((`t`.date_int - :time) < 144000 and (`t`.date_int - :time) > 0) or ((`t`.date_int - :time) > -144000 and (`t`.date_int - :time) < 0)');
                    $criteria->addCondition('`t`.is_trash = 0');
                    $criteria->addCondition('`t`.status != :finish');
                    $criteria->with = ['ratioFixed'];
                    $criteria->params = [
                        ':team_1'   => $item['team_1'],
                        ':team_2'   => $item['team_2'],
                        ':time'     => $item['date_int'],
                        ':number'   => $item['number'],
                        ':finish'   => iStatus::STATUS_FINISH
                    ];

                    /** @var iSportEvent $model */
                    $model = SportEvent::model()->find($criteria);
                    if(!$model)
                        $model = SportEvent::getInstance($Sport->getSportType());

                    $model
                        ->setSportType($Sport->getSportType())
                        ->setEventTemplate($template_name);

                    $FixedValue = new FixedValue();
                    if($model->hasRelated('ratioFixed') && $model->getRatioFixed()) {
                        foreach ($model->getRatioFixed() as $RatioFixed)
                            $FixedValue->{$RatioFixed->getRatioName()}($RatioFixed->getRatioName(), $RatioFixed->getRatioValue());
                    }

                    foreach($item as $name => $value) {
                        if($FixedValue->{$name} !== null)
                            continue;

                        $model->setAttribute($name, $value);
                    }

                    $old_ratio = [];
                    if(!$model->getIsNewRecord()) {
                        $criteria = new CDbCriteria();
                        $criteria->select = ['type', 'value'];
                        $criteria->addCondition('event_id = :event_id');
                        $criteria->addCondition('_v = :_v');
                        $criteria->params = [
                            ':event_id' => $model->getId(),
                            ':_v' => $model->getV(),
                        ];
                        $last_ratio = \SportEventRatio::model()
                            ->getCommandBuilder()
                            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
                            ->queryAll();
                        foreach ($last_ratio as $ratio)
                            $old_ratio[$ratio['type']] = $ratio['value'];
                    }

                    $model->getNewRatio()->populateRecord($item['ratio_list']);
                    $model->getOldRatio()->populateRecord($old_ratio);
                    $model->save(false);

                    //Запускаем чекер проверок и проверяем на проблемы, событие передается по ссылке
                    $ProblemEventChecker = new \common\helpers\ProblemEventChecker($model);
                    $ProblemEventChecker->setEventFixedValue($FixedValue)
                        ->setNewEvent($item)
                        ->setSportId($Sport->getId())
                        ->setSportTitle($Sport->getTitle());
                    $problem_list = [
                        SportEventProblem::PROBLEM_DATE,
                        SportEventProblem::PROBLEM_FORA,
                        SportEventProblem::PROBLEM_SPORT_ID,
                    ];
                    $ProblemEventChecker->check($problem_list);

                    $model->setProblemCount($ProblemEventChecker->getProblemCount())
                        ->setHaveProblem($ProblemEventChecker->isHaveAnyProblem());

                    if($model->getStatus() != iStatus::STATUS_ENABLE
                        && \common\singletons\Settings::init()->isEnableAutoapprove()
                        && $model->canAuto()) {

                        $model->setStatus(iStatus::STATUS_ENABLE);
                    }

                    if($model->getStatus() == iStatus::STATUS_ENABLE)
                        $model->setNotAuto(false)->setNotAutoReason(null);

                    if(empty($old_ratio) || ($d = $model->haveDiff())) {
                        if($d) {
                            $model->setV($model->getV() + 1);
                            SportEventRatio::toOld($model->getId());
                            SportEventRatio::toLast($model->getId());
                        }

                        $model->getNewRatio()->insert($model->getId(), $model->getV(), false);
                    }

                    if(!$model->save(false))
                        throw new NException(
                            "Не удалось сохранить событие {$item['number']} - {$Sport->getTitle()}", NException::ERROR_PARSE,
                            [
                                'errors' => $model->getErrors(),
                                'attributes' => $model->getAttributes(),
                            ]);

                    $tDb->commit();


                    unset($ProblemEventChecker);
                    unset($SportFactory);
                    unset($old_ratio);
                    unset($item);
                    unset($FixedValue);
                    unset($criteria);
                    unset($tDb);
                    //die('die-1');
                } catch (\Exception $ex) {
                    $tDb->rollback();
                    MException::logMongo($ex, 'cron_event');
                    var_dump($ex->getMessage());
                }

                if($d && $model) {
                    SocketIOHelper::eventChange(['items' => [$model->getId() => $model->prepareForSocket()]]);
                }
                unset($model);
            }

            $sport_ids[] = $Sport->getId();
            Sport::model()->updateByPk($Sport->getId(), ['event_count' => count($items), 'sport_template' => $template_name]);
        }

        $criteria = new CDbCriteria();
        $criteria->addNotInCondition('id', $sport_ids);
        $criteria->addCondition('sport_type = :sport_type');
        $criteria->params[':sport_type'] = $sport_type;
        Sport::model()->updateAll(['event_count' => 0], $criteria);
    }

    private function teamAlias($team)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.title = :title');
        $criteria->addCondition('`teamAliases`.title = :title', 'OR');
        $criteria->with = ['teamAliases'];
        $criteria->params = [':title' => $team];
        /** @var Team $Team */
        $Team = Team::model()->find($criteria);
        if(!$Team) {
            $TeamNew = TeamAliasNew::model()->find('title = :title', [':title' => $team]);
            if(!$TeamNew) {
                $TeamNew = new TeamAliasNew();
                $TeamNew->setTitle($team)->save();
            }

            return false;
        } else
            return $Team->getId();
    }

    public function actionStats2()
    {
        $t = Yii::app()->db->beginTransaction();
        try {
            $criteria = new CDbCriteria();
            $criteria->addBetweenCondition('create_at', strtotime(date('Y-m-d 00:00:00', time())), strtotime(date('Y-m-d 23:59:59', time())));
            $Stats = Stats::model()->find($criteria);

            $day = date('d.m.Y', strtotime('-1 day'));
            if(!$Stats) {
                $Stats = new Stats();
                $Stats->setDatestring(date('Y-m-d H:i:s', strtotime('-1 day')));
            }

            $criteria = new CDbCriteria();
            $criteria->addCondition('payment_type = :input or payment_type = :output');
            $criteria->addBetweenCondition('create_at', strtotime($day.' 00:00:00'), strtotime($day.' 23:59:59'));
            $criteria->addNotInCondition('user_id', \common\components\WebUser::getAdminIds());
            $criteria->params = CMap::mergeArray($criteria->params, [':input' => UserBalance::BALANCE_TYPE_INPUT, ':output' => UserBalance::BALANCE_TYPE_OUTPUT]);
            /** @var UserBalance[] $models */
            $models = UserBalance::model()->findAll($criteria);

            foreach ($models as $Stat) {
                if($Stat->getOperationType() == UserBalance::OPERATION_TYPE_ADD)
                    $Stats = $this->inStat($Stat->getPriceType(), $Stat->getPrice(), $Stats);
                else
                    $Stats = $this->outStat($Stat->getPriceType(), $Stat->getPrice(), $Stats);
            }

            if(!$Stats->save())
                throw new NException('Не удалось записать статистику', 0,
                    [
                        'errors' => $Stats->getErrors(),
                        'attributes' => $Stats->getAttributes(),
                        'class' => 'LineCommand',
                        'method' => 'actionStats'
                    ]);

            $t->commit();
        } catch (Exception $ex) {
            $t->rollback();
            MException::logMongo($ex, 'cron_stats');
            var_dump($ex->getMessage());
        }
    }

    public function actionPopstats()
    {
        for($i = 10; $i >= 0; $i--) {
            $this->actionStats( strtotime('-'.$i.' day'));
        }
    }

    public function actionStats($timestamp = null)
    {
        $day = date('d.m.Y', $timestamp ? $timestamp : strtotime('-1 day'));
        var_dump($day);

        $criteria = new CDbCriteria();
        $criteria->select = 'sum(`t`.price) as sum';
        $criteria->addCondition('`t`.operation_type = :operation_type');
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addCondition('`t`.status = :finish or `t`.status = :new');
        $criteria->addNotInCondition('`t`.user_id', \common\components\WebUser::getAdminIds());
        $criteria->addBetweenCondition('`t`.create_at', strtotime($day.' 00:00:00'), strtotime($day.' 23:59:59'));
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':price_type' => UserBalance::TYPE_KR,
            ':finish' => iStatus::STATUS_FINISH,
            ':new' => iStatus::STATUS_NEW,
        ]);
        /** @var UserBalance $OutputKr */
        $OutputKr = \UserBalanceOutput::model()->find($criteria);

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
            ':price_type' => UserBalance::TYPE_EKR
        ]);
        /** @var \UserBalance $OutputEkr */
        $OutputEkr = \UserBalanceOutput::model()->find($criteria);

		$criteria->params = \CMap::mergeArray($criteria->params, [
			':operation_type' => \UserBalanceOutput::OPERATION_TYPE_TAKE,
			':price_type' => UserBalance::TYPE_GOLD
		]);
		/** @var \UserBalance $OutputGold */
		$OutputGold = \UserBalanceOutput::model()->find($criteria);

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
            ':price_type' => UserBalance::TYPE_KR
        ]);
        /** @var \UserBalance $inputKr */
        $inputKr = \UserBalanceInput::model()->find($criteria);

        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
            ':price_type' => UserBalance::TYPE_EKR
        ]);
        /** @var \UserBalance $inputEkr */
        $inputEkr = \UserBalanceInput::model()->find($criteria);

		$criteria->params = \CMap::mergeArray($criteria->params, [
			':operation_type' => \UserBalanceInput::OPERATION_TYPE_ADD,
			':price_type' => UserBalance::TYPE_EKR
		]);
		/** @var \UserBalance $inputGold */
		$inputGold = \UserBalanceInput::model()->find($criteria);


        $criteria = new CDbCriteria();
        $criteria->addBetweenCondition('stats_at',  strtotime($day.' 00:00:00'), strtotime($day.' 23:59:59'));
        $Stats = Stats::model()->find($criteria);
        if(!$Stats) {
            $Stats = new Stats();
            $Stats->setStatsAt($timestamp ? $timestamp : strtotime('-1 day'))
                ->setDatestring(date('Y-m-d H:i:s', $timestamp ? $timestamp : strtotime('-1 day')));
        }

        $Stats->setMoneyEkrIn($inputEkr->sum)
            ->setMoneyEkrOut($OutputEkr->sum)
            ->setMoneyKrIn($inputKr->sum)
            ->setMoneyKrOut($OutputKr->sum)
			->setMoneyGoldIn($inputGold->sum)
			->setMoneyGoldOut($OutputGold->sum);

        try {
            if(!$Stats->save())
                throw new NException('Не удалось записать статистику', 0,
                    [
                        'errors' => $Stats->getErrors(),
                        'attributes' => $Stats->getAttributes(),
                        'class' => 'SportCommand',
                        'method' => 'actionStats'
                    ]);
        } catch (Exception $ex) {
            MException::logMongo($ex, 'cron_stats');
            var_dump($ex->getMessage());
        }

    }

    private function inStat($type, $price, Stats $Stat)
    {
        switch ($type) {
            case UserBalance::TYPE_VOUCHER:
                //$Stat->addMoneyVoucherIn($price);
                break;
            case UserBalance::TYPE_EKR:
                $Stat->addMoneyEkrIn($price);
                break;
            case UserBalance::TYPE_KR:
                $Stat->addMoneyKrIn($price);
                break;
			case UserBalance::TYPE_GOLD:
				$Stat->addMoneyGoldIn($price);
				break;
        }

        return $Stat;
    }

    private function outStat($type, $price, Stats $Stat)
    {
        switch ($type) {
            case UserBalance::TYPE_VOUCHER:
                //$Stat->addMoneyVoucherOut($price);
                break;
            case UserBalance::TYPE_EKR:
                $Stat->addMoneyEkrOut($price);
                break;
            case UserBalance::TYPE_KR:
                $Stat->addMoneyKrOut($price);
                break;
			case UserBalance::TYPE_GOLD:
				$Stat->addMoneyGoldOut($price);
				break;
        }

        return $Stat;
    }
}