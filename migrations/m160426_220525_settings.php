<?php

use yii\db\Migration;

class m160426_220525_settings extends Migration
{
    /**
     * @var string
     */
    public $tableName = '{{%settings}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            // 32 (key length) + 32 (keyPrefix length)
            'id' => $this->string(64)->notNull() .
                ($this->db->driverName === 'sqlite' ? ' PRIMARY KEY' : ''),

            // Caching a query to the db with BLOB data type
            // https://github.com/yiisoft/yii2/issues/9899
            'data' => $this->text(),
        ], $tableOptions);

        if ($this->db->driverName !== 'sqlite') {
            $this->addPrimaryKey('settings_pk', $this->tableName, 'id');
        }
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
