<?php

use yii\db\Migration;

/**
 * Class m190219_094228_add_table_user_org
 */
class m190219_094228_add_table_user_org extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drop column access_org
        $this->dropColumn('ec_user', 'access_org');
        
        // create table ec_user_organization
        $this->createTable('ec_user_organization', [
            'username' => $this->string(250)->notNull(),
            'org_code' => $this->string(5)->notNull(),
        ]);
        // add foreign keys
        $this->addForeignKey('pk_user_organization_username', 'ec_user_organization', 'username', 'ec_user', 'username');
        $this->addForeignKey('pk_user_organization_organization', 'ec_user_organization', 'org_code', 'ec_organization', 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign keys
        $this->dropForeignKey('pk_user_organization_username', 'ec_user_organization');
        $this->dropForeignKey('pk_user_organization_organization', 'ec_user_organization');
        
        // drop table ec_user_organization
        $this->dropTable('ec_user_organization');
        
        // add column access_org
        $this->addColumn('ec_user', 'access_org', $this->string());
        
    }
   
}
