<?php

/**
 * YiiNewRelicWebAppBehavior
 *
 * @author    Paul Lowndes <github@gtcode.com>
 * @author    GTCode
 * @link      http://www.GTCode.com/
 * @package   YiiNewRelic
 * @version   0.02
 * @category  ext*
 *
 * This class is designed for use with YiiNewRelic.  Please see that class for
 * more information.
 *
 * @see {@link http://newrelic.com/about About New Relic}
 * @see {@link https://newrelic.com/docs/php/the-php-api New Relic PHP API}
 */
class YiiNewRelicWebAppBehavior extends CBehavior
{

    public function events() {
        return array(
            'onBeginRequest' => 'handleBeginRequest',
        );
    }

    public function handleBeginRequest($event) {
        $event->sender->newRelic->setYiiAppName();
    }

}
