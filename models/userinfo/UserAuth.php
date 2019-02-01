<?php

namespace app\models\userinfo;

use app\models\ldap\LDAPInfo;
use yii\base\Exception;

/**
 * Класс для получения информации о пользователе
 *
 * Информация о пользователе берется из переменной $_SERVER['AUTH_USER']
 * В связи с чем получение информации возможно только если включена Windows аутентефикация
 *
 * @author toatall
 * @version 02.12.2016
 * @since 1.0
 * @property bool userAuth
 * @property string userLogin - логин пользователя
 * @property string userName - имя (ФИО) пользователя
 * @property string orgCode - код организации (от имени пользователя)
 * @property string clientIP - ip адрес
 * @property string clientHost - имя станции клиента
 * @property string ADLogin - логин пользователя (с доменом) (REGIONS\username)
 * @property string	ADDepartment - название подразделения
 * @property string	ADPost - должность
 * @property string ADCompany - название организации
 * @property string	ADTelephone - телефонный номер
 * @property string ADPrincipalName - логин пользователя с доменом (username@regions.tax.nalog.ru)
 * @property string ADMemberOf - группы, в которые входит пользователь
 *
 * @uses UserInfo::inst()
 */
class UserAuth
{
    
    const SESSION_PREFIX = 'ec_';
    
    /**
     * Свойства
     * @var array
     */
    private $_fields = array(
        'userAuth' => false,
        'userLogin' => '',
        'userName' => '',
        'orgCode' => '',
        'clientIP' => '',
        'clientHost' => '',
        'ADLogin' => '',
        'ADDepartment' => '',
        'ADPost' => '',
        'ADCompany' => '',
        'ADTelephone' => '',
        'ADPrincipalName' => '',
        'ADMemberOf' => '',
    );
    
    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->run();
    }
    
    /**
     * Магический метод для получения значений свойств
     * @param string $name
     * @return mixed|string
     * @magic
     */
    public function __get($name)
    {
        if (isset($this->_fields[$name]))
            return $this->_fields[$name];
            return '';
    }
    
    /**
     * Магический метод для присвоения значений свойствам
     * @param string $name
     * @param string $value
     * @magic
     */
    public function __set($name, $value)
    {
        if (isset($this->_fields[$name]))
        {
            $this->_fields[$name] = $value;
        }
    }
    
    /**
     * Получение информации о пользователе из сессии $_SESSION
     * @uses $this->loadSession()
     */
    public function run()
    {
        // загрузить данные из сесии
        if (session_id() == null)
            session_start();
            return $this->_fields['userAuth'] = $this->loadSession();
    }
    
    /**
     * Загрузка сессии
     * @return boolean
     * @see $this->run()
     * @uses $this->getUserInfo()
     */
    private function loadSession()
    {
        if (isset($_SESSION[self::SESSION_PREFIX . 'userLogin']))
        {
            foreach ($this->_fields as $field=>$value)
            {
                if (isset($_SESSION[self::SESSION_PREFIX . $field]))
                    $this->_fields[$field] = $_SESSION[self::SESSION_PREFIX . $field];
            }
            return true;
        }
        else
        {
            return $this->getUserInfo();
        }
    }
    
    /**
     * Сохранение сессии
     * @see $this->getUserInfo()
     */
    private function saveSession()
    {
        foreach ($this->_fields as $field=>$value)
        {
            $_SESSION[self::SESSION_PREFIX . $field] = $value;//$this->_fields[$field];
        }
    }
    
    /**
     * Получение информации из ActiveDirectory
     * Результат сохраняется в сессию $_SESSION
     * @uses $this->getADInformation()
     * @uses $this->getRemoteAddress()
     * @return boolean
     */
    private function getUserInfo()
    {
        if (!isset($_SERVER['AUTH_USER']) || empty($_SERVER['AUTH_USER']))
            return $this->_fields['userAuth'] = false;
            
            $authUser = $_SERVER['AUTH_USER'];
            $authUser = explode('\\', $authUser);
            if (count($authUser) < 2) return false;
            $this->userLogin = $authUser[1];
            $this->ADLogin = $_SERVER['AUTH_USER'];
            
            $this->getADInformation();
            $this->getRemoteAddress();
            
            $this->saveSession();
            
            return $this->_fields['userAuth'] = true;
    }
    
    /**
     * Получение информации о IP и хосте пользователя
     * @see $this->getUserInfo()
     */
    private function getRemoteAddress()
    {
        $this->clientIP = $_SERVER['REMOTE_ADDR'];
        $this->clientHost = gethostbyaddr($this->clientIP);
    }
    
    /**
     * Поиск пользователя в ActiveDirectory
     * и сохранение информации в $this->_fields[]
     * @see LDAPInfo
     * @see $this->getUserInfo()
     * @uses $this->getOrganization()
     * @uses $this->convertMembers()
     * @return boolean
     */
    private function getADInformation()
    {
        try
        {
            $ldapInfo = new LDAPInfo();
            if (!$ldapInfo->getInfoAD($this->userLogin))
                return false;
                
                $this->userName = ($ldapInfo->cn!=null ? $ldapInfo->cn : $ldapInfo->displayname);
                $this->orgCode = $this->getOrganization();
                $this->ADDepartment = $ldapInfo->department;
                $this->ADPost = $ldapInfo->title;
                $this->ADCompany = $ldapInfo->company;
                $this->ADTelephone = $ldapInfo->telephonenumber;
                $this->ADPrincipalName = $ldapInfo->userprincipalname;
                $this->ADMemberOf = $this->convertMembers($ldapInfo->memberof);
                return true;
        }
        catch (Exception $ex)
        {
            return false;
        }
    }
    
    /**
     * Получение организации пользователя из его логина
     * (первые 4 символа)
     * @see $this->getADInformation()
     * @return string|NULL
     */
    private function getOrganization()
    {
        if ((strlen($this->userLogin) >= 4))
        {
            $org = substr($this->userLogin, 0, 4);
            if (is_numeric($org))
                return $org;
        }
        return null;
    }
    
    /**
     * Преобразование наименование групп
     * обрезание DN пути
     * @param array $ADMembers
     * @see $this->getADInformation()
     * @return array
     */
    private function convertMembers($ADMembers)
    {
        if (!is_array($ADMembers) || count($ADMembers)==0)
            return array();
            
            $groups = array();
            for ($i=0; $i<$ADMembers['count']; $i++)
            {
                $matches = array();
                if (preg_match('/(?<=CN=)(.*?)(?=,OU)/', $ADMembers[$i], $matches))
                {
                    $groups[] = $matches[0];
                }
            }
            return $groups;
    }
    
    public function organizationFromLogin()
    {
        return $this->getOrganization();
    }
    
    
}