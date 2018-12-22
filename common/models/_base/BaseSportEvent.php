<?php

/**
 * This is the model base class for the table "sport_event".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "SportEvent".
 *
 * Columns in table "sport_event" available as properties of the model,
 * followed by relations of table "sport_event" available as properties of the model.
 *
 * @property integer $id
 * @property integer $sport_id
 * @property string $sport_title
 * @property string $number
 * @property integer $date_int
 * @property string $date_string
 * @property string $team_1
 * @property string $team_2
 * @property integer $event_type
 * @property integer $update_at
 * @property integer $create_at
 * @property integer $status
 * @property integer $_v
 * @property integer $is_new
 * @property integer $have_result
 * @property integer $is_freeze
 *
 * @property Sport $sport
 */
abstract class BaseSportEvent extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'sport_event';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'SportEvent|SportEvents', $n);
	}

	public static function representingColumn() {
		return 'sport_title';
	}

	public function rules() {
		return [
			['sport_id, sport_title, number, date_int, date_string, event_type, status', 'required'],
			['sport_id, date_int, event_type, update_at, create_at, status, _v, is_new, have_result, is_freeze', 'numerical', 'integerOnly' => true],
			['sport_title, number, date_string, team_1, team_2', 'length', 'max' => 255],
			['team_1, team_2, update_at, create_at, _v, is_new, have_result, is_freeze', 'default', 'setOnEmpty' => true, 'value' => null],
			['id, sport_id, sport_title, number, date_int, date_string, team_1, team_2, event_type, update_at, create_at, status, _v, is_new, have_result, is_freeze', 'safe', 'on' => 'search'],
		];
	}

	public function relations() {
		return [
			'eventCronJobs' => [self::HAS_MANY, 'EventCronJobs', 'event_id'],
			'sport' => [self::BELONGS_TO, 'Sport', 'sport_id'],
		];
	}

	public function pivotModels() {
		return [
		];
	}

	public function attributeLabels() {
		return [
			'id' => Yii::t('app', 'ID'),
			'sport_id' => null,
			'sport_title' => Yii::t('app', 'Sport Title'),
			'number' => Yii::t('app', 'Number'),
			'date_int' => Yii::t('app', 'Date Int'),
			'date_string' => Yii::t('app', 'Date String'),
			'team_1' => Yii::t('app', 'Team 1'),
			'team_2' => Yii::t('app', 'Team 2'),
			'event_type' => Yii::t('app', 'Event Type'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
			'status' => Yii::t('app', 'Status'),
			'_v' => Yii::t('app', 'V'),
			'is_new' => Yii::t('app', 'Is New'),
			'have_result' => Yii::t('app', 'Have Result'),
			'is_freeze' => Yii::t('app', 'Is Freeze'),
			'eventCronJobs' => null,
			'sport' => null,
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('sport_id', $this->sport_id);
		$criteria->compare('sport_title', $this->sport_title, true);
		$criteria->compare('number', $this->number, true);
		$criteria->compare('date_int', $this->date_int);
		$criteria->compare('date_string', $this->date_string, true);
		$criteria->compare('team_1', $this->team_1, true);
		$criteria->compare('team_2', $this->team_2, true);
		$criteria->compare('event_type', $this->event_type);
		$criteria->compare('update_at', $this->update_at);
		$criteria->compare('create_at', $this->create_at);
		$criteria->compare('status', $this->status);
		$criteria->compare('_v', $this->_v);
		$criteria->compare('is_new', $this->is_new);
		$criteria->compare('have_result', $this->have_result);
		$criteria->compare('is_freeze', $this->is_freeze);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}