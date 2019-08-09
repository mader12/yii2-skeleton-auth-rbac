<?php
namespace app\behaviors;


use yii\behaviors\TimestampBehavior;

class DatetimeBehavior extends TimestampBehavior
{

    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](https://secure.php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return date('Y-m-d H:i:s');
        }

        return parent::getValue($event);
    }

}