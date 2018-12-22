<?php
/**
 * Class UserBalance
 *
 * @property float price
 */
class UserBalancePayment extends UserBalance
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
            'condition' => $t.'.payment_type = :'.$t.'payment_type',
            'params' => [':'.$t.'payment_type' => self::BALANCE_TYPE_PAYMENT]
        ];
    }

    public function beforeValidate()
    {
        $r = parent::beforeValidate();
        $this->payment_type = self::BALANCE_TYPE_PAYMENT;

        return $r;
    }
}