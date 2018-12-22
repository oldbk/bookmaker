<?php

Yii::import('common.models._base.BaseAdminHistory');

/**
 * Class AdminHistory
 *
 * @property int item_id
 * @property SportEvent event
 * @property User user
 */
class AdminHistory extends BaseAdminHistory
{
    const ACTION_EVENT_ACCEPT           = 1;
    const ACTION_EVENT_CLOSE            = 2;
    const ACTION_EVENT_DECLINE          = 3;
    const ACTION_EVENT_REFUND           = 4;
    const ACTION_EVENT_TRASH            = 5;
    const ACTION_TRASH_RECOVERY         = 7;
    const ACTION_EVENT_CHANGE           = 9;
    const ACTION_EVENT_RATIO_CHANGE     = 10;

    const ACTION_SETTINGS_CHANGE        = 6;
    const ACTION_SETTINGS_CHANGE_PRICE  = 61;

    const ACTION_USER_CHANGE            = 8;

    const ACTION_PROBLEM_RESOLVE        = 9;

    private $custom_message = null;
    public function __construct($custom_message = null, $scenario='insert')
    {
        $this->custom_message = $custom_message;
        parent::__construct($scenario);
    }

    /**
     * @param string $className
     * @return AdminHistory
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function relations() {
        return [
            'event' => [
                self::BELONGS_TO, 'SportEvent', 'item_id', 'joinType' => 'left join'
            ],
            'user' => [
                self::BELONGS_TO, 'User', 'admin_id', 'joinType' => 'left join'
            ]
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'admin_id' => Yii::t('app', 'Admin'),
            'action_id' => Yii::t('app', 'Action'),
            'description' => Yii::t('app', 'Описание'),
            'create_at' => Yii::t('app', 'Дата'),
        ];
    }

    public function afterConstruct()
    {
        parent::afterConstruct();
    }

    /**
     * @param CEvent $Event
     */
    public function afterEventAccept(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;

        $this->setActionId(self::ACTION_EVENT_ACCEPT)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setDescription('Событие одобрено')
            ->setItemId($Sender->getId())
            ->save();
    }

    /**
     * @param CEvent $Event
     */
    public function afterRatioChange(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;
        if($this->custom_message === null)
            $this->custom_message = 'Изменили коэффициент';

        $this->setActionId(self::ACTION_EVENT_RATIO_CHANGE)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setDescription($this->custom_message)
            ->setItemId($Sender->getId())
            ->save();
    }

    /**
     * @param CEvent $Event
     */
    public function afterEventClose(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;

        $this->setActionId(self::ACTION_EVENT_CLOSE)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setDescription('Событие завершено и был возврат')
            ->setItemId($Sender->getId())
            ->save();
    }

    /**
     * @param CEvent $Event
     */
    public function afterEventDecline(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;

        $this->setActionId(self::ACTION_EVENT_DECLINE)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setDescription('Событие отменено')
            ->setItemId($Sender->getId())
            ->save();
    }

    /**
     * @param CEvent $Event
     */
    public function afterEventRefund(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;

        $this->setActionId(self::ACTION_EVENT_REFUND)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setDescription('Возврат средст по событию')
            ->setItemId($Sender->getId())
            ->save();
    }

    /**
     * @param CEvent $Event
     */
    public function afterEventTrash(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;

        $this->setActionId(self::ACTION_EVENT_TRASH)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setDescription('Отправили событие в корзину')
            ->setItemId($Sender->getId())
            ->save();
    }

    /**
     * @param CEvent $Event
     */
    public function afterTrashRecovery(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;

        $this->setActionId(self::ACTION_TRASH_RECOVERY)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setDescription('Восстановили событие')
            ->setItemId($Sender->getId())
            ->save();
    }

    /**
     * @param CEvent $Event
     */
    public function afterChange(CEvent $Event)
    {
        /** @var \common\interfaces\iAdminLog $Sender */
        $Sender = $Event->sender;
        if($Sender->getOldAttributes() == $Sender->getNewAttributes())
            return;

        $additional = '';
        $action_id = null;
        switch (true) {
            case ($Sender instanceof SportEvent):
                $action_id = self::ACTION_EVENT_CHANGE;
                break;
            case ($Sender instanceof User):
                $action_id = self::ACTION_USER_CHANGE;
                break;
            case ($Sender instanceof Settings):
                $action_id = self::ACTION_SETTINGS_CHANGE;
                break;
            case ($Sender instanceof PriceSettings):
                /** @var PriceSettings $Sender */
                $action_id = self::ACTION_SETTINGS_CHANGE_PRICE;
                $additional .= ' Валюта: '.$Sender->getShortName(). '('.$Sender->getId().')';
                break;
        }

        $this->setActionId($action_id)
            ->setAdminId(Yii::app()->getUser()->getId())
            ->setItemId($Sender->getId());
        $string = '';
        foreach(array_diff_assoc($Sender->getOldAttributes(), $Sender->getNewAttributes()) as $key => $value)
            $string .= sprintf('%s: %s => %s.%s', $this->getAttributeLabel($key), $Sender->getOldAttributes()[$key], $Sender->getNewAttributes()[$key], $additional);

        $this->setDescription(trim($string))
            ->save();
    }

    public function behaviors()
    {
        return [
            // Password behavior strategy
            'MTimestampBehavior' => [
                'class' => 'common\extensions\behaviors\MTimestampBehavior',
                'createAttribute' => 'create_at',
                'updateAttribute' => null
            ]
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * @param int $admin_id
     * @return $this
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getActionId()
    {
        return $this->action_id;
    }

    /**
     * @param int $action_id
     * @return $this
     */
    public function setActionId($action_id)
    {
        $this->action_id = $action_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param int $create_at
     * @return $this
     */
    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     * @return $this
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

    public function getFinalDescription()
    {
        $string = $this->getDescription();
        switch ($this->action_id) {
            case self::ACTION_EVENT_ACCEPT:
            case self::ACTION_EVENT_CHANGE:
            case self::ACTION_EVENT_TRASH:
            case self::ACTION_EVENT_REFUND:
            case self::ACTION_EVENT_DECLINE:
            case self::ACTION_EVENT_CLOSE:
            case self::ACTION_EVENT_RATIO_CHANGE:
            case self::ACTION_TRASH_RECOVERY:
            case self::ACTION_PROBLEM_RESOLVE:
                $string .= ' '.CHtml::tag('i', [
                        'class'         => 'glyphicon glyphicon-search pointer',
                        'data-link'     => Yii::app()->createUrl('/admin/event/info', ['event_id' => $this->getItemId()]),
                        'data-type'     => 'ajax',
                        'title'         => 'Посмотреть информацию о событии',
                        'data-toggle'   => 'tooltip'
                    ]);
                break;
        }

        return $string;
    }
}