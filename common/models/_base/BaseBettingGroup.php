<?php

/**
 * This is the model base class for the table "betting_group".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "BettingGroup".
 *
 * Columns in table "betting_group" available as properties of the model,
 * followed by relations of table "betting_group" available as properties of the model.
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $bet_type
 * @property string $price
 * @property integer $price_type
 * @property string $ratio_value
 * @property integer $status
 * @property integer $result_status
 * @property string $payment_sum
 * @property integer $refund_at
 * @property integer $update_at
 * @property integer $create_at
 * @property string $create_at_datetime
 *
 * @property UserBetting[] $userBetting
 */
abstract class BaseBettingGroup extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'betting_group';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'BettingGroup|BettingGroups', $n);
	}

	public static function representingColumn() {
		return 'ratio_value';
	}

	public function rules() {
		return [
			['user_id, bet_type', 'required'],
			['user_id, bet_type, price_type, status, result_status, refund_at, update_at, create_at', 'numerical', 'integerOnly'=>true],
			['price, ratio_value, payment_sum', 'length', 'max'=>19],
			['create_at_datetime', 'safe'],
			['price, price_type, ratio_value, status, result_status, payment_sum, refund_at, update_at, create_at, create_at_datetime', 'default', 'setOnEmpty' => true, 'value' => null],
			['id, user_id, bet_type, price, price_type, ratio_value, status, result_status, payment_sum, refund_at, update_at, create_at, create_at_datetime', 'safe', 'on'=>'search'],
		];
	}

	public function relations() {
		return [
			'userBetting' => [self::HAS_MANY, 'UserBetting', 'bet_group_id'],
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
			'bet_type' => Yii::t('app', 'Bet Type'),
			'price' => Yii::t('app', 'Price'),
			'price_type' => Yii::t('app', 'Price Type'),
			'ratio_value' => Yii::t('app', 'Ratio Value'),
			'status' => Yii::t('app', 'Status'),
			'result_status' => Yii::t('app', 'Result Status'),
			'payment_sum' => Yii::t('app', 'Payment Sum'),
			'refund_at' => Yii::t('app', 'Refund At'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
			'create_at_datetime' => Yii::t('app', 'Create At Datetime'),
			'userBetting' => null,
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('bet_type', $this->bet_type);
		$criteria->compare('price', $this->price, true);
		$criteria->compare('price_type', $this->price_type);
		$criteria->compare('ratio_value', $this->ratio_value, true);
		$criteria->compare('status', $this->status);
		$criteria->compare('result_status', $this->result_status);
		$criteria->compare('payment_sum', $this->payment_sum, true);
		$criteria->compare('refund_at', $this->refund_at);
		$criteria->compare('update_at', $this->update_at);
		$criteria->compare('create_at', $this->create_at);
		$criteria->compare('create_at_datetime', $this->create_at_datetime, true);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}