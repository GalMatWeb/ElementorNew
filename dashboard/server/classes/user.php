<?php
/**
 * Created by PhpStorm.
 * User: Gal Matheys
 * Date: 15/01/2022
 * Time: 16:41
 */

class user {
    public $id;
    public $name;
    public $email;
    public $userAgent;
    public $entranceTime;
    public $visitCount;
    public $userIp;
    public $lastUpdate;
    public $isOnLine;

    public function __construct($id,$name,$email,$userAgent,$entranceTime,$visitCount = 0,$lastUpdate,$userIp,$isOnLine = 0){

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->userAgent = $userAgent;
        $this->entranceTime = $entranceTime;
        $this->visitCount = $visitCount;
        $this->userIp = $userIp;
        $this->lastUpdate = $lastUpdate;
        $this->isOnLine = $isOnLine;


    }

    public function set_userAgent($val){
        $this->userAgent = $val;
    }
    public function set_userIp($val){
        $this->userIp = $val;
    }
    public function set_visitCount(){
        $this->visitCount++;
    }
    public function set_isOnLine($val){
        $this->isOnLine = $val;
    }
    public function set_lastUpdate(){
        $this->lastUpdate = date("Y-m-d H:i");
    }
    public function set_entranceTime($val){
        $this->entranceTime = $val;
    }

}