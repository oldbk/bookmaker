<?php

/**
 * This is the model base class for the table "sport_event_result".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "SportEventResult".
 *
 * Columns in table "sport_event_result" available as properties of the model,
 * followed by relations of table "sport_event_result" available as properties of the model.
 *
 * @property integer $event_id
 * @property integer $team_1_part_1
 * @property integer $team_2_part_1
 * @property integer $team_1_part_2
 * @property integer $team_2_part_2
 * @property integer $update_at
 * @property integer $create_at
 *
 * @property SportEvent $event
 */
abstract class BaseSportEventResultOld extends BaseModel
{
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'sport_event_result_old';
    }

    public static function label($n = 1) {
        return Yii::t('app', 'SportEventResult|SportEventResults', $n);
    }

    public static function representingColumn() {
        return 'event_id';
    }

    public function rules() {
        return [
            ['event_id', 'required'],
            ['event_id, team_1_part_1, team_2_part_1, team_1_part_2, team_2_part_2, update_at, create_at', 'numerical', 'integerOnly'=>true],
            ['team_1_part_1, team_2_part_1, team_1_part_2, team_2_part_2, update_at, create_at, is_cancel', 'default', 'setOnEmpty' => true, 'value' => null],
            ['event_id, team_1_part_1, team_2_part_1, team_1_part_2, team_2_part_2, update_at, create_at, is_cancel', 'safe', 'on'=>'search'],
        ];
    }

    public function relations() {
        return [
            'event' => [self::BELONGS_TO, 'SportEvent', 'event_id'],
        ];
    }

    public function pivotModels() {
        return [
        ];
    }

    public function attributeLabels() {
        return [
            'event_id' => null,
            'team_1_part_1' => Yii::t('app', 'Team 1 Part 1'),
            'team_2_part_1' => Yii::t('app', 'Team 2 Part 1'),
            'team_1_part_2' => Yii::t('app', 'Team 1 Part 2'),
            'team_2_part_2' => Yii::t('app', 'Team 2 Part 2'),
            'update_at' => Yii::t('app', 'Update At'),
            'create_at' => Yii::t('app', 'Create At'),
            'event' => null,
        ];
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('event_id', $this->event_id);
        $criteria->compare('team_1_part_1', $this->team_1_part_1);
        $criteria->compare('team_2_part_1', $this->team_2_part_1);
        $criteria->compare('team_1_part_2', $this->team_1_part_2);
        $criteria->compare('team_2_part_2', $this->team_2_part_2);
        $criteria->compare('update_at', $this->update_at);
        $criteria->compare('create_at', $this->create_at);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}