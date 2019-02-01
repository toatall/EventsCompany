<?php
namespace app\models\ldap;

use yii\base\Exception;

/**
 * Работа с Microsoft ActiveDirectory
 * @author alexeevich
 */
class LDAPInfo
{
    /**
     * Настройки для подключения к ActiveDirectory
     * @var array
     */
    private $config;
    
    /**
     * Результаты поиска
     * @var array
     */
    private $_resultSearch = array();
    
    /**
     * При возникновении ошибки, сюда запивается текст
     * @var string
     */
    private $_error = null;
    
    /**
     * Коструктор
     */
    public function __construct()
    {
        $this->config = require(__DIR__ . '/../../config/paramsAD.php');
    }
    
    /**
     * @magic
     * @param string $name
     * @return mixed|NULL
     */
    public function __get($name)
    {
        if (isset($this->_resultSearch[$name][0]))
        {
            if (isset($this->_resultSearch[$name]['count']) && $this->_resultSearch[$name]['count']>1)
            {
                return $this->_resultSearch[$name];
            }
            return $this->_resultSearch[$name][0];
        }
        return null;
    }
    
    
    /**
     * Получение информации о пользователе из AcriveDirectory
     * @param string $username логин пользователя
     * @throws Exception
     * @return array|false
     * @uses LoginGuest::getADInformation()
     */
    public function getInfoAD($username)
    {
        try
        {
            if (!$ldap_connect = @ldap_connect($this->config['ldapServer']))
                throw new Exception('Не удалось подключиться к серверу AD!');
                
                @ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, 3);
                
                if ($ldap_connect)
                {
                    if (@ldap_bind($ldap_connect, $this->config['bindLogin'], $this->config['bindPassword'])
                        && @ldap_bind($ldap_connect, $this->config['bindLogin'], $this->config['bindPassword']))
                    {
                        if ($ldap_search = @ldap_search(array($ldap_connect, $ldap_connect), $this->config['baseDN'],
                            '(sAMAccountName='.$username.')'))
                        {
                            foreach ($ldap_search as $search)
                            {
                                $res = @ldap_get_entries($ldap_connect, $search);
                                if ($res['count']>0)
                                    break;
                            }
                            
                            if (isset($res['count']) && $res['count']>0)
                            {
                                $this->_resultSearch = (is_array($res) && count($res) > 1) ? $res[0] : array();
                                return (count($this->_resultSearch)>0 ? true : false);
                            }
                            else
                            {
                                throw new Exception('Пользователь <strong>' . $username . '</strong> не найден!');
                            }
                        }
                    }
                }
                throw new Exception('AD error text: #' . ldap_errno($ldap_connect) . ': ' . ldap_error($ldap_connect));
        }
        catch (Exception $ex)
        {
            $this->_error = $ex->getMessage();
        }
        return false;
    }
    
    /**
     * Получение информации о последней ошибке
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }
    
    
}