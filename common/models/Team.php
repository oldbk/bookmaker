<?php

Yii::import('common.models._base.BaseTeam');

class Team extends BaseTeam
{
    /**
     * @param string $className
     * @return Team
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
                'createAttribute' => 'create_at',
                'updateAttribute' => 'update_at',
                'setUpdateOnCreate' => true
            ]
        ];
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Название'),
            'aliases_count' => Yii::t('app', 'Aliases Count'),
            'update_at' => Yii::t('app', 'Update At'),
            'create_at' => Yii::t('app', 'Добавлена'),
            'teamAliases' => null,
        ];
    }

    public function relations() {
        return [
            'teamAliases' => [self::HAS_MANY, 'TeamAlias', 'team_id'],
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
    public function getAliasesCount()
    {
        return $this->aliases_count;
    }

    /**
     * @param int $aliases_count
     * @return $this
     */
    public function setAliasesCount($aliases_count)
    {
        $this->aliases_count = $aliases_count;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @param int $update_at
     * @return $this
     */
    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
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

    public function getAliases()
    {
        $aliases = '';
        if($this->hasRelated('teamAliases') && $this->teamAliases) {
            foreach ($this->teamAliases as $alias)
                $aliases .= $alias->getTitle().'<br>';
        }

        return $aliases;
    }
}