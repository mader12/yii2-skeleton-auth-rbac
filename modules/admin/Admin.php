<?php

namespace app\modules\admin;

/**
 * module module definition class
 */
class Admin extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $layout = '@admin/views/layouts/main';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
