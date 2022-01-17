<?php
/**
 * Created by PhpStorm.
 * User: Gal Matheys
 * Date: 15/01/2022
 * Time: 13:48
 */
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
session_start();
require "classes/dblayer.php";


$action = isset($_GET['act']) ? $_GET['act'] : '';
$result = [];
if(!$action) {
    $result['status'] = 'fail';
    $result['message'] = 'no action delivered';
    echo json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    dblayer::init();
    switch ($action) {
        case 'login':
                $json = file_get_contents('php://input');
                $json = json_decode($json,true);
                $name = $json['name'];
                $email = $json['email'];
                $user = dblayer::findUserByNameAndEmail($name,$email);
                if(!$user) {
                    throw new Exception("Could Not LogIn");
                }
                //now update user and sebd details to client
                $user->set_isOnLine(1);
                $user->set_userAgent($_SERVER['HTTP_USER_AGENT']);
                $user->set_visitCount();
                $user->set_entranceTime(date("Y-m-d H:i:s"));
                $user->set_userIp($_SERVER['REMOTE_ADDR']);
                $user->set_lastUpdate(date("Y-m-d H:i:s"));
                dblayer::updateUser($user->id , $user);
                $result['status'] = 'ok';
                $result['userID'] = $user->id;
                $result['name'] = $user->name;
              break;
        case 'logout':
            $userID = $_POST['userID'];
            session_destroy();
            if(!$userID){
                throw new Exception("No User Id Found");
            }
            $user = dblayer::getUserByID($userID);
            $user->set_isOnLine(0);
            $user->set_userAgent($_SERVER['HTTP_USER_AGENT']);
            $user->set_visitCount();
            $user->set_entranceTime(date("Y-m-d H:i"));
            $user->set_userIp($_SERVER['REMOTE_ADDR']);
            $user->set_lastUpdate(date("Y-m-d H:i:s"));
            dblayer::updateUser($user->id , $user);
            break;
        case 'list':
            $isLoggedIn = 1;
            $userID = isset($_SERVER['HTTP_USERID']) ? $_SERVER['HTTP_USERID'] : null;
            if(!$userID) { //TODO check if is logged in
                throw new Exception("you are not logged in");
            }
            $user = dblayer::getUserByID($userID);
            $user->set_lastUpdate(date("Y-m-d H:i:s"));
            $user->set_isOnLine(1);
            dblayer::updateUser($user->id , $user);
            $sendUsers = dblayer::retrieveAllOnlineUsers($userID);
            $result['users'] = $sendUsers;
            break;
    }
}
catch (Exception $e) {
    $result['status'] = 'fail';
    $result['message'] = $e->getMessage();
}

echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);