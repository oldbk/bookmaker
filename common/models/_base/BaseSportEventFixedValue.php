<?php

/**
 * This is the model base class for the table "sport_event_fixed_ratio".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "SportEventFixedValue".
 *
 * Columns in table "sport_event_fixed_ratio" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $event_id
 * @property string $ratio_name
 * @property string $ratio_value
 * @property integer $user_id
 * @property integer $create_at
 *
 */
abstract class BaseSportEventFixedValue extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'sport_event_fixed_value';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'SportEventFixedValue|SportEventFixedValues', $n);
	}

	public static function representingColumn() {
		return 'ratio_value';
	}

	public function rules() {
		return [
			['event_id, ratio_name, ratio_value, user_id', 'required'],
			['event_id, user_id, create_at', 'numerical', 'integerOnly'=>true],
			['ratio_name', 'length', 'max'=>50],
			['ratio_value', 'length', 'max'=>255],
			['event_id, ratio_name, ratio_value, user_id, create_at', 'safe', 'on'=>'search'],
		];
	}

	public function relations() {
		return [
		];
	}

	public function pivotModels() {
		return [
		];
	}

	public function attributeLabels() {
		return [
			'event_id' => Yii::t('app', 'Event'),
			'ratio_name' => Yii::t('app', 'Ratio Name'),
			'ratio_value' => Yii::t('app', 'Ratio Value'),
			'user_id' => Yii::t('app', 'User'),
			'create_at' => Yii::t('app', 'Create At'),
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('event_id', $this->event_id);
		$criteria->compare('ratio_name', $this->ratio_name, true);
		$criteria->compare('ratio_value', $this->ratio_value, true);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('create_at', $this->create_at);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}