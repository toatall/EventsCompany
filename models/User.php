<?php

namespace app\models;

use Yii;
use app\models\userinfo\UserInfo;

/**
 * This is the model class for table "ec_user".
 *
 * @property string $username
 * @property string $userfio
 * @property string $rolename
 * @property string $access_org
 * @property string $date_create
 * @property string $date_update
 *
 * @property Event[] $events
 * @property File[] $files
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    
    public $password;
    public $authKey;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ec_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'date_create'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['access_org'], 'string'],
            [['username'], 'string', 'max' => 250],
            [['userfio'], 'string', 'max' => 500],
            [['rolename'], 'string', 'max' => 30],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'userfio' => 'ФИО',
            'rolename' => 'Роль',
            'access_org' => 'Доступные организации',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['username' => 'username']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['username' => 'username']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
    
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::find()->where('username=:username', [':username'=>$id])->one();
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /*foreach (self::$users as $user) {
         if ($user['accessToken'] === $token) {
         return new static($user);
         }
         }*/
        return null;
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findByCondition('username=:username', [':username'=>$username])->one();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->username;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }
    
    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    
    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
        {
            return false;
        }
        
        return true;        
    }
    
    /**
     * Login user
     * @return boolean
     */
    public static function login()
    {
        $userInfo = UserInfo::inst();
        
        $model = self::find()->where('username=:username', [':username'=>$userInfo->userLogin])->one(); 
        if ($model === null)
        {            
            $model = new self();
            $model->username = $userInfo->userLogin;
            $model->userfio = $userInfo->userName;
            $model->rolename = 'user';
            $model->date_create = DateHelper::currentDateTime();
            //$model->validate();
            //print_r($model->getErrors());exit;
            
            if (!$model->save())
                return false;
        }
        
        Yii::$app->user->login($model);
        
        return $model != null;
    }
    
    /**
     * Current user id
     * @return string
     */
    public function getRole()
    {
        return $this->rolename;
    }
}
