<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ec_event".
 *
 * @property int $id
 * @property string $theme
 * @property string $date1
 * @property string $date2
 * @property string $description
 * @property string $member_users
 * @property string $member_organizations
 * @property int $is_photo
 * @property int $is_video
 * @property string $photo_path
 * @property string $video_path
 * @property string $date_create
 * @property string $date_update
 * @property string $date_delete
 * @property string $username
 * @property string $log_change
 * @property string $tags
 * @property string $date_activity
 * @property string $thumbnail
 * @property string $location
 * @property string $members_other
 * @property string $user_on_photo
 * @property string $user_on_video
 *
 * @property EcUser $username0
 * @property EcFile[] $ecFiles
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['theme', 'date1', 'is_photo', 'is_video', 'date_create', 'username', 'log_change', 'date_activity', 'location'], 'required'],
            [['date1', 'date2', 'date_create', 'date_update', 'date_delete', 'date_activity'], 'safe'],
            [['description'], 'string'],
            [['is_photo', 'is_video'], 'integer'],
            [['theme', 'username'], 'string', 'max' => 250],
            [['member_users', 'member_organizations', 'log_change', 'tags', 'members_other', 'user_on_photo', 'user_on_video'], 'string', 'max' => 255],
            [['photo_path', 'video_path', 'thumbnail'], 'string', 'max' => 500],
            [['location'], 'string', 'max' => 2000],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['username' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'theme' => 'Theme',
            'date1' => 'Date1',
            'date2' => 'Date2',
            'description' => 'Description',
            'member_users' => 'Member Users',
            'member_organizations' => 'Member Organizations',
            'is_photo' => 'Is Photo',
            'is_video' => 'Is Video',
            'photo_path' => 'Photo Path',
            'video_path' => 'Video Path',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'date_delete' => 'Date Delete',
            'username' => 'Username',
            'log_change' => 'Log Change',
            'tags' => 'Tags',
            'date_activity' => 'Date Activity',
            'thumbnail' => 'Thumbnail',
            'location' => 'Location',
            'members_other' => 'Members Other',
            'user_on_photo' => 'User On Photo',
            'user_on_video' => 'User On Video',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsername0()
    {
        return $this->hasOne(User::className(), ['username' => 'username']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEcFiles()
    {
        return $this->hasMany(File::className(), ['id_event' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }
}
