<?php


require 'restful_api.php';
require 'sqlDAO.php';
require 'firebaseDAO.php';

class api extends restful_api {
    protected $mySQL;
    protected $firebase;
    function __construct(){
        $this->mySQL = new sqlDAO();
        $this->firebase = new firebaseDAO();
        parent::__construct();
    }

    function getConnectSQL(){
        if ($this->method == 'GET'){
            $data = $this->mySQL->connectSQL();
            $this->response(200, $data);
        }
    }

    function getAllSQL()
    {
        if($this->method == 'GET')
        {
            $data = $this->mySQL->getALL();
            $this->response(200,$data);
        }
    }

    function getRealTime()
    {
        if ($this->method == 'GET'){
            $data = $this->firebase->getValueRealTime();
            $this->response(200, $data);
        }
    }

    function getNow()
    {
        if ($this->method == 'GET'){
            $data = $this->firebase->getValueFireStore();
            $this->response(200, $data);
        }
    }

    function createAccount()
    {
        if ($this->method == 'GET'){
            $user = isset($_GET['user']) ? $_GET['user'] : die();
            $pass = isset($_GET['pass']) ? $_GET['pass'] : die();
            $name = isset($_GET['name']) ? $_GET['name'] : die();
            $gender = isset($_GET['gender']) ? $_GET['gender'] : die();
            $sensor = isset($_GET['sensor']) ? $_GET['sensor'] : die();
            $status = isset($_GET['status']) ? $_GET['status'] : die();
            $data = array('status'=>true,'data'=>
                array('result'=>
                    array(
                        'SQL'=> $this->mySQL->createAccount($user,$pass,$name,$gender,$sensor,$status),
                        'Firebase'=>$this->firebase->createToken($user))));
            $this->response(200, $data);
        }
    }
    function setValueChay()
    {
        if($this->method == 'GET')
        {
            $bool = isset($_GET['bool']) ? $_GET['bool'] : die();
            $user = isset($_GET['user']) ? $_GET['user'] : die();
            $token = isset($_GET['token']) ? $_GET['token'] : die();
            $this->response(200, $this->firebase->setValueStatusPhatHienChay($bool,$user,$token));
        }
    }

    function setValueTrom()
    {
        if($this->method == 'GET')
        {
            $bool = isset($_GET['bool']) ? $_GET['bool'] : die();
            $user = isset($_GET['user']) ? $_GET['user'] : die();
            $token = isset($_GET['token']) ? $_GET['token'] : die();
            $this->response(200, $this->firebase->setValueStatusPhatHienDotNhap($bool,$user,$token));
        }
    }

    function loginAccount()
    {
        if($this->method == 'GET')
        {
//            $bool = isset($_GET['bool']) ? $_GET['bool'] : die();
            $user = isset($_GET['user']) ? $_GET['user'] : die();
            $pass = isset($_GET['pass']) ? $_GET['pass'] : die();
            $this->response(200, $this->firebase->loginAccount($user,$this->mySQL->loginAccount($user,$pass)));
        }
    }

    function changePassAccount()
    {
        if($this->method == 'GET')
        {
            $user = isset($_GET['user']) ? $_GET['user'] : die();
            $oldpass = isset($_GET['oldpass']) ? $_GET['oldpass'] : die();
            $newpass = isset($_GET['newpass']) ? $_GET['newpass'] : die();
            $this->response(200, $this->mySQL->changePass($user,$oldpass,$newpass));
        }
    }
}
new api();