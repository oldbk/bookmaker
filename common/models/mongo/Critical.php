<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 30.09.2014
 * Time: 21:20
 */

class Critical extends BaseMongo
{
    /** @var int */
    public $type;

    /** @var string */
    public $message;

    /** @var string */
    public $file;

    /** @var int */
    public $line;

    /** @var boolean */
    public $is_new = true;

    /** @var DateTime */
    public $updated_at;

    /** @var DateTime */
    public $created_at;

    /** @var array */
    public $other = [];

    /** @var int */
    public $user_id;

    /** @var array */
    public $post = [];

    /** @var array */
    public $get = [];

    /**
     * @param string $className
     * @return Critical
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function collectionName()
    {
        return 'critical';
    }

    public function behaviors()
    {
        return [
            'CTimestampbehavior' => [
                'class' => 'EMongoTimestampBehaviour',
                'timestampExpression' => 'time()',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true
            ]
        ];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return $this
     */
    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param DateTime $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param DateTime $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNew()
    {
        return $this->is_new;
    }

    /**
     * @param boolean $is_new
     * @return $this
     */
    public function setIsNew($is_new)
    {
        $this->is_new = $is_new;
        return $this;
    }

    /**
     * @return array
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * @param array $other
     * @return $this
     */
    public function setOther($other)
    {
        $this->other = $other;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return array
     */
    public function getGet()
    {
        return $this->get;
    }

    /**
     * @param array $get
     * @return $this
     */
    public function setGet($get)
    {
        $this->get = $get;
        return $this;
    }

    /**
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param array $post
     * @return $this
     */
    public function setPost($post)
    {
        $this->post = $post;
        return $this;
    }
} 