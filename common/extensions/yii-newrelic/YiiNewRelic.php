<?php

/**
 * YiiNewRelic
 *
 * @author    Paul Lowndes <github@gtcode.com>
 * @author    GTCode
 * @link      http://www.GTCode.com/
 * @package   YiiNewRelic
 * @version   0.02
 * @category  ext*
 *
 * This class is a Yii wrapper around New Relic PHP API.
 *
 * @see {@link http://newrelic.com/about About New Relic}
 * @see {@link https://newrelic.com/docs/php/the-php-api New Relic PHP API}
 *
 * To use this extension, you must sign up for an account with New Relic, and
 * follow their instructions on how to install the PHP agent on your server.
 *
 * Requirements:
 *   PHP:
 *     - PHP version 5.2+
 *     - New Relic for PHP [https://newrelic.com/docs/php/new-relic-for-php]
 *   OS:
 *     - Linux 2.6+, glibc 2.5+ with NPTL support
 *     - OpenSolaris 10
 *     - FreeBSD 7.3+
 *     - MacOS/X 10.5+
 *   Web Serever:
 *     - Apache 2.2 or 2.4 via mod_php
 *   CPU:
 *     - Intel Architecture
 *
 * Configuration:
 *   - Install the New Relic PHP driver on your web server.
 *
 *   - Place this extension in /protected/extensions/yii-newrelic/.
 *
 *   - In main.php, add the following to 'components':
 *         'newRelic' => array(
 *             'class' => 'ext.yii-newrelic.YiiNewRelic',
 *         ),
 *         'clientScript' => array(
 *             'class' => 'ext.yii-newrelic.YiiNewRelicClientScript',
 *         ),
 *
 *   - If you are using a script that subclasses CClientScript, instead of
 *     adding 'clientScript' to your 'components', you will instead need to
 *     orphan that extension's script and extend it from YiiNewRelicClientScript
 *     instead.  To do so, change 'extends CClientScript' to
 *     'extends YiiNewRelicClientScript', and then add a line before that class
 *     declaration that says:
 *         Yii::import('ext.yii-newrelic.YiiNewRelicClientScript');
 *
 *   - In main.php, add the following to the top-level array:
 *         'behaviors' => array(
 *             'newRelic' => array(
 *                  'class' => 'ext.yii-newrelic.behaviors.YiiNewRelicWebAppBehavior',
 *             ),
 *         ),
 *
 *   - Create subclass of CWebApplication, e.g. NewRelicApplication
 *
 *   - In this new class, e.g. NewRelicApplication, add a method::
 *         public function beforeControllerAction($controller, $action) {
 *             Yii::app()->newRelic->setTransactionName($controller->id, $action->id);
 *             return parent::beforeControllerAction($controller, $action);
 *         }
 *
 *   - To use your new subclassed CWebApplication, modify index.php similar to:
 *         $config=dirname(__FILE__).'/../protected/config/main.php';
 *         require_once(dirname(__FILE__).'/../yii-1.1.12.b600af/framework/yii.php');
 *         require_once(dirname(__FILE__).'/../protected/components/system/PromocastApplication.php');
 *         $app = new NewRelicApplication($config);
 *         $app->run();
 *
 *   - In console.php, add the following to 'components':
 *         'newRelic' => array(
 *             'class' => 'ext.yii-newrelic.YiiNewRelic',
 *         ),
 *
 *   - In console.php, add the following to the top-level array:
 *         'behaviors' => array(
 *             'newRelic' => array(
 *                  'class' => 'ext.yii-newrelic.behaviors.YiiNewRelicConsoleAppBehavior',
 *             ),
 *         ),
 *
 * Usage: Please see individual public method calls for more information.  The
 *        base configuration above will provide a wealth of valuable data,
 *        without using any of the below methods.
 *
 * Known Issues: 1) A future release will aim to avoid needing to call
 *                  YiiNewRelic::nameTransaction() via CWebApplication subclass.
 *                  This seems to be the only reliable mechanism for determining
 *                  the actual controller/action in use.  An attempt was made to
 *                  use Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest())
 *                  in YiiNewRelicWebAppBehavior, but this does not seem to
 *                  produce consistent results.
 *               2) Console apps currently only set the class name to
 *                  YiiNewRelic::nameTransaction().  A future release will
 *                  attempt to include the action as well.
 *
 */
class YiiNewRelic extends CApplicationComponent
{

    private $extensionLoaded;

    /**
     * Whether to set the New Relic application name simply to the Yii app name
     */
    public $setAppNameToYiiName = true;

    /**
     * Initializes the New Relic extension.
     */
    public function init() {
        $this->extensionLoaded = extension_loaded('newrelic');
    }

    /**
     * This is used with each wrapped New Relic function to determine whether
     * the extension is loaded and thus whether to perform or skip the function.
     * @return boolean true if extension is not loaded, false if not.
     */
    private function skip() {
        return !$this->extensionLoaded;
    }

    /**
     * This helper will set the New Relic application name to that of your
     * internal Yii application name.
     *
     * Note: The New Relic API function newrelic_set_appname() offers more
     * flexibility, please see their documentation for more details.
     */
    public function setYiiAppName() {
        if ($this->skip()) {
            return;
        }
        $this->setAppName(Yii::app()->name);
    }

    /**
     * This helper will set the name_transaction, given the Controller ID
     * and Action ID.  It will also try to prepend the current module, if set.
     *
     * @param string $controllerId Name of the current controller
     * @param string $actionId Name of the current action
     */
    public function setTransactionName($controllerId, $actionId) {
        $route = $controllerId . '/' . $actionId;
        $module = Yii::app()->controller->module;
        if ($module instanceof CModule) {
            $route = $module->getId() . '/' . $route;
        }
        $this->nameTransaction($route);
    }

    /**
     * Begin New Relic PHP Agent API wrapper methods
     */

    /**
     * Adds a custom parameter to current web transaction, e.g. customer's full
     * name.
     *
     * @param string $key Name of custom parameter
     * @param string $value Value of custom parameter
     */
    public function addCustomParameter($key, $value) {
        if ($this->skip()) {
            return;
        }
        newrelic_add_custom_parameter($key, $value);
    }

    /**
     * Adds a user defined functions or methods to the list to be instrumented.
     *
     * Internal PHP functions cannot have custom tracing.
     *
     * @param string $name Either 'functionName', or 'ClassName::functionName'
     */
    public function addCustomTracer($name) {
        if ($this->skip()) {
            return;
        }
        newrelic_add_custom_tracer($name);
    }

    /**
     * Whether to mark as a background job or web application.
     *
     * @param boolean $flag true if background job, false if web application
     */
    public function backgroundJob($flag=true) {
        if ($this->skip()) {
            return;
        }
        newrelic_background_job($flag);
    }

    /**
     * If enabled, this enabled the capturing of URL parameters for displaying
     * in transaciton traces.  This overrides the newrelic.capture_params
     * setting.
     *
     * @param boolean $enable true if enabled, false if not.
     */
    public function captureParams($enable=false) {
        if ($this->skip()) {
            return;
        }
        if ($enable) {
            newrelic_capture_params('on');
        } else {
            newrelic_capture_params(false);
        }
    }

    /**
     * Adds a cutom metric with specified name and value.
     * Note: Value to be stored is of type Double.
     *
     * @param string $metricName The name of the metric to store
     * @param double $value The value to store
     */
    public function customMetric($metricName, $value) {
        if ($this->skip()) {
            return;
        }
        newrelic_custom_metric($metricName, $value);
    }

    /**
     * Prevents output filter from attempting to insert RUM Javascript.
     */
    public function disableAutorum() {
        if ($this->skip()) {
            return;
        }
        newrelic_disable_autorum();
    }

    /**
     * Stop recording the web transaction immediately.  Useful when page is done
     * computing and is about to stream data (file download, audio, video).
     */
    public function endOfTransaction() {
        if ($this->skip()) {
            return;
        }
        newrelic_end_of_transaction();
    }

    /**
     * Despite being similar in name to newrelic_end_of_transaction above, this call
     * serves a very different purpose. newrelic_end_of_transaction simply marks the
     * end time of the transaction but takes no other action. The transaction is
     * still only sent to the daemon when the PHP engine determines that the script
     * is done executing and is shutting down. This function on the other hand,
     * causes the current transaction to end immediately, and will ship all of the
     * metrics gathered thus far to the daemon unless the ignore parameter is set to
     * true. In effect this call simulates what would happen when PHP terminates the
     * current transaction. This is most commonly used in command line scripts that
     * do some form of job queue processing. You would use this call at the end of
     * processing a single job task, and begin a new transaction (see below) when a
     * new task is pulled off the queue.
     *
     * @param boolean Normally, when you end a transaction you want the metrics that
     *                have been gathered thus far to be recorded. However, there are
     *                times when you may want to end a transaction without doing so.
     *                In this case use the second form of the function and set ignore
     *                to true.
     *
     * @since 3.0
     */
    public function endTransaction($ignore=false) {
        if ($this->skip()) {
            return;
        }
        newrelic_end_transaction($ignore);
    }

    /**
     * Returns the JavaScript to insert in your <head>.
     *
     * Default is to return the surrounding script tags.
     *
     * @param boolean $flag If true, also returns <script> tag, else no tag.
     * @return string JavaScript for the timing header, empty string if extension not loaded
     */
    public function getBrowserTimingHeader($flag=true) {
        if ($this->skip()) {
            return '';
        }
        return newrelic_get_browser_timing_header($flag);
    }

    /**
     * Returns the JavaScript to insert directly before your closing </body>
     * tag.
     *
     * Default is to return the surrounding script tags.
     *
     * @param boolean $flag If true, also returns <script> tag, else no tag.
     * @return string JavaScript for the timing footer, empty string if extension not loaded
     */
    public function getBrowserTimingFooter($flag=true) {
        if ($this->skip()) {
            return '';
        }
        return newrelic_get_browser_timing_footer($flag);
    }

    /**
     * Do not generate Apdex metrics for this transaction.  Useful if you have
     * a very short or very long transaction that can skew your apdex score.
     */
    public function ignoreApdex() {
        if ($this->skip()) {
            return;
        }
        newrelic_ignore_apdex();
    }

    /**
     * Do not generate metrics for this transaction.  Useful if you have a
     * known particularly slow transaction that you do not want skewing your
     * metrics.
     */
    public function ignoreTransaction() {
        if ($this->skip()) {
            return;
        }
        newrelic_ignore_transaction();
    }

    /**
     * Sets the name of the transaction to the specified string, useful if you
     * have your own dispatching scheme.
     *
     * Please see New Relic PHP API docs for more details.
     *
     * @param string $string Name of the transaction
     */
    public function nameTransaction($string) {
        if ($this->skip()) {
            return;
        }
        newrelic_name_transaction($string);
    }

    /**
     * Reports an error at this line of code, with complete stack trace.
     *
     * @param string $message The error message
     * @param string $exception The name of a valid PHP Exception class
     * @since 2.6 (with $exception parameter)
     */
    public function noticeError($message, $exception=null) {
        if ($this->skip()) {
            return;
        }
        if ($exception === null) {
            newrelic_notice_error($message);
        } else {
            newrelic_notice_error($message, $exception);
        }
    }

    /**
     * Reports an error at this line of code, with complete stack trace.
     * This method contains additional parameters vs. YiiNewRelic::noticeError()
     *
     * @param string $errno The error code number
     * @param string $message The error message
     * @param string $funcname The name of the function
     * @param string $lineno The line number
     * @param string $errcontext The context of this error
     */
    public function noticeErrorLong($errno, $message, $funcname, $lineno, $errcontext) {
        if ($this->skip()) {
            return;
        }
        newrelic_notice_error($errno, $message, $funcname, $lineno, $errcontext);
    }

    /**
     * Records a <a href="https://docs.newrelic.com/docs/insights/new-relic-insights/understanding-insights/new-relic-insights">New Relic Insights<a> custom event.
     * For more information, see <a href="https://docs.newrelic.com/docs/insights/new-relic-insights/adding-querying-data/inserting-custom-events-new-relic-agents#php-att">Inserting custom events with the PHP agent.</a>
     * 
     * @param string $name The event name
     * @param array $attributes Associative array of the attributes
     * @since ?.?
     */
    public function recordCustomEvent($name, $attributes) {
        if ($this->skip()) {
            return;
        }
        newrelic_record_custom_event($name, $attributes);
    }

    /**
     * Sets the name of your application in New Relic.
     * Must be set before the footer has been sent, and is best if called as
     * early as possible.
     *
     * Please see New Relic PHP API docs for more details.
     *
     * @param string $name Sets the name of the application to name. The string
     *                        uses the same format as newrelic.appname and can set
     *                        multiple application names by separating each with a
     *                        semi-colon (;). However, be aware of the restriction
     *                        on the application name ordering as described for
     *                        that setting.
     *                        The first application name is the primary name. You
     *                        can also specify up to two extra application names.
     *                        (However, the same application name can only ever be
     *                        used once as a primary name). Call this function as
     *                        early as possible. It will have no effect if called
     *                        after the JavaScript footer for page load timing
     *                        (sometimes referred to as real user monitoring or RUM)
     *                        has been sent.
     * @param string $license If you use multiple licenses, you can also specify
     *                        a license key along with the application name. An
     *                        application can appear in more than one account and
     *                        the license key controls which account you are
     *                        changing the name in. If you do not wish to change
     *                        the license and wish to use the third variant,
     *                        simply set the license key to the empty string ("").
     * @param boolean $xmit The xmit flag is new in PHP agent version 3.1. Usually,
     *                        when you change an application name, the agent simply
     *                        discards the current transaction and does not send any
     *                        of the accumulated metrics to the daemon. However, if
     *                        you want to record the metric and transaction data up
     *                        to the point at which you called this function, you
     *                        can specify a value of true for this argument to make
     *                        the agent send the transaction to the daemon. This has
     *                        a very slight performance impact as it takes a few
     *                        milliseconds for the agent to dump its data. By
     *                        default this parameter is false.
     * @since 2.7
     * @since 3.1 ($xmit)
     */
    public function setAppName($name, $license=null, $xmit=false) {
        if ($this->skip()) {
            return;
        }
        newrelic_set_appname($name, $license, $xmit);
    }

    /**
     * As of release 4.4, calling newrelic_set_user_attributes("a", "b", "c");
     * is equivalent to calling:
     * 
     * newrelic_add_custom_parameter("user", "a");
     * newrelic_add_custom_parameter("account", "b");
     * newrelic_add_custom_parameter("product", "c");
     * 
     * Previously, the three parameter strings were added to collected browser
     * traces. All three parameters are required, but may be empty strings.
     *
     * @param string $user
     * @param string $account
     * @param string $product
     * @since 4.4
     */
    public function setUserAttributes($user, $account, $product) {
        if ($this->skip()) {
            return;
        }
        newrelic_set_user_attributes($user, $account, $product);
    }

    /**
     * If you have ended a transaction before your script terminates (perhaps
     * due to it just having finished a task in a job queue manager) and you
     * want to start a new transaction, use this call. This will perform the
     * same operations that occur when the script was first started. Of the
     * two arguments, only the application name is mandatory. However, if you
     * are processing tasks for multiple accounts, you may also provide a
     * license for the associated account. The license set for this API call
     * will supersede all per-directory and global default licenses configured
     * in INI files
     * 
     * @param string $appName The application name
     * @param string $license The application license, optional
     * @since 3.0
     */
    public function startTransaction($appName, $license=null) {
        if ($this->skip()) {
            return;
        }
        newrelic_start_transaction($appName, $license);
    }

}
