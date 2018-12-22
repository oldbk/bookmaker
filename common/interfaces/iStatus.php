<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 14.02.2015
 * Time: 20:32
 */

namespace common\interfaces;
interface iStatus
{
    const STATUS_NEW            = 0;
    const STATUS_ENABLE         = 1;
    const STATUS_DISABLE        = 2;
    const STATUS_FINISH         = 4;
    const STATUS_ERROR          = 5;
    const STATUS_TRASH          = 6;
    const STATUS_HAVE_RESULT    = 7; //Используется только в группах ставок, для фиксации этапа
    const STATUS_CANCEL         = 8;
    const STATUS_DECLINE        = 9;
    const STATUS_LIVE           = 10;

    const RESULT_NEW        = 0;
    const RESULT_WIN        = 1;
    const RESULT_LOSS       = 2;
    const RESULT_RETURN     = 3;
    /** @deprecated use RESULT_RETURN */
    const RESULT_REFUND     = 4;
    const RESULT_HALF_WIN   = 5;
    const RESULT_ERROR      = 6;
    const RESULT_SET_K_1    = 7;

    const PROBLEM_STATUS_NO                 = 0;
    const PROBLEM_STATUS_RESULT             = 1;
    const PROBLEM_STATUS_RESULT_NOT_SAVE    = 2;
    const PROBLEM_STATUS_EVENT_NOT_SAVE     = 3;
    const PROBLEM_STATUS_NOT_PAYMENT        = 4;
    const PROBLEM_STATUS_DATE_WRONG         = 5;
}