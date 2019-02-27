<?php

use yii\db\Migration;

/**
 * Class m190227_084547_add_table_query
 */
class m190227_084547_add_table_query extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ec_query', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),
            'text' => $this->string(5000)->notNull(),
            'text_right' => $this->string(5000)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        
        $this->createTable('ec_query_log', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),
            'text' => $this->string(5000),
            'str_user_agent' => $this->string(500),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('fk_query_log_user_username', 'ec_query_log', 'username', 'ec_user', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_query_log_user_username', 'ec_query_log');
        
        $this->dropTable('ec_query_log');
        $this->dropTable('ec_query');
    }
    
}
