<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 22.11.2014
 * Time: 22:32
 */

namespace common\components\oldbk;

class Vouchers
{
    private $_vouchers_ids = [
        5 => [],
        15 => [],
        20 => [],
        25 => [],
        40 => [],
        100 => [],
        200 => [],
        300 => [],
    ];
    private $_vouchers_count = [
        5 => 0,
        15 => 0,
        20 => 0,
        25 => 0,
        40 => 0,
        100 => 0,
        200 => 0,
        300 => 0,
    ];

    /**
     * @param $array
     * id: "442602737"
     * name: "%C2%E0%F3%F7%E5%F0+5+%E5%EA%F0"
     * prototype: "100005"
     * ecost: "5.00"
     * img: "comm_5ekr.gif"
     */
    public function addVoucher($array)
    {
        if(!isset($array['ecost']))
            return;

        $type = (int)$array['ecost'];
        $this->_vouchers_count[$type]++;

        $this->_vouchers_ids[$type][] = $array['id'];
    }

    public function getCount($type)
    {
        $id = str_replace('voucher_', '', $type);

        return $this->_vouchers_count[$id];
    }

    /**
     * @param $type
     * @param int $limit
     * @return array
     */
    public function getIds($type, $limit = 0)
    {
        $id = str_replace('voucher_', '', $type);

        if($limit == 0)
            return $this->_vouchers_ids[$id];
        else {
            $returned = [];
            foreach ($this->_vouchers_ids[$id] as $id) {
                if(count($returned) == $limit)
                    break;
                $returned[] = $id;
            }

            return $returned;
        }
    }
} 