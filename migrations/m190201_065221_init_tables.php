<?php

use yii\db\Migration;

/**
 * Class m190201_065221_init_tables
 */
class m190201_065221_init_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // table organization
        $this->createTable('ec_organization', [
            'code' => $this->string(5)->notNull(),
            'name' => $this->string(150)->notNull(),
            'description' => $this->text(),
        ]);
        $this->addPrimaryKey('pk_organization', 'ec_organization', 'code');
        
        
        // table user
        $this->createTable('ec_user', [
            'username' => $this->string(250)->notNull(),
            'userfio' => $this->string(500)->null(),
            'rolename' => $this->string(30)->defaultValue('user'),
            'access_org' => $this->string(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->null(),
        ]);        
        $this->addPrimaryKey('pk_username', 'ec_user', 'username');       
        
        // table event
        $this->createTable('ec_event', [
            'id' => $this->primaryKey(),
            'org_code' => $this->string(5)->notNull(),
            'theme' => $this->string(500)->notNull(),
            'date1' => $this->date()->notNull(),
            'date2' => $this->date()->null(),
            'description' => $this->text()->null(),
            //'member_users' => $this->string()->null(),
            //'member_organizations' => $this->string()->null(),
            'is_photo' => $this->boolean()->notNull(),
            'is_video' => $this->boolean()->notNull(),
            'photo_path' => $this->string(500)->null(),
            'video_path' => $this->string(500)->null(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->null(),
            'date_delete' => $this->dateTime()->null(),
            'username' => $this->string(250)->notNull(),
            'log_change' => $this->text()->notNull(),
            'tags' => $this->string()->null(),
            'date_activity' => $this->date()->notNull(),
            'thumbnail' => $this->string(500)->null(),
            'location' => $this->string(2000)->notNull(),
            //'members_other' => $this->string()->null(),
            //'user_on_photo' => $this->string()->null(),
            //'user_on_video' => $this->string()->null(),
        ]);
        $this->addForeignKey('fk_event_user_username', 'ec_event', 'username', 'ec_user', 'username');
        $this->addForeignKey('fk_event_organization', 'ec_event', 'org_code', 'ec_organization', 'code');
        
        // table members
        $this->createTable('ec_member', [
            'id' => $this->primaryKey(),
            'id_event' => $this->integer()->notNull(),
            'type_member' => $this->smallInteger()->notNull(),
            'text' => $this->string(1000)->notNull(),            
        ]);
        $this->addForeignKey('fk_member_event_idevent', 'ec_member', 'id_event', 'ec_event', 'id');
        
        // table file
        $this->createTable('ec_file', [
            'id' => $this->primaryKey(),
            'id_event' => $this->integer()->notNull(),
            'filename' => $this->string(250)->notNull(),
            'filename_path' => $this->string(500)->notNull(),
            'date_create' => $this->dateTime()->notNull(),
            'username' => $this->string(250)->notNull(),
        ]);
        $this->addForeignKey('fk_file_event_idevent', 'ec_file', 'id_event', 'ec_event', 'id');
        $this->addForeignKey('fk_file_user_username', 'ec_file', 'username', 'ec_user', 'username');
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {        
        // drop table file
        $this->dropForeignKey('fk_file_user_username', 'ec_file');
        $this->dropForeignKey('fk_file_event_idevent', 'ec_file');
        $this->dropTable('ec_file');
        
        // drop table member
        $this->dropForeignKey('fk_member_event_idevent', 'ec_member');
        $this->dropTable('ec_member');
        
        // drop table event
        $this->dropForeignKey('fk_event_user_username', 'ec_event');
        $this->dropForeignKey('fk_event_organization', 'ec_event');
        $this->dropTable('ec_event');
        
        // drop table user
        $this->dropTable('ec_user');
        
        // drop table organization
        $this->dropTable('ec_organization');
    }
    
    
    

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190201_065221_init_tables cannot be reverted.\n";

        return false;
    }
    */
}
