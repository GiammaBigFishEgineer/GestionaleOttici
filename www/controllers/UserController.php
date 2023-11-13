<?php

require_once dirname(__DIR__) . "/models/UserModel.php";
require_once dirname(__DIR__) . "/views/UserView.php";

//require_once(__ROOT__ . '/config/DB.php');

require_once(__ROOT__ . '/utils/ClassUtils.php');
class UserController
{
    public static function showLogin(){
        $view = new UserView();
        $view->renderLogin();
    }

    public static function login(){
        $data = $_POST;

        $user = new UserModel($data);
        $user::login($user->password, $user->email);
    }
}

?>