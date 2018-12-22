<?php
/**
 * Class UserBalance
 *
 * @property float price
 */
class UserBalanceAkcii extends UserBalance
{
    /**
     * @param string $className
     * @return UserBalanceInput
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function defaultScope()
    {
        $t = $this->getTableAlias(false, false);
        return [
            'condition' => $t.'.payment_type = :'.$t.'payment_type and '.$t.'operation_type = :'.$t.'operation_type',
            'params' => [':'.$t.'payment_type' => self::BALANCE_TYPE_INPUT, ':'.$t.'operation_type' => self::OPERATION_TYPE_AKCII]
        ];
    }

    public function beforeValidate()
    {
        $r = parent::beforeValidate();
        $this->payment_type = self::BALANCE_TYPE_INPUT;
        $this->operation_type = self::OPERATION_TYPE_AKCII;

        return $r;
    }
}