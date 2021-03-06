<?php

/**
 * This is the model base class for the table "team_alias".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "TeamAlias".
 *
 * Columns in table "team_alias" available as properties of the model,
 * followed by relations of table "team_alias" available as properties of the model.
 *
 * @property integer $team_id
 * @property string $title
 * @property integer $create_at
 *
 * @property Team $team
 */
abstract class BaseTeamAlias extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'team_alias';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'TeamAlias|TeamAliases', $n);
	}

	public static function representingColumn() {
		return 'title';
	}

	public function rules() {
		return [
			['team_id, title', 'required'],
			['team_id, create_at', 'numerical', 'integerOnly'=>true],
			['title', 'length', 'max'=>255],
			['create_at', 'default', 'setOnEmpty' => true, 'value' => null],
			['team_id, title, create_at', 'safe', 'on'=>'search'],
		];
	}

	public function relations() {
		return [
			'team' => [self::BELONGS_TO, 'Team', 'team_id'],
		];
	}

	public function pivotModels() {
		return [
		];
	}

	public function attributeLabels() {
		return [
			'team_id' => null,
			'title' => Yii::t('app', 'Title'),
			'create_at' => Yii::t('app', 'Create At'),
			'team' => null,
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('team_id', $this->team_id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('create_at', $this->create_at);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}