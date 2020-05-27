<?php
class sqlDAO {
    protected $server_name = "den1.mssql8.gear.host";
    protected $database_name = "thcntt03test";

    public function connectSQL(){
        try
        {
            $conn = new PDO("sqlsrv:Server=$this->server_name;Database=$this->database_name;ConnectionPooling=0", "thcntt03test", "Ih22wbpu-9I-");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $data = array('status'=>true,'data'=>array('result'=>'Connect sql success'));
            return $data;
        }
        catch(Exception $e)
        {
            $data = array('status'=>false,'data'=>array('result'=>$e->getMessage()));
            return $data;
        }
    }

    public function getALL()
    {
        try
        {
            $conn = new PDO("sqlsrv:Server=$this->server_name;Database=$this->database_name;ConnectionPooling=0", "thcntt03test", "Ih22wbpu-9I-");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tsql = "SELECT * FROM [thcntt03test].[dbo].[user_info]";
//            $getResults = $conn->prepare($tsql);
//            $getResults->execute();
//            $results = $getResults->fetchAll(PDO::FETCH_BOTH);
            $results = $conn->query($tsql);
            $data = array();
            foreach($results as $row)
            {
                $data[] = array('user'=>$row['user'],'name'=>$row['name'],'gender'=>$row['gender'],'sensor'=>'sensor','status'=>$row['Status']);
            }
            return array('status'=>true,'data'=>array('result'=>$data));
        }
        catch(Exception $e)
        {
            return array('status'=>true,'data'=>array('result'=>$e->getMessage()));
        }
    }

    public function createAccount($user,$pass,$name,$gender,$sensor,$status)
    {
        try
        {
            $conn = new PDO("sqlsrv:Server=$this->server_name;Database=$this->database_name;ConnectionPooling=0", "thcntt03test", "Ih22wbpu-9I-");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tsql = "INSERT INTO user_info ([user_info].[user], [user_info].[pass], [user_info].[name], [user_info].[gender], [user_info].[sensor], [user_info].[Status]) VALUES ('$user','$pass','$name','$gender','$sensor','$status')";
//            $getResults = $conn->prepare($tsql);
//            $getResults->execute();
//            $results = $getResults->fetchAll(PDO::FETCH_BOTH);
            $conn->exec($tsql);
            return 'Create Account Success';
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    public function loginAccount($user,$pass)
    {
        try
        {
            $conn = new PDO("sqlsrv:Server=$this->server_name;Database=$this->database_name;ConnectionPooling=0", "thcntt03test", "Ih22wbpu-9I-");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tsql = "select * from user_info where [user_info].[user] = '$user' and [user_info].[pass] = '$pass'";
//            $getResults = $conn->prepare($tsql);
//            $getResults->execute();
//            $results = $getResults->fetchAll(PDO::FETCH_BOTH);
            $results = $conn->query($tsql);
            if($results->rowCount()==0)
            {
                return 0;
            }
            else
            return 1;
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    public function changePass($user,$oldpass,$newpass)
    {
        try
        {
            $conn = new PDO("sqlsrv:Server=$this->server_name;Database=$this->database_name;ConnectionPooling=0", "thcntt03test", "Ih22wbpu-9I-");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tsql = "UPDATE user_info SET [user_info].[pass] = '$newpass' WHERE [user_info].[user] = '$user' and [user_info].[pass] = '$oldpass'";
//            $getResults = $conn->prepare($tsql);
//            $getResults->execute();
//            $results = $getResults->fetchAll(PDO::FETCH_BOTH);
            $conn->exec($tsql);
            return 'Changed Password Success';
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

// $tsql = "SELECT * FROM [thcntt03test].[dbo].[userinfo]";
//      $getResults = $conn->prepare($tsql);
//      $getResults->execute();
//      $results = $getResults->fetchAll(PDO::FETCH_BOTH);

//      foreach($results as $row)
//      {
//          echo $row['username'].' '.$row['password'];
//          echo '<br>';
//      }
}