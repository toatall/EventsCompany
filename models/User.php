<?php

namespace app\models;

use Yii;
use app\models\userinfo\UserInfo;
use yii\helpers\ArrayHelper;

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
    /**
     * Roles
     * @var array
     */
    private $_roles = ['user'=>'user', 'moderator'=>'moderator', 'admin'=>'admin'];    
    
    /***
     * Organizations
     * @var array
     */
    private $_organizations;
    
    /**
     * @inheritdoc \yii\web\IdentityInterface
     */
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
            [['username'], 'required'],
            [['organizations'], 'safe'],           
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
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',    
            'organizations' => 'Организации',
        ];
    }
    
    
    /* ----------------------- Relations --------------------*/
    
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
    
    /* ----------------------- / Relations --------------------*/
    
    
    /* ----------------------- Events --------------------*/
    
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
        
        return true;        
    }       
    
    /* ----------------------- / Events --------------------*/
    
    
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
    
    /**
     * Return all roles
     * @property array $allRoles
     * @return array
     */
    public function getAllRoles()
    {
        return $this->_roles;
    }
    
    /**
     * @property string $organizationsList
     */
    public function getOrganizationsList()
    {
        $query = Organization::find()
            ->distinct(true)            
            ->alias('t')
            ->join('join', 'ec_user_organization u_org', 't.code=u_org.org_code')            
            ->where('u_org.username=:username', [':username'=>$this->username])
            ->all();        
        return ArrayHelper::map($query, 'code', 'full');
    }
    
    /**
     * @property organizations
     * @return array
     */
    public function getOrganizations()
    {
        if (\Yii::$app->user->can('admin'))
        {
            $query = (new \yii\db\Query())
                ->select('code')
                ->distinct(true)
                ->from('ec_organization')
                ->all();
        }
        else
        {            
            $query = (new \yii\db\Query())             
                ->select('org_code as code')
                ->distinct(true)
                ->from('ec_user_organization')
                ->where('username=:username', [':username'=>$this->username])
                ->all();
        }
        
        return ArrayHelper::map($query, 'code', 'code');
    }
    
    /**
     * @property string $organizations
     * @param string $value
     */
    public function setOrganizations($value)
    {        
        $this->saveOrganizations($value);
    }
    
    /**
     * Save organizations in table `ec_user_organizations`
     * @param array $organizations
     */
    private function saveOrganizations($organizations)
    {
        if (!is_array($organizations))
            return;
        
        // delete old organizations
        \Yii::$app->db->createCommand()
            ->delete('ec_user_organization', 'username=:username', [':username'=>$this->username])
            ->execute();        
        
        // save new organizations
        if ($this->rolename == 'admin')
            return;
        
        foreach ($organizations as $org)
        {
            \Yii::$app->db->createCommand()
                ->insert('ec_user_organization', [
                    'username' => $this->username,
                    'org_code' => $org,
                ])->execute();
        }
    }
    
    public function isAllow($org, $roles)
    {
        if ($org==null || $roles==null)
            return false;
        if (!is_array($roles))
            $roles[] = $roles;                    
        return (in_array($this->rolename, $roles) && in_array($org, $this->organizations));    
    }
    
}
