<?php

require_once  './vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class firebaseDAO
{
    protected $realtimedatabase;
    protected $firestoredatabase;
    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromValue(__DIR__.'/secret/smarthome-a93f9-882c4bb55a05.json');
        $this->realtimedatabase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://smarthome-a93f9.firebaseio.com/')
            ->createDatabase();
        $this->firestoredatabase =
            (new Factory())
            ->withServiceAccount($serviceAccount)
            ->withProjectId('smarthome-a93f9')
            ->createFirestore();
    }

    public function getValueRealTime()
    {
        return $this->realtimedatabase->getReference()->getValue();
    }

    public function setValueStatusPhatHienChay($bool,$user,$token)
    {
        if($user == 'chaychaychay'&&$token==$this->getTokenUser($user))
        {
            switch ((int) $bool)
            {
                case 1:
                    {
                        $this->realtimedatabase->getReference()->getChild('PhatHienChay')->set('Có cháy');
                        if ($this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->snapshot()->exists()) {
                            $array = $this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->snapshot()->get('logs');
                            date_default_timezone_set('Asia/Ho_Chi_Minh');
                            $date = date('h:i:s_a_m/d/Y', time());
                            $array[] = 'TRUE '.$date;
                            $this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->set(array('Status'=>'Nguy hiểm','logs'=>$array));
                        }
                    }break;
                case 0:
                    {
                        $this->realtimedatabase->getReference()->getChild('PhatHienChay')->set('Không có cháy');
                        if ($this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->snapshot()->exists()) {
                            $array = $this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->snapshot()->get('logs');
                            date_default_timezone_set('Asia/Ho_Chi_Minh');
                            $date = date('h:i:s_a_m/d/Y', time());
                            $array[] = 'FALSE '.$date;
                            $this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->set(array('Status'=>'An toàn','logs'=>$array));
                        }
                    }
                    break;
                default: return false;
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function setValueStatusPhatHienDotNhap($bool,$user,$token)
    {
        if($user == 'tromtromtrom'&&$token==$this->getTokenUser($user))
        {
            switch ((int) $bool)
            {
                case 1:
                    {
                        $this->realtimedatabase->getReference()->getChild('PhatHienDotNhap')->set('Có Người Đột Nhập');
                        if ($this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->snapshot()->exists()) {
                            $array = $this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->snapshot()->get('logs');
                            date_default_timezone_set('Asia/Ho_Chi_Minh');
                            $date = date('h:i:s_a_m/d/Y', time());
                            $array[] = 'TRUE '.$date;
                            $this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->set(array('Status'=>'Nguy hiểm','logs'=>$array));
                        }
                    }
                    break;
                case 0:
                    {
                        $this->realtimedatabase->getReference()->getChild('PhatHienDotNhap')->set('Không Có Người Đột Nhập');
                        if ($this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->snapshot()->exists()) {
                            $array = $this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->snapshot()->get('logs');
                            date_default_timezone_set('Asia/Ho_Chi_Minh');
                            $date = date('h:i:s_a_m/d/Y', time());
                            $array[] = 'FALSE '.$date;
                            $this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->set(array('Status'=>'An toàn','logs'=>$array));
                        }
                    }break;
                default: return false;
            }
            return true;
        }
        else return false;
    }

    public function getValueFireStore()
    {
        try {
            $data = array();
            if ($this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->snapshot()->exists()) {
                $data[] = array('PhatHienDotNhap'=>$this->firestoredatabase->database()->collection('PhatHienDotNhap')->document('PhatHienDotNhap')->snapshot()->data());
            } else {
                throw new Exception('Document are not exists');
            }
            if ($this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->snapshot()->exists()) {
                $data[] = array('PhatHienChay'=>$this->firestoredatabase->database()->collection('PhatHienChay')->document('PhatHienChay')->snapshot()->data());
            } else {
                throw new Exception('Document are not exists');
            }
        }catch (Exception $e)
        {
            $data[] = array('error'=>$e->getMessage());
        }
        return $data;
    }


    public function createToken($user)
    {
        try {
//            $uid = (string) $this->auth->createCustomToken((string) random_int(100, 999));
            $uid = $this->getRandomToken();
            $this->firestoredatabase->database()->collection('user')->document($user)->create(array('token'=>$uid));
            return $uid;
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getTokenUser($user)
    {
        try {
            if ($this->firestoredatabase->database()->collection('user')->document($user)->snapshot()->exists()) {
                return $this->firestoredatabase->database()->collection('user')->document($user)->snapshot()->get('token');
            } else {
                throw new Exception('Document are not exists');
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function loginAccount($user,$bool)
    {
        if($bool==1)
        {
            try {
//                $uid = (string) $this->auth->createCustomToken((string) random_int(100, 999));
                $uid = $this->getRandomToken();
                $this->firestoredatabase->database()->collection('user')->document($user)->set(array('token'=>$uid));
                return $uid;
            }
            catch (Exception $e)
            {
                return $e->getMessage();
            }
        }
    }

    private function getRandomToken()
    {
        $a = (int) random_int(100,200);
        $string = '';
        $array = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','J','K','L','O','P','Q','R','S','X','M','N','T','V','W'];
        for($i=0;$i<$a;$i++){
            $string .= $array[(int) random_int(0,31)];
        }
        return $string;
    }
}