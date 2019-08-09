<?php

use yii\db\Migration;

/**
 * Class m190107_143114_table_users
 */
class m190107_143114_table_users extends Migration
{
    public $tableName = '{{%users}}';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'password' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->null(),
            'auth_key' => $this->string(255)->null(),
            'status' => $this->integer(2)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        ], $tableOptions);

        $this->insert($this->tableName, [
            'username' => 'admin',
            'email' => 'admin@seo.ru',
            'status' => 10,
            'password' => Yii::$app->security->generatePasswordHash('12345678')
        ]);

        $this->insert($this->tableName, [
            'username' => 'editor',
            'email' => 'editor@seo.ru',
            'status' => 10,
            'password' => Yii::$app->security->generatePasswordHash('12345678')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        if ($this->db->getTableSchema($this->tableName, true) !== null) {
            $this->dropTable($this->tableName);
        }

        return true;
    }

}
