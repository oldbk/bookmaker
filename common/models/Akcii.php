<?php

Yii::import('common.models._base.BaseAkcii');

class Akcii extends BaseAkcii
{
    /**
     * @param string $className
     * @return Akcii
     */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Название'),
            'create_at' => Yii::t('app', 'Дата создания'),
        ];
    }

    public function behaviors()
    {
        return [
            // Password behavior strategy
            'MTimestampBehavior' => [
                'class' => 'common\extensions\behaviors\MTimestampBehavior',
                'createAttribute' => 'create_at',
                'updateAttribute' => null,
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
}