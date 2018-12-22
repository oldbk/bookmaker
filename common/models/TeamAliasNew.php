<?php

Yii::import('common.models._base.BaseTeamAliasNew');

class TeamAliasNew extends BaseTeamAliasNew
{
    public $is_main = false;
    public $parent = null;

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Название'),
            'find_at' => Yii::t('app', 'Find At'),
            'is_main' => Yii::t('app', 'Основное название'),
            'parent' => Yii::t('app', 'Родитель'),
        ];
    }

    /**
     * @param string $className
     * @return TeamAliasNew
     */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function behaviors()
    {
        return [
            // Password behavior strategy
            'MTimestampBehavior' => [
                'class' => 'common\extensions\behaviors\MTimestampBehavior',
                'createAttribute' => 'find_at',
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
    public function getFindAt()
    {
        return $this->find_at;
    }

    /**
     * @param int $find_at
     * @return $this
     */
    public function setFindAt($find_at)
    {
        $this->find_at = $find_at;
        return $this;
    }
}