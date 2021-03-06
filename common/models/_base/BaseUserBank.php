<?php

/**
 * This is the model base class for the table "user_bank".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "UserBank".
 *
 * Columns in table "user_bank" available as properties of the model,
 * followed by relations of table "user_bank" available as properties of the model.
 *
 * @property integer $user_id
 * @property integer $bank_number
 * @property string $bank_pass
 * @property string $price
 * @property integer $update_at
 * @property integer $create_at
 *
 * @property User $user
 */
abstract class BaseUserBank extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'user_bank';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'UserBank|UserBanks', $n);
	}

	public static function representingColumn() {
		return 'bank_pass';
	}

	public function rules() {
		return [
			['user_id, bank_number, bank_pass', 'required'],
			['user_id, bank_number, update_at, create_at', 'numerical', 'integerOnly'=>true],
			['bank_pass', 'length', 'max'=>255],
			['price', 'length', 'max'=>19],
			['price, update_at, create_at', 'default', 'setOnEmpty' => true, 'value' => null],
			['user_id, bank_number, bank_pass, price, update_at, create_at', 'safe', 'on'=>'search'],
		];
	}

	public function relations() {
		return [
			'user' => [self::BELONGS_TO, 'User', 'user_id'],
		];
	}

	public function pivotModels() {
		return [
		];
	}

	public function attributeLabels() {
		return [
			'user_id' => null,
			'bank_number' => Yii::t('app', 'Bank Number'),
			'bank_pass' => Yii::t('app', 'Bank Pass'),
			'price' => Yii::t('app', 'Price'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
			'user' => null,
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('bank_number', $this->bank_number);
		$criteria->compare('bank_pass', $this->bank_pass, true);
		$criteria->compare('price', $this->price, true);
		$criteria->compare('update_at', $this->update_at);
		$criteria->compare('create_at', $this->create_at);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}