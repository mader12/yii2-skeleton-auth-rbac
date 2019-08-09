<?php

namespace app\components;

use yii\web\UrlManager;
use app\models\Language;

class LangUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        if( isset($params['lang_id']) ){
            //Если указан идентефикатор языка, то делаем попытку найти язык в БД,
            //иначе работаем с языком по умолчанию
            $lang = Language::findOne($params['lang_id']);
            if( $lang === null ){
                $lang = Language::getDefaultLang();
            }

            unset($params['lang_id']);
        } else {
            //Если не указан параметр языка, то работаем с текущим языком
            $lang = Language::getCurrent();
        }

        $url = parent::createUrl($params);

        return $url == '/' ? '/'.$lang->url : '/'.$lang->url.$url;
    }
}