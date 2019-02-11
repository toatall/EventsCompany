<?php

namespace app\models;

use Yii;
use app\models\userinfo\UserInfo;
use yii\bootstrap\Html;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ec_event".
 *
 * @property int $id
 * @property string $org_code
 * @property string $theme
 * @property string $date1
 * @property string $date2
 * @property string $description
 * @property array $member_users
 * @property array $member_organizations
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
 * @property array $member_others
 * @property array $user_on_photo
 * @property array $user_on_video
 * 
 * @property string $linksMemberUsers
 * @property string $linksMemberOrganizations
 * @property string $linksMemberOthers
 * @property string $linksUserOnPhoto
 * @property string $linksUserOnVideo
 * @property string $uploadFiles
 *
 * @property File[] $files
 * @property User $userProfile
 * @property string $thumbnailImageSrc
 */
class Event extends \yii\db\ActiveRecord
{
    
    const EVENT_TYPE_MEMBER_USERS = 1;
    const EVENT_TYPE_MEMBER_ORGANIZATIONS = 2;
    const EVENT_TYPE_MEMBER_OTHERS = 3;
    const EVENT_TYPE_USER_ON_PHOTO = 4;
    const EVENT_TYPE_USER_ON_VIDEO = 5;
        
    /**
     * @uses views/site/_index
     * @var integer
     */
    public static $currentYear = 0;
    
    /**
     * Uploaded thumbnail file
     * @var yii\web\UploadedFile
     */
    public $thumbnailImage;
    
    /**
     * Flag to delete thumbnail file
     * @var boolean
     */
    public $delThumbnail = false;
    
    /**
     * Uploaded attachment files
     * @var yii\web\UploadedFile[]
     */
    public $attachmentFiles;
    
    /**
     * Flag to delete attachment files
     * @var int[]
     */
    public $delAttachmentFiles;
        
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
            [['theme', 'date1', 'is_photo', 'is_video', 'date_activity', 'location', 'org_code'], 'required'],
            [['description', 'log_change'], 'string'],
            [['org_code'], 'string', 'max'=>5],
            [['date1', 'date2', 'date_activity'], 'date', 'format'=>'php:d.m.Y'],            
            [['is_photo', 'is_video'], 'integer'],
            [['delThumbnail'], 'boolean'],
            [['username'], 'string', 'max' => 250],
            /*[['member_organizations', 'tags', 
                'members_other', 'user_on_photo', 'user_on_video'], 'string', 'max' => 255],*/
            [['member_users', 'member_organizations', 'member_others', 'user_on_photo', 'user_on_video'], 'safe'],
            [['theme', 'photo_path', 'video_path', 'thumbnail'], 'string', 'max' => 500],
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
            'id' => 'ИД',
            'org_code' => 'Код организации',
            'theme' => 'Тема',
            'date_activity' => 'Дата мероприятия',
            'date1' => 'Фактическая дата начала мероприятия',
            'date2' => 'Фактическая дата окончания мероприятия',
            'description' => 'Описание',
            'member_users' => 'Участники (сотрудники)',
            'member_organizations' => 'Участники (организации)',
            'is_photo' => 'Наличие фотографий',
            'is_video' => 'Наличие видеозаписей',
            'photo_path' => 'Путь к фотоматериалам',
            'video_path' => 'Путь к видеоматериалам',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'username' => 'Логин пользователя',
            'log_change' => 'Журнал изменений',
            'tags' => 'Тэги',
            'thumbnail' => 'Миниатюра',
            'location' => 'Место проведения',
            'members_other' => 'Участники (сторонние)',
            'user_on_photo' => 'Участники на фотографии',
            'user_on_video' => 'Участники на видео',
            'thumbnailImage' => 'Миниатюра',
            'delThumbnail' => 'Удалить миниатюру',
            'attachmentFiles' => 'Приложения',
            'delAttachmentFiles' => 'Удалить приложения',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['org_code', 'code']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(User::className(), ['username' => 'username']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
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
   
    /**
     * ----------------------------------------------
     * < EVENTS >
     */
    
    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;
            
        if ($this->isNewRecord)
        {            
            $this->date_create = DateHelper::currentDateTime();
        }
        else
        {
            $this->date_update = DateHelper::currentDateTime();
            $this->date_create = DateHelper::writeDateTime($this->date_create, true);
        }
                       
        $this->date1 = DateHelper::writeDateTime($this->date1);
        $this->date2 = DateHelper::writeDateTime($this->date2);
        $this->date_activity = DateHelper::writeDateTime($this->date_activity);
        
        $this->username = UserInfo::inst()->userLogin;
        $this->log_change = LogChangeHelper::setLog($this->log_change, ($this->isNewRecord ? 'создание': 'изменение'));                     
        
        return true;
    }
    
    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    
    public function afterFind()
    {        
        parent::afterFind();
        $this->date1 = DateHelper::readDateTime($this->date1, true);
        $this->date2 = DateHelper::readDateTime($this->date2, true);
        $this->date_activity = DateHelper::readDateTime($this->date_activity, true);
        $this->date_create = DateHelper::readDateTime($this->date_create);
        $this->date_update = DateHelper::readDateTime($this->date_update);
    }
    
    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete())
            return false;
            $this->deleteThumbnail();
            $this->deleteAttachmentFiles(null, true);
        return true;
    }
    
    /**
     * < / EVENTS >
     * ---------------------------------------------
     */
    
    
    /**
     * < PROPERTY >
     */
    
    /**
     * Help function: Save members in table ec_member
     * @param integer $type
     * @param string[] $value
     */
    private function saveMembers($type, $value)
    {
        // 1. delete old members
        \Yii::$app->db->createCommand()
            ->delete('ec_member', [
                'id_event' => $this->id,
                'type_member' => $type,
            ])->execute();
        
        
        
        // 2. save new members
        if (!is_array($value))
            return;
        
        foreach ($value as $v)
        {            
            \Yii::$app->db->createCommand()
                ->insert('ec_member', [
                    'id_event' => $this->id,
                    'type_member' => $type,
                    'text' => $v,
                ])->execute();
        }
    }
    
    /**
     * Help function: get members from table ec_member
     * @param integer $type
     * @return array
     */
    private function loadMembers($type)
    {
        $query = (new  \yii\db\Query())
            ->from('ec_member')
            ->where('id_event=:id_event and type_member=:type_member', [
                ':id_event' => $this->id,
                ':type_member' => $type,
            ])
            ->all();
        return ArrayHelper::map($query, 'text', 'text');
    }
    
    /**
     * GET: member_users
     * @return array
     */
    public function getMember_users()
    {
        return $this->loadMembers(self::EVENT_TYPE_MEMBER_USERS);
    }
    
    /**
     * SET: member_users
     * @param array $value
     */
    public function setMember_users($value)
    {
        $this->saveMembers(self::EVENT_TYPE_MEMBER_USERS, $value);
    }
    
    /**
     * GET: member_organization
     */
    public function getMember_organizations()
    {
        return $this->loadMembers(self::EVENT_TYPE_MEMBER_ORGANIZATIONS);
    }
    
    /**
     * SET: member_organization
     * @param array $value
     */
    public function setMember_organizations($value)
    {
        $this->saveMembers(self::EVENT_TYPE_MEMBER_ORGANIZATIONS, $value);
    }
    
    /**
     * GET: member_others
     * @return array
     */
    public function getMember_others()
    {
        return $this->loadMembers(self::EVENT_TYPE_MEMBER_OTHERS);
    }
    
    /**
     * SET: member_others
     * @param array $value
     */
    public function setMember_others($value)
    {
        $this->saveMembers(self::EVENT_TYPE_MEMBER_OTHERS, $value);
    }
    
    /**
     * GET: user_on_photo
     * @return array
     */
    public function getUser_on_photo()
    {
        return $this->loadMembers(self::EVENT_TYPE_USER_ON_PHOTO);    
    }
    
    /**
     * SET: user_on_photo
     * @param array $value
     */
    public function setUser_on_photo($value)
    {
        $this->saveMembers(self::EVENT_TYPE_USER_ON_PHOTO, $value);
    }
    
    /**
     * GET: user_on_video
     * @return array
     */
    public function getUser_on_video()
    {
        return $this->loadMembers(self::EVENT_TYPE_USER_ON_VIDEO);
    }
    
    /**
     * SET: user_on_video
     * @param array $value
     */
    public function setUser_on_video($value)
    {
        $this->saveMembers(self::EVENT_TYPE_USER_ON_VIDEO, $value);
    }
    
    
    /**
     * Member users as links
     * @return string
     */
    public function getLinksMemberUsers()
    {
        return $this->toLinks($this->member_users);
    }
    
    /**
     * Member organizations for links on view
     * @return string
     */
    public function getLinksMemberOrganizations()
    {
        return $this->toLinks($this->member_organizations);
    }
    
    /**
     * Member others for links on view
     * @return string
     */
    public function getLinksMemberOthers()
    {
        return $this->toLinks($this->member_others);
    }
    
    /**
     * User on photo for links on view
     * @return string
     */
    public function getLinksUserOnPhoto()
    {
        return $this->toLinks($this->user_on_photo);
    }
    
    /**
     * User on video for links on view
     * @return string
     */
    public function getLinksUserOnVideo()
    {
        return $this->toLinks($this->user_on_video);
    }
    
    /**
     * Create links
     * @param string $str
     * @return string
     */
    private function toLinks($arr)
    {        
        if (!is_array($arr))
            $arr[] = $arr;
        
        $lnk = '';
        foreach ($arr as $item)
        {
            if ($lnk != '')
                $lnk .= ', ';
                $lnk .= Html::a($item, ['site/index', 'term'=>trim($item)], ['target'=>'_blank']);
        }
        return $lnk;
    }
    
    /**
     * Generate file name
     * @param string $extension
     * @param string $prefix
     * @return string
     */
    private function generateName($extension, $prefix)
    {
        return $prefix . date('dmY_His_') . rand(10, 99) . '.' . $extension;
    }
    
    /**
     * Upload thumbnail
     * @return boolean
     */
    public function uploadThumbnail()
    {
        // delete old thumbnail
        if (!$this->isNewRecord && $this->delThumbnail)
        {
            $this->deleteThumbnail();
        }
        
        // upload thumbnail
        if ($this->validate() && $this->thumbnailImage != null)
        {
            $fileUpload = 'uploads/' . $this->generateName($this->thumbnailImage->extension, 'thumbnail_');
            $this->thumbnail = '/' . $fileUpload;
            $this->thumbnailImage->saveAs($fileUpload);
            $this->thumbnailImage = null;
            return true;
        }
        $this->thumbnailImage = null;
        return false;
    }
    
    /**
     * Upload attachment files
     */
    public function uploadAttachmentFiles()
    {
        // delete selected attachments
        if ($this->delAttachmentFiles != null && is_array($this->delAttachmentFiles))
        {
            $this->deleteAttachmentFiles($this->delAttachmentFiles);
        }
       
        // upload attachments
        if ($this->attachmentFiles != null)
        {
            foreach ($this->attachmentFiles as $file)
            {
                $fileUpload = 'uploads/' . $this->generateName($file->extension, 'attachment_');
                // save to db
                if ($file->saveAs($fileUpload))
                {
                    $this->saveAttachmentDb('/' . $fileUpload);
                }
            }
        }
    }
    
    /**
     * Save attachment files in table
     * @param string $fileName
     * @return number
     */
    private function saveAttachmentDb($fileName)
    {
        return Yii::$app->db->createCommand()
            ->insert(File::tableName(), [
                'id_event' => $this->id,
                'filename' => basename($fileName),
                'filename_path' => $fileName,
                'date_create' => DateHelper::currentDateTime(),
                'username' => UserInfo::inst()->userLogin,
        ])->execute();
    }
    
    /**
     * Delete thumbnail file
     * @return boolean
     */
    public function deleteThumbnail()
    {
        $fileName = Yii::$app->basePath . '/web' . $this->thumbnail;
        if (file_exists($fileName))
        {
            if (@FileHelper::unlink($fileName))
            {
                $this->thumbnail = null;
                return true;
            }
        }
        else $this->thumbnail = null;
        return false;
    }
    
    /**
     * Delete attachments files
     * @param int $id
     * @param boolean $deleteAll
     * @return number
     */
    public function deleteAttachmentFiles($id, $deleteAll=false)
    {
        $query = (new \yii\db\Query())            
            ->from(File::tableName())
            ->where('id_main=:id_main', [':id_main'=>$this->id]);
        
        if (!$deleteAll)
        {
            $query = $query->andWhere(['in', 'id', $id]);
        }
        
        foreach ($query->all() as $file)
        {
            $fileName = Yii::$app->basePath . '/web' . $file['filename_path'];
            // delete file from disk
            if (file_exists($fileName))
                if (!@FileHelper::unlink($fileName))
                    continue;
                    
                    // delete from table
            Yii::$app->db->createCommand()
                ->delete(File::tableName(), [
                    'id'=>$file['id'],
                    'id_main'=>$this['id'],
                ])
                ->execute();
        }
    }
    
    /**
     * Return thumbnail src image
     * @return string
     */
    public function getThumbnailImageSrc()
    {
        if (is_file(Yii::$app->basePath . '/web' . $this->thumbnail))
            return $this->thumbnail;
            return '/images/no_image_available.jpeg';
    }
    
    /**
     * Attachments for download
     * @return string
     */
    public function getAttachmentFileWithUri()
    {
        $resultStr = '';
        if ($this->files == null)
            return $resultStr;
        foreach ($this->files as $file)
        {
            if ($resultStr != '')
                $resultStr .= '<br />';
                $resultStr .= Html::a($file->filename, $file->filename_path, ['target'=>'_blank']);
        }
        return $resultStr;
    }
    
    /*
    public static function listField($field, $term)
    {
        $query = (new \yii\db\Query())
            ->select("id, {$field}")
            ->from('ec_event');
        
        if ($term!=null)
            $query->where = ['like', $field, $term];    
        
        $query = $query->all();
        
        return ArrayHelper::map($query, 'id', $field);
    }
    */
    
        /*
    public function listToStr($array)
    {
        return implode(', ', $array);
    }*/
    
    /**
     * Return tags with type member users
     * @return array
     */
    public function tagsMemberUsers()
    {      
        return $this->tagsMember(self::EVENT_TYPE_MEMBER_USERS);     
    }
    
    /**
     * Return tags with type member organizations
     * @return array
     */
    public function tagsMemberOrganizations()
    {
        return $this->tagsMember(self::EVENT_TYPE_MEMBER_ORGANIZATIONS);
    }
    
    /**
     * Return tags with type user on photo
     * @return array
     */
    public function tagsUserOnPhoto()
    {
        return $this->tagsMember(self::EVENT_TYPE_USER_ON_PHOTO);
    }
    
    /**
     * Return tags with type user on video
     * @return array
     */
    public function tagsUserOnVideo()
    {
        return $this->tagsMember(self::EVENT_TYPE_USER_ON_VIDEO);
    }
    
    /**
     * Help function: return all members by tag
     * @param integer $typeMember
     * @return array
     */
    private function tagsMember($typeMember)
    {
        $query = (new \yii\db\Query())
            ->from('ec_member')
            ->where('type_member=:type_member', [':type_member'=>$typeMember])
            ->orderBy('text')
            ->all();        
        return ArrayHelper::map($query, 'text', 'text');
    }
    
    
}
