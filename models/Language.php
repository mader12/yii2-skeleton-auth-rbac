<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "language".
 *
 * @property int $id
 * @property string $url
 * @property string $local
 * @property string $name
 * @property int $default
 * @property string $created_at
 * @property string $updated_at
 */
class Language extends \yii\db\ActiveRecord
{
    //Переменная, для хранения текущего объекта языка
    static $current = null;

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'app\behaviors\DatetimeBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'local', 'name'], 'required'],
            [['default'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['url', 'local', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'local' => 'Local',
            'name' => 'Name',
            'default' => 'Default',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

//Получение текущего объекта языка
    static function getCurrent()
    {
        if (self::$current === null) {
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

//Установка текущего объекта языка и локаль пользователя
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current->local;
    }

//Получения объекта языка по умолчанию
    static function getDefaultLang()
    {
        return Language::find()->where('`default` = :default', [':default' => 1])->one();
    }

//Получения объекта языка по буквенному идентификатору
    static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $language = Language::find()->where('url = :url', [':url' => $url])->one();
            if ($language === null) {
                return null;
            } else {
                return $language;
            }
        }
    }
}
