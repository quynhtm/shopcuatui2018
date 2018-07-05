<?php
/**
 * Created by PhpStorm.
 * User: ThinhLK
 * Date: 7/9/14
 * Time: 10:55 AM
 */
namespace App\Library\AdminFunction;

class Curl {

    protected static $ch = null;
    protected static $instance = null;

    public static function getInstance(){
        if(self::$ch === null){
            self::$instance = new self();
            self::init();
            return self::$instance;
        }else{
            return self::$instance;
        }
    }

    public static function init() {
        try{
            if (!isset(self::$ch)) {
                $options = array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER         => 0,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_ENCODING       => "",
                    CURLOPT_AUTOREFERER    => true,
                    CURLOPT_CONNECTTIMEOUT => 60,
                    CURLOPT_TIMEOUT        => 60,
                    CURLOPT_MAXREDIRS      => 10
                );
                self::$ch = curl_init();
                curl_setopt_array( self::$ch, $options );
            }
        }catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function get($url, $userToken = '', $flagHttps = false) {
        if (isset(self::$ch)) {
            curl_setopt(self::$ch, CURLOPT_URL, $url);
            curl_setopt(self::$ch, CURLOPT_HTTPGET, 1);
            if($userToken != ''){
                curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $userToken));
            }
            if($flagHttps) {
                curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
            }
            $data = curl_exec(self::$ch);
            return $data;
        }
    }

    public function post($url, $vars, $userToken = '', $flagHttps = false, $flgJson = false) {
        if(isset(self::$ch)){
            curl_setopt(self::$ch, CURLOPT_URL, $url);
            curl_setopt(self::$ch, CURLOPT_POST, 1);
            if($userToken != ''){
                curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $userToken));
            }

            if($flgJson) {
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, json_encode($vars));
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen(json_encode($vars)))
                );
            }else{
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $vars);
            }

            if($flagHttps) {
                curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
            }
            $data= curl_exec(self::$ch);
            return $data;
        }
    }

    public function put($url, $vars, $userToken = '', $flagHttps = false, $flgJson = false) {
        if(isset(self::$ch)){
            curl_setopt(self::$ch, CURLOPT_URL, $url);
            curl_setopt(self::$ch, CURLOPT_POST, 1);
            curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if($userToken != ''){
                curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $userToken));
            }

            if($flgJson) {
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, json_encode($vars));
                curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen(json_encode($vars)))
                );
            }else{
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $vars);
            }

            if($flagHttps) {
                curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
            }
            $data= curl_exec(self::$ch);
            return $data;
        }
    }

    public function close() {
        if (isset(self::$ch)) {
        }
    }

    public function __destruct() {
        $this->close();
    }
} 