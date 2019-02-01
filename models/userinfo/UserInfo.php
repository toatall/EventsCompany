<?php
namespace app\models\userinfo;


/**
 * Получение информации о пользователе
 * из глобального массива $_SERVER['AUTH_USER'] и ActiveDirectory
 * и сохранение в сесии
 *
 * Предназначено под web-сервер IIS с включением
 * Windows-аутентификации
 *
 * @author alexeevich
 * @static
 */
class UserInfo
{
    
    /**
     * Экземпляр класса UserAuth
     * @var UserAuth
     * @static
     */
    private static $instance = null;
    
    /**
     * Возвращает единственный экземпляр UserAuth
     * @static
     * @return UserAuth
     * @uses CommentController::actionForm()
     * @uses LikeController::loadCountLike()
     * @uses InterviewController::actionLike()
     * @uses ServiceController::actionUser()
     * @uses SiteController::actionContact()
     * @uses WebUser::saveLogOpertaion()
     * @uses RatingData::beforeSave()
     * @uses RatingMain::beforeSave()
     */
    public static function inst()
    {
        if (self::$instance === null)
            self::$instance = new UserAuth();
            return self::$instance;
    }
    
}