<?php

use yii\db\Migration;

/**
 * Class m190808_103154_lang
 */
class m190808_103154_lang extends Migration
{
    public $tableName = '{{%language}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'url' => $this->string(255)->notNull(),
            'local' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'default' => $this->smallInteger(6)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        ], $tableOptions);

        $this->insert($this->tableName, [
            'url' => 'en',
            'local' => 'en-EN',
            'name' => 'English',
            'default' => 0,
        ]);

        $this->insert($this->tableName, [
            'url' => 'ru',
            'local' => 'ru-RU',
            'name' => 'Русский',
            'default' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190808_103154_lang cannot be reverted.\n";

        return false;
    }
}
