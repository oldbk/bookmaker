<?php

/**
 * This is the model base class for the table "sport".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Sport".
 *
 * Columns in table "sport" available as properties of the model,
 * followed by relations of table "sport" available as properties of the model.
 *
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property integer $event_count
 * @property integer $event_active_count
 * @property integer $update_at
 * @property integer $create_at
 * @property integer $is_blocked
 * @property integer $is_new
 * @property integer $sport_type
 *
 * @property SportEvent[] $sportEvents
 */
abstract class BaseSport extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'sport';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Sport|Sports', $n);
	}

	public static function representingColumn() {
		return 'title';
	}

	public function rules() {
		return [
			['title, link', 'required'],
			['event_count, event_active_count, update_at, create_at, is_blocked, is_new, sport_type', 'numerical', 'integerOnly'=>true],
			['title, link', 'length', 'max'=>255],
			//['is_blocked, is_new, sport_type', 'default', 'setOnEmpty' => true, 'value' => null],
			['id, title, link, event_count, event_active_count, update_at, create_at, is_blocked, is_new, sport_type', 'safe', 'on'=>'search'],
		];
	}

	public function relations() {
		return [
			'sportEvents' => [self::HAS_MANY, 'SportEvent', 'sport_id'],
		];
	}

	public function pivotModels() {
		return [
		];
	}

	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'title' => Yii::t('app', 'Title'),
			'link' => Yii::t('app', 'Link'),
			'event_count' => Yii::t('app', 'Event Count'),
			'event_active_count' => Yii::t('app', 'Event Active Count'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
			'is_blocked' => Yii::t('app', 'Is Blocked'),
			'is_new' => Yii::t('app', 'Is New'),
			'sport_type' => Yii::t('app', 'Sport Type'),
			'sportEvents' => null,
			'sportEventPreviouses' => null,
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('link', $this->link, true);
		$criteria->compare('event_count', $this->event_count);
		$criteria->compare('event_active_count', $this->event_active_count);
		$criteria->compare('update_at', $this->update_at);
		$criteria->compare('create_at', $this->create_at);
		$criteria->compare('is_blocked', $this->is_blocked);
		$criteria->compare('is_new', $this->is_new);
		$criteria->compare('sport_type', $this->sport_type);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}