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
use \common\factories\ResultFactory;
use \common\components\NException;
use \common\helpers\Convert;
use \common\factories\TransferFactory;
class ResultsCommand extends CConsoleCommand
{
    public function actionJobs()
    {
        $jobby = new \Jobby\Jobby();

        $command = sprintf('/usr/bin/php %s/console/yiic.php results index', ROOT_DIR);
        $jobby->add('Sport_results', array(
            'command' => $command,
            'schedule' => '* * * * *',
            'output' => sprintf('%s/console/runtime/jobby/sport/update_results.log', ROOT_DIR),
            'enabled' => true,
            'maxRuntime' => 1800,
        ));

        $jobby->run();
    }

    private $_link_result = "https://www.parimatch.com/res.html?&Date=%date%&SK=0";
    /** @var \common\factories\parser\results\_interfaces\iResult[] */
    private $_dataByDate = [];
    private $_parsedContent = [];
    public function actionIndex()
    {
        $starttime = time();
        Yii::app()->cache->set('results_index', ['status' => 'running', 'time' => time(), 'at' => time()]);

        $criteriaEvent = new CDbCriteria();
        $criteriaEvent->select = ['id', 'date_int', 'date_string', 'team_1', 'team_2', 'sport_type', 'sport_id'];
        $criteriaEvent->addCondition('`t`.have_result = 0 and `t`.is_trash = 0');
        $criteriaEvent->addCondition('`t`.date_int < :time');
        $criteriaEvent->addCondition('(`t`.sport_type = :tennis and `t`.date_int + 9000 < :time) OR (`t`.sport_type != :tennis and `t`.date_int + 7200 < :time)');
        $criteriaEvent->order = '`t`.date_int asc';
        $criteriaEvent->params = [
            ':time' => time(),
            ':tennis' => \common\helpers\SportHelper::SPORT_TENNIS_ID,
        ];

        $SportEvents = \SportEvent::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEvent::model()->tableName(), $criteriaEvent)
            ->queryAll();
        $criteriaEvent->select = '*';
        $criteriaEvent->addCondition('id = :id');
        $criteriaEvent->with = ['betCount'];

        $ResultEvent = null;
        $curl = Yii::app()->phantom->run(true);
        foreach ($SportEvents as $event_array) {
            $c = new CDbCriteria();
            $c->select = ['title'];
            $c->addCondition('id = :id');
            $c->params = [':id' => $event_array['sport_id']];
            $SportArray = Sport::model()
                ->getCommandBuilder()
                ->createFindCommand(Sport::model()->tableName(), $c)
                ->queryRow();

            $datetime = (new DateTime())
                ->setTimestamp($event_array['date_int'])
                ->setTimezone(new DateTimeZone('Europe/Kiev'));

            $date = $datetime->format('Ymd');

            var_dump('Event_id: '.$event_array['id'].' Date: '.$date.' - '.$event_array['date_string']);
            if(!isset($this->_dataByDate[(string)$date])) {
                $link = str_replace('%date%', $date, $this->_link_result);
                var_dump('Get data: '.$link);

                $data = $curl->getCanDelay($link, 6);
                if (!$data) {
                    var_dump($data);
                    //$data = null;
                    //continue;
                }
                $this->_dataByDate[(string)$date] = sprintf('<html>%s</html>', $data);
            }

            $params = [
                'html'          => $this->_dataByDate[(string)$date],
                'team1'         => $event_array['team_1'],
                'team2'         => $event_array['team_2'],
            ];

            $Factory = ResultFactory::factory($event_array['sport_type'], $params);
            $Factory->setSportTitle($SportArray['title'])
                ->setContent(isset($this->_parsedContent[(string)$date]) ? $this->_parsedContent[(string)$date] : []);
            $this->_parsedContent[(string)$date] = $Factory->parse();
            
            $ResultEvent = $Factory->getData();
            echo sprintf("Result: %s - %s. Is empty: %s\n",
                $ResultEvent->getTeam1Result(), $ResultEvent->getTeam2Result(), $ResultEvent->isEmpty() ? 'TRUE': 'FALSE');

            $t = Yii::app()->getDb()->beginTransaction();
            try {
                $criteriaEvent->params[':id'] = $event_array['id'];
                /** @var iSportEvent $Event */
                $Event = SportEvent::model()->find($criteriaEvent);
                if(!$Event) throw new NException();

                if($Event->betCount == 0 && time() - $Event->getDateInt() > (3600 * 30)) {
                    $Event->setIsTrash(true)
                        ->save(false);
                    $t->commit();
                    continue;
                }

                $Event->setResult($ResultEvent);
                $ProblemEventChecker = new \common\helpers\ProblemEventChecker($Event);
                $ProblemEventChecker->check(SportEventProblem::PROBLEM_NO_RESULT);

                $Event->setProblemCount($ProblemEventChecker->getProblemCount())
                    ->setHaveProblem($ProblemEventChecker->isHaveAnyProblem());

                if($ProblemEventChecker->isHaveProblem(SportEventProblem::PROBLEM_NO_RESULT)) {
                    if(!$Event->save())
                        throw new NException(
                            "Не удалось сохарнить событие {$Event->getId()}", iStatus::PROBLEM_STATUS_EVENT_NOT_SAVE,
                            [
                                'errors' => $Event->getErrors(),
                                'attributes' => $Event->getAttributes(),
                                'class' => 'ResultsCommand',
                                'method' => 'actionIndex',
                            ]);
                    $t->commit();
                    continue;
                }

                if(!$ResultEvent->insert($Event->getId())) {
                    throw new NException(
                        "Не удалось сохарнить результат для события {$Event->getId()}");
                }

                $r = $Event
                    ->setStatus(iStatus::STATUS_FINISH)
                    ->setHaveResult(1)
                    ->save();
                if(!$r)
                    throw new NException(
                        "Не удалось сохарнить событие после результата {$Event->getId()}", iStatus::PROBLEM_STATUS_EVENT_NOT_SAVE,
                        [
                            'errors' => $Event->getErrors(),
                            'attributes' => $Event->getAttributes(),
                            'class' => 'ResultsCommand',
                            'method' => 'actionIndex',
                        ]);
                $t->commit();
            } catch (Exception $ex) {
                $t->rollback();
                MException::logMongo($ex);
                var_dump($ex->getMessage());
            }
        }

        $time = time() - $starttime;
        Yii::app()->cache->set('results_index', ['status' => 'finish', 'time' => $time, 'at' => time()]);
    }

    /**
     * Выставляем результаты ставкам
     */
    public function actionBetting()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.status = :new');
        $criteria->with = [
            'eventRatio' => [
                'select' => ['type', 'value', 'event_id']
            ],
            'eventOriginal' => [
                'scopes' => 'have_result', 'select' => false
            ]
        ];
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
        ];
        //$criteria->limit = 100;
        /** @var \UserBetting[] $BettingList */
        $BettingList = \UserBetting::model()->findAll($criteria);
        $event_ids = [];
        foreach ($BettingList as $Bet) {
            if(!in_array($Bet->getEventId(), $event_ids))
                $event_ids[] = $Bet->getEventId();
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('`t`.id', $event_ids);
        $criteria->index = 'id';
        /** @var iSportEvent[] $EventList */
        $EventList = SportEvent::model()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->select = ['event_id', 'result_field', 'value'];
        $criteria->addInCondition('`t`.event_id', $event_ids);
        $event_result_list = \SportEventResult::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventResult::model()->tableName(), $criteria)
            ->queryAll();
        $event_result = [];
        foreach ($event_result_list as $result)
            $event_result[$result['event_id']][$result['result_field']] = $result['value'];
        unset($event_result_list);

        foreach ($BettingList as $Betting) {
            $t = Yii::app()->getDb()->beginTransaction();
            try {
                $ratio_list = [];
                foreach ($Betting->eventRatio as $ratio)
                    $ratio_list[$ratio->getType()] = $ratio->getValue();

                $Event = $EventList[$Betting->getEventId()]->copy();
                $Event->getNewRatio()->populateRecord($ratio_list, true, $Betting->getDopRatio(), $Betting->getPriceType());
                if(!isset($event_result[$Event->getId()]))
                    throw new NException('Не удалось найти результат для события', NException::ERROR_EVENT_RESULT, [
                        'event' => $Event->getAttributes(),
                        'results' => $event_result
                    ]);

                $Event->getResult()->populateRecord($event_result[$Event->getId()]);
                $ratio_val = $Event->getNewRatio()->getAttribute($Betting->getRatioType());

                if(!$Event->getResult()->isCancel()) {
                    /** @var \common\factories\ratio\_default\_interface\iRatio $CheckResult */
                    $CheckResult = \common\factories\RatioFactory::factory($Event->getSportType(), $Betting->getRatioType());
                    $CheckResult->setBet($Betting)
                        ->setRatioValue($ratio_val)
                        ->setEvent($Event)
                        ->check();
                    $Betting->setResultStatus($CheckResult->getStatus())
                        ->setRatioValue(Convert::getFormat($CheckResult->getRatioValue()));
                } else {
                    $Betting->setResultStatus(iStatus::RESULT_WIN)
                        ->setRatioValue(1.00);
                }

                $r = $Betting->setStatus(iStatus::STATUS_FINISH)
                    ->save();
                if(!$r)
                    throw new NException("Не удалось обновить ставку", NException::ERROR_BET, [
                        'bet_id' => $Betting->getId(),
                        'errors' => $Betting->getErrors(),
                        'attributes' => $Betting->getAttributes(),
                        'class' => 'ResultsCommand',
                        'method' => 'actionBetting'
                    ]);

                $t->commit();
            } catch (Exception $ex) {
                $t->rollback();
                MException::logMongo($ex, 'cronResult_betting');
            }
        }
    }

    /**
     * Выставляем статусы группам
     */
    public function actionGroup()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.status = :new');
        $criteria->with = [
            'finishedAllBet' => ['together' => true],
            'userBetting'
        ];
        $criteria->params = [
            ':new' => iStatus::STATUS_NEW,
        ];
        /** @var BettingGroup[] $GroupList */
        $GroupList = BettingGroup::model()->findAll($criteria);
        foreach ($GroupList as $Group) {
            try {
                if(!is_array($Group->userBetting))
                    throw new NException("В группе отсутствуют ставки", NException::ERROR_BET, [
                        'attributes' => $Group->getAttributes(),
                        'class' => 'ResultsCommand',
                        'method' => 'actionGroup'
                    ]);

                $isReturn = false;
                $isLoss = false;
                $result_status = iStatus::RESULT_WIN;
                $ratio = 1;
                /** @var UserBetting $Bet */
                foreach ($Group->userBetting as $Bet) {
                    if($Bet->getResultStatus() == iStatus::RESULT_LOSS)
                        $isLoss = true;
                    elseif($Bet->getResultStatus() == iStatus::RESULT_RETURN)
                        $isReturn = true;

                    $ratio *= $Bet->getRatioValue();
                }
                if($isLoss)
                    $result_status = iStatus::RESULT_LOSS;
                elseif($isReturn)
                    $result_status = iStatus::RESULT_RETURN;

                $r = $Group
                    ->setRatioValue(Convert::getFormat($ratio))
                    ->setStatus(($result_status == iStatus::RESULT_LOSS) ? iStatus::STATUS_FINISH : iStatus::STATUS_HAVE_RESULT)
                    ->setResultStatus($result_status)
                    ->save();
                if(!$r)
                    throw new NException("Не удалось обновить группу", NException::ERROR_BET, [
                        'group_id' => $Group->getId(),
                        'errors' => $Group->getErrors(),
                        'attributes' => $Group->getAttributes(),
                        'class' => 'ResultsCommand',
                        'method' => 'actionGroup'
                    ]);
            } catch (Exception $ex) {
                MException::logMongo($ex, 'cronResult_group');
            }
        }
    }

    /**
     * Выплата победителям
     */
    public function actionWin()
    {
        $criteria = new \CDbCriteria();
        $criteria->select = ['id'];
        $criteria->addCondition('`t`.status = :have_result');
        $criteria->addCondition('`t`.result_status = :win or `t`.result_status = :set_k1');
        $criteria->params = [
            ':have_result' => iStatus::STATUS_HAVE_RESULT,
            ':win'         => iStatus::RESULT_WIN,
            ':set_k1'      => iStatus::RESULT_SET_K_1
        ];
        $criteria->limit = 200;

        $GroupArray = BettingGroup::model()
            ->getCommandBuilder()
            ->createFindCommand(\BettingGroup::model()->tableName(), $criteria)
            ->queryAll();

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.status = :have_result');
        $criteria->addCondition('`t`.result_status = :win or `t`.result_status = :set_k1');
        $criteria->with = ['user'];
        $criteria->params = [
            ':have_result' => iStatus::STATUS_HAVE_RESULT,
            ':win'         => iStatus::RESULT_WIN,
            ':set_k1'      => iStatus::RESULT_SET_K_1
        ];
        foreach ($GroupArray as $_Group) {
            $t = Yii::app()->db->beginTransaction();
            try {
                $criteria->params[':id'] = $_Group['id'];
                $Group = BettingGroup::model()->find($criteria);
                if(!$Group)
                    throw new Exception();

                $Payment = TransferFactory::factory('payment', $Group->getPriceType(), [$Group->user, $Group]);
                $sum = Convert::getMoneyFormat($Group->getPrice() * $Group->getRatioValue());

                if(!$Payment->run($sum, 'Выплата за выигрыш'))
                    throw new NException("Не удалось добавить средства", NException::ERROR_RESULT_MONEY, [
                        'attributes' => $Group->getAttributes(),
                        'class' => 'ResultsCommand',
                        'method' => 'actionWin'
                    ]);

                $r = $Group->setStatus(iStatus::STATUS_FINISH)
                    ->setPaymentSum($sum)
                    ->save();
                if(!$r)
                    throw new NException("Не удалось обновить группу после зачислений", NException::ERROR_RESULT_MONEY, [
                        'group_id' => $Group->getId(),
                        'errors' => $Group->getErrors(),
                        'attributes' => $Group->getAttributes(),
                        'class' => 'ResultsCommand',
                        'method' => 'actionWin'
                    ]);

                $t->commit();

                $Payment->getUser()->sendBalanceToFront();
                unset($Payment, $sum);
            } catch (Exception $ex) {
                $t->rollback();
                MException::logMongo($ex, 'cronResult_win');
            }
        }
        unset($GroupArray);
    }

    /**
     * Возвраты
     * @throws CDbException
     *
     */
    public function actionReturn()
    {
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.status = :have_result');
        $criteria->addCondition('`t`.result_status = :win');
        $criteria->with = ['user'];
        $criteria->params = [
            ':have_result' => iStatus::STATUS_HAVE_RESULT,
            ':win'         => iStatus::RESULT_RETURN
        ];
        $criteria->limit = 200;

        $GroupArray = BettingGroup::model()
            ->getCommandBuilder()
            ->createFindCommand(\BettingGroup::model()->tableName(), $criteria)
            ->queryAll();

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.status = :have_result');
        $criteria->addCondition('`t`.result_status = :win');
        $criteria->with = ['user'];
        $criteria->params = [
            ':have_result' => iStatus::STATUS_HAVE_RESULT,
            ':win'         => iStatus::RESULT_RETURN
        ];
        foreach ($GroupArray as $_Group) {
            $t = Yii::app()->db->beginTransaction();
            try {
                $criteria->params[':id'] = $_Group['id'];
                $Group = BettingGroup::model()->find($criteria);
                if(!$Group)
                    throw new Exception();

                $Payment = TransferFactory::factory('payment', $Group->getPriceType(), [$Group->user, $Group]);
                $sum = $Group->getPrice();

                if(!$Payment->run($sum, 'Возврат по группе'))
                    throw new NException("Не удалось добавить средства", NException::ERROR_RESULT_MONEY, [
                        'attributes' => $Group->getAttributes(),
                        'class' => 'ResultsCommand',
                        'method' => 'actionReturn'
                    ]);

                $r = $Group->setStatus(iStatus::STATUS_FINISH)
                    ->setPaymentSum($sum)
                    ->save();
                if(!$r)
                    throw new NException("Не удалось обновить группу после зачислений", NException::ERROR_RESULT_MONEY, [
                        'group_id' => $Group->getId(),
                        'errors' => $Group->getErrors(),
                        'attributes' => $Group->getAttributes(),
                        'class' => 'ResultsCommand',
                        'method' => 'actionReturn'
                    ]);

                $t->commit();

                $Payment->getUser()->sendBalanceToFront();
                unset($Payment, $sum);
            } catch (Exception $ex) {
                $t->rollback();
                MException::logMongo($ex, 'cronResult_return');
            }
        }
        unset($GroupList);
    }
}