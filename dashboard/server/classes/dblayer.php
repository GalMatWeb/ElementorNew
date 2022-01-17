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
        self::loadAllUsers();

    }



    public static function loadAllUsers(){
        self::$catchUsers = [];
        $preArray = json_decode(file_get_contents(self::$usersFile),true);
        foreach ($preArray as $user) {
            self::$catchUsers[$user['id']] = new user($user['id'],$user['name'],$user['email'],$user['userAgent'],$user['entranceTime'],$user['visitCount'],
                $user['lastUpdate'],$user['userIp'],$user['isOnLine']);
        }
        $updateFile = false;
        foreach (self::$catchUsers as $catchUser) {
            if(strtotime($catchUser->lastUpdate) > strtotime("+3 minute")) {
                self::$catchUsers[$catchUser->id]->isOnLine = 0;
                $updateFile = true;
            }
        }
        if($updateFile === true) {
            self::updateFile(self::$usersFile,self::$catchUsers);
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
        self::loadAllUsers();
        if(isset($userData->userAgent))  self::$catchUsers[$userID]->set_userAgent($userData->userAgent);

        if(isset($userData->userIp))     self::$catchUsers[$userID]->set_userIp($userData->userIp);

        if(isset($userData->visitCount)) self::$catchUsers[$userID]->set_visitCount();

        if(isset($userData->isOnLine))   self::$catchUsers[$userID]->set_isOnLine($userData->isOnLine);

        if(isset($userData->lastupdate)) self::$catchUsers[$userID]->set_lastUpdate($userData->lastupdate);

        if(isset($userData->entranceTime)) self::$catchUsers[$userID]->set_entranceTime($userData->entranceTime);

        self::updateFile(self::$usersFile,self::$catchUsers);

    }

    public static function getUserByID($userID){
        if(!$userID) return [];
        self::loadAllUsers();
        return self::$catchUsers[$userID] ?? [];
    }


    public static function retrieveAllUser(){
        self::loadAllUsers();
        return self::$catchUsers;
    }

    public static function retrieveAllOnlineUsers($userID = 0){
        self::loadAllUsers();
        self::$catchOnlineUsers = [];
        foreach (self::$catchUsers as $user) {
            if($user->isOnLine == 1 && $user->id != $userID) {
                self::$catchOnlineUsers[$user->id] = $user;
            }
        }
        return self::$catchOnlineUsers;
    }

    public static function findUserByNameAndEmail($name,$email){
        if(!$name || !$email) return [];
        self::loadAllUsers();
        foreach (self::$catchUsers as $user) {
            if(strtolower($user->name) == strtolower($name) && strtolower($user->email) == strtolower($email)) {
                return $user;
            }
        }
        return [];
    }




}