<?php

namespace app\models;

use Yii;
use app\models\userinfo\UserInfo;
use yii\bootstrap\Html;
use yii\helpers\FileHelper;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\base\ModelEvent;
use app\models\DateBehavior;

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
            [['theme', 'date1', 'is_photo', 'is_video', 'date_activity', 'location'], 'required'],
            [['description'], 'string'],
            [['date1', 'date2', 'date_activity'], 'date', 'format'=>'php:d.m.Y'],            
            [['is_photo', 'is_video'], 'integer'],
            [['delThumbnail'], 'boolean'],
            [['username'], 'string', 'max' => 250],
            [['member_users', 'member_organizations', 'log_change', 'tags', 
                'members_other', 'user_on_photo', 'user_on_video'], 'string', 'max' => 255],
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
            'user_on_video' => 'Учатники на видео',
            'thumbnailImage' => 'Миниатюра',
            'delThumbnail' => 'Удалить миниатюру',
            'attachmentFiles' => 'Приложения',
            'delAttachmentFiles' => 'Удалить приложения',
        ];
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
            $this->date_create = DateHelper::formatDateTimeDb($this->date_create, true);
        }
                       
        $this->date1 = DateHelper::formatDateTimeDb($this->date1);
        $this->date2 = DateHelper::formatDateTimeDb($this->date2);
        $this->date_activity = DateHelper::formatDateTimeDb($this->date_activity);
        
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
        return $this->toLinks($this->members_other);
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
    private function toLinks($str)
    {
        $array = preg_split('/,/', $str);
        $lnk = '';
        foreach ($array as $item)
        {
            if ($lnk != '')
                $lnk .= ', ';
                $lnk .= Html::a($item, ['site/index', 'term'=>$item], ['target'=>'_blank']);
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
        if ($this->validate() && $this->attachmentFiles != null)
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
            'id_main' => $this->id,
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
    
    
}
