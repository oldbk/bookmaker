<?php

/**
 * This is the model base class for the table "sport_event_previous".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "SportEventPrevious".
 *
 * Columns in table "sport_event_previous" available as properties of the model,
 * followed by relations of table "sport_event_previous" available as properties of the model.
 *
 * @property integer $sport_event_id
 * @property integer $sport_id
 * @property string $sport_title
 * @property string $number
 * @property integer $date_int
 * @property string $date_string
 * @property string $team_1
 * @property string $team_2
 * @property string $fora_val_1
 * @property string $fora_val_2
 * @property string $fora_ratio_1
 * @property string $fora_ratio_2
 * @property string $total_val
 * @property string $total_more
 * @property string $total_less
 * @property string $ratio_p1
 * @property string $ratio_x
 * @property string $ratio_p2
 * @property string $ratio_1x
 * @property string $ratio_12
 * @property string $ratio_x2
 * @property string $itotal_val_1
 * @property string $itotal_val_2
 * @property string $itotal_more_1
 * @property string $itotal_more_2
 * @property string $itotal_less_1
 * @property string $itotal_less_2
 * @property integer $event_type
 * @property integer $update_at
 * @property integer $create_at
 * @property integer $status
 * @property integer $_v
 *
 * @property SportEvent $sportEvent
 * @property Sport $sport
 */
abstract class BaseSportEventPrevious extends BaseModel {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'sport_event_previous';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'SportEventPrevious|SportEventPreviouses', $n);
	}

	public static function representingColumn() {
		return 'sport_title';
	}

	public function rules() {
		return [
			['sport_event_id, sport_id, sport_title, number, date_int, date_string, event_type, status', 'required'],
			['sport_event_id, sport_id, date_int, event_type, update_at, create_at, status, _v', 'numerical', 'integerOnly'=>true],
			['sport_title, number, date_string, team_1, team_2, fora_val_1, fora_val_2, total_val, itotal_val_1, itotal_val_2', 'length', 'max'=>255],
			['fora_ratio_1, fora_ratio_2, total_more, total_less, ratio_p1, ratio_x, ratio_p2, ratio_1x, ratio_12, ratio_x2, itotal_more_1, itotal_more_2, itotal_less_1, itotal_less_2', 'length', 'max'=>19],
			['team_1, team_2, fora_val_1, fora_val_2, fora_ratio_1, fora_ratio_2, total_val, total_more, total_less, ratio_p1, ratio_x, ratio_p2, ratio_1x, ratio_12, ratio_x2, itotal_val_1, itotal_val_2, itotal_more_1, itotal_more_2, itotal_less_1, itotal_less_2, update_at, create_at, _v', 'default', 'setOnEmpty' => true, 'value' => null],
			['sport_event_id, sport_id, sport_title, number, date_int, date_string, team_1, team_2, fora_val_1, fora_val_2, fora_ratio_1, fora_ratio_2, total_val, total_more, total_less, ratio_p1, ratio_x, ratio_p2, ratio_1x, ratio_12, ratio_x2, itotal_val_1, itotal_val_2, itotal_more_1, itotal_more_2, itotal_less_1, itotal_less_2, event_type, update_at, create_at, status, _v', 'safe', 'on'=>'search'],
		];
	}

	public function relations() {
		return [
			'sportEvent' => [self::BELONGS_TO, 'SportEvent', 'sport_event_id'],
			'sport' => [self::BELONGS_TO, 'Sport', 'sport_id'],
		];
	}

	public function pivotModels() {
		return [
		];
	}

	public function attributeLabels() {
		return [
			'sport_event_id' => null,
			'sport_id' => null,
			'sport_title' => Yii::t('app', 'Sport Title'),
			'number' => Yii::t('app', 'Number'),
			'date_int' => Yii::t('app', 'Date Int'),
			'date_string' => Yii::t('app', 'Date String'),
			'team_1' => Yii::t('app', 'Team 1'),
			'team_2' => Yii::t('app', 'Team 2'),
			'fora_val_1' => Yii::t('app', 'Fora Val 1'),
			'fora_val_2' => Yii::t('app', 'Fora Val 2'),
			'fora_ratio_1' => Yii::t('app', 'Fora Ratio 1'),
			'fora_ratio_2' => Yii::t('app', 'Fora Ratio 2'),
			'total_val' => Yii::t('app', 'Total Val'),
			'total_more' => Yii::t('app', 'Total More'),
			'total_less' => Yii::t('app', 'Total Less'),
			'ratio_p1' => Yii::t('app', 'Ratio P1'),
			'ratio_x' => Yii::t('app', 'Ratio X'),
			'ratio_p2' => Yii::t('app', 'Ratio P2'),
			'ratio_1x' => Yii::t('app', 'Ratio 1x'),
			'ratio_12' => Yii::t('app', 'Ratio 12'),
			'ratio_x2' => Yii::t('app', 'Ratio X2'),
			'itotal_val_1' => Yii::t('app', 'Itotal Val 1'),
			'itotal_val_2' => Yii::t('app', 'Itotal Val 2'),
			'itotal_more_1' => Yii::t('app', 'Itotal More 1'),
			'itotal_more_2' => Yii::t('app', 'Itotal More 2'),
			'itotal_less_1' => Yii::t('app', 'Itotal Less 1'),
			'itotal_less_2' => Yii::t('app', 'Itotal Less 2'),
			'event_type' => Yii::t('app', 'Event Type'),
			'update_at' => Yii::t('app', 'Update At'),
			'create_at' => Yii::t('app', 'Create At'),
			'status' => Yii::t('app', 'Status'),
			'_v' => Yii::t('app', 'V'),
			'sportEvent' => null,
			'sport' => null,
		];
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('sport_event_id', $this->sport_event_id);
		$criteria->compare('sport_id', $this->sport_id);
		$criteria->compare('sport_title', $this->sport_title, true);
		$criteria->compare('number', $this->number, true);
		$criteria->compare('date_int', $this->date_int);
		$criteria->compare('date_string', $this->date_string, true);
		$criteria->compare('team_1', $this->team_1, true);
		$criteria->compare('team_2', $this->team_2, true);
		$criteria->compare('fora_val_1', $this->fora_val_1, true);
		$criteria->compare('fora_val_2', $this->fora_val_2, true);
		$criteria->compare('fora_ratio_1', $this->fora_ratio_1, true);
		$criteria->compare('fora_ratio_2', $this->fora_ratio_2, true);
		$criteria->compare('total_val', $this->total_val, true);
		$criteria->compare('total_more', $this->total_more, true);
		$criteria->compare('total_less', $this->total_less, true);
		$criteria->compare('ratio_p1', $this->ratio_p1, true);
		$criteria->compare('ratio_x', $this->ratio_x, true);
		$criteria->compare('ratio_p2', $this->ratio_p2, true);
		$criteria->compare('ratio_1x', $this->ratio_1x, true);
		$criteria->compare('ratio_12', $this->ratio_12, true);
		$criteria->compare('ratio_x2', $this->ratio_x2, true);
		$criteria->compare('itotal_val_1', $this->itotal_val_1, true);
		$criteria->compare('itotal_val_2', $this->itotal_val_2, true);
		$criteria->compare('itotal_more_1', $this->itotal_more_1, true);
		$criteria->compare('itotal_more_2', $this->itotal_more_2, true);
		$criteria->compare('itotal_less_1', $this->itotal_less_1, true);
		$criteria->compare('itotal_less_2', $this->itotal_less_2, true);
		$criteria->compare('event_type', $this->event_type);
		$criteria->compare('update_at', $this->update_at);
		$criteria->compare('create_at', $this->create_at);
		$criteria->compare('status', $this->status);
		$criteria->compare('_v', $this->_v);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}
}