<?php

/**
 * This is the model base class for the table "pages".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Pages".
 *
 * Columns in table "pages" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $dir
 * @property string $text
 * @property integer $update_at
 * @property integer $create_at
 *
 */
abstract class BasePages extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'pages';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Pages|Pages', $n);
	}

	public static function representingColumn() {
		return 'dir';
	}

	public function rules() {
		return [
			['user_id, dir, text', 'required'],
			['user_id, update_at, create_at', 'numerical', 'integerOnly'=>true],
			['dir', 'length', 'max'=>255],
			['update_at, create_at', 'default', 'setOnEmpty' => true, 'value' => null],
			['id, user_id, dir, text, update_at, create_at', 'safe', 'on'=>'search'],
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
			'dir' => Yii::t('app', 'Dir'),
			'text' => Yii::t('app', 'Text'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('dir', $this->dir, true);
		$criteria->compare('text', $this->text, true);
		$criteria->compare('update_at', $this->update_at);
		$criteria->compare('create_at', $this->create_at);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}