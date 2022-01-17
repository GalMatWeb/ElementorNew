<?php
/**
 * Created by PhpStorm.
 * User: Gal Matheys
 * Date: 15/01/2022
 * Time: 15:31
 */
require "user.php";

class dblayer {

    public static $catchUsers = [];
    public static $catchOnlineUsers = [];
    public static $usersFile;
    public static $DATA_DIR = "data/";

    public static function init(){
        self::$usersFile = self::$DATA_DIR . "users.json";
    }

    public static function loadAllUsers(){
        self::$catchUsers = [];
        $preArray = json_decode(file_get_contents(self::$usersFile),true);
        foreach ($preArray as $user) {
            self::$catchUsers[$user['id']] = new user($user['id'],$user['name'],$user['email'],$user['userAgent'],$user['entranceTime'],$user['visitCount'],$user['userIp'],$user['lastUpdate'],$user['isOnLine']);
            if($user['isonline'] == 1) {
                self::$catchOnlineUsers[$user['id']] = $user;
            }
        }
    }

    public static function updateFile($fileToUpdate , $jsonData){

        $handle = fopen($fileToUpdate,"r+");
        if(flock($handle, LOCK_EX)) {
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($jsonData));    //Write the new Hit Count
            flock($handle, LOCK_UN);    //Unlock File
        } else {
            throw new Exception("Could not lock file");
        }
        fclose($handle);
    }

    public static function updateUser($userID, $userData){
        if(!$userID) return false;
        if(!$userData) return false;
        if(!self::$catchUsers) self::loadAllUsers();
        if(isset($userData->useragaent))
            self::$catchUsers[$userID]->set_userAgent($userData['useragaent']);
        if(isset($userData->userip))
            self::$catchUsers[$userID]->set_userIp($userData['userip']);
        if(isset($userData->visitcount))
            self::$catchUsers[$userID]->set_visitCount();
        if(isset($userData->isonline))
            self::$catchUsers[$userID]->set_isOnLine();
        if(isset($userData->lastupdate))
            self::$catchUsers[$userID]->set_lastUpdate($userData['lastupdate']);

        self::updateFile(self::$usersFile,self::$catchUsers);

    }

    public static function removeUserFromOnlineList($userID){
        if(!$userID) return false;
        if(!self::$catchOnlineUsers) self::loadAllUsers();
        unset(self::$catchOnlineUsers[$userID]);
        self::updateFile(self::$onlineUsersFile,self::$catchOnlineUsers);
    }

    public static function retrieveUser($userID){
        if(!$userID) return [];
        if(!self::$catchUsers) self::loadAllUsers();
        return (!self::$catchUsers[$userID]) ? [] : self::$catchUsers[$userID];
    }

    public static function retrieveAllUser(){
        if(!self::$catchUsers) self::loadAllUsers();
        return self::$catchUsers;
    }

    public static function retrieveAllOnlineUsers(){
        if(!self::$catchOnlineUsers) self::loadAllUsers();
        return self::$catchOnlineUsers;
    }

    public static function findUserByNameAndEmail($name,$email){
        if(!$name || !$email) return [];
        if(!self::$catchUsers) self::loadAllUsers();
        foreach (self::$catchUsers as $user) {
            if(strtolower($user->name) == strtolower($name) && strtolower($user->email) == strtolower($email)) {
                return $user;
            }
        }
        return [];
    }

    public static function addOnlineUser($userID){
        if(!$userID) return [];
        if(!self::$catchUsers) self::loadAllUsers();
        if(!self::$catchOnlineUsers[$userID]) {
            //TODO improve for error handling
            return false;
        }
        //self::$catchOnlineUsers[$userID]['useragaent'] =
    }


}