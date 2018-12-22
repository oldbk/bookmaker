<?php

/**
 * This is the model base class for the table "user_output_request".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "UserOutputRequest".
 *
 * Columns in table "user_output_request" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $price
 * @property integer $price_type
 * @property integer $status
 * @property integer $moderator_id
 * @property integer $update_at
 * @property integer $create_at
 *
 */
abstract class BaseUserOutputRequest extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'user_output_request';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'UserOutputRequest|UserOutputRequests', $n);
	}

	public static function representingColumn() {
		return 'price';
	}

	public function rules() {
		return [
			['user_id, price_type, status', 'required'],
			['user_id, price_type, status, moderator_id, update_at, create_at', 'numerical', 'integerOnly'=>true],
			['price', 'length', 'max'=>19],
			['price, update_at, create_at', 'default', 'setOnEmpty' => true, 'value' => null],
			['id, user_id, price, price_type, status, moderator_id, update_at, create_at', 'safe', 'on'=>'search'],
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
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User'),
			'price' => Yii::t('app', 'Price'),
			'price_type' => Yii::t('app', 'Price Type'),
			'status' => Yii::t('app', 'Status'),
			'moderator_id' => Yii::t('app', 'Moderator'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('price', $this->price, true);
		$criteria->compare('price_type', $this->price_type);
		$criteria->compare('status', $this->status);
		$criteria->compare('moderator_id', $this->moderator_id);
		$criteria->compare('update_at', $this->update_at);
		$criteria->compare('create_at', $this->create_at);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}