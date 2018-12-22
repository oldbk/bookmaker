<?php
namespace common\extensions\html;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 02.11.2014
 * Time: 20:22
 */

use CHtml;
class MHtml extends CHtml
{
    public static function activeCheckBox($model,$attribute,$htmlOptions=array())
    {
        self::resolveNameID($model,$attribute,$htmlOptions);
        if(!isset($htmlOptions['value']))
            $htmlOptions['value']=1;
        if(!isset($htmlOptions['checked']) && self::resolveValue($model,$attribute)==$htmlOptions['value'])
            $htmlOptions['checked']='checked';
        self::clientChange('click',$htmlOptions);

        if(array_key_exists('uncheckValue',$htmlOptions))
        {
            $uncheck=$htmlOptions['uncheckValue'];
            unset($htmlOptions['uncheckValue']);
        }
        else
            $uncheck='0';

        $hiddenOptions = [];
        if(isset($htmlOptions['hiddenOptions'])) {
            $hiddenOptions = $htmlOptions['hiddenOptions'];
            unset($htmlOptions['hiddenOptions']);
        }

        $hiddenOptions['id'] = isset($htmlOptions['id']) ? self::ID_PREFIX.$htmlOptions['id'] : false;
        $hidden=$uncheck!==null ? self::hiddenField($htmlOptions['name'],$uncheck,$hiddenOptions) : '';

        return $hidden . self::activeInputField('checkbox',$model,$attribute,$htmlOptions);
    }

    public static function checkBox($name,$checked=false,$htmlOptions=array())
    {
        if($checked)
            $htmlOptions['checked']='checked';
        else
            unset($htmlOptions['checked']);
        $value=isset($htmlOptions['value']) ? $htmlOptions['value'] : 1;
        self::clientChange('click',$htmlOptions);

        if(array_key_exists('uncheckValue',$htmlOptions))
        {
            $uncheck=$htmlOptions['uncheckValue'];
            unset($htmlOptions['uncheckValue']);
        }
        else
            $uncheck='0';

        $hiddenOptions = [];
        if(isset($htmlOptions['hiddenOptions'])) {
            $hiddenOptions = $htmlOptions['hiddenOptions'];
            unset($htmlOptions['hiddenOptions']);
        }

        $hiddenOptions['id'] = isset($htmlOptions['id']) ? self::ID_PREFIX.$htmlOptions['id'] : false;
        $hidden=$uncheck!==null ? self::hiddenField($name,$uncheck,$hiddenOptions) : '';

        // add a hidden field so that if the check box is not checked, it still submits a value
        return $hidden . self::inputField('checkbox',$name,$value,$htmlOptions);
    }
} 