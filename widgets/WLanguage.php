<?php
namespace app\widgets;

use app\models\Language;

class WLanguage extends \yii\bootstrap\Widget
{
    public function init(){}

    public function run() {
        return $this->render('language/view', [
            'current' => Language::getCurrent(),
            'langs' => Language::find()->where('id != :current_id', [':current_id' => Language::getCurrent()->id])->all(),
        ]);
    }
}