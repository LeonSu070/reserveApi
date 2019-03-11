<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Http Calling Class
 *
 * @package application
 * @subpackage Libraries
 * @category Httphelper
 * @author Sam Zhao(sam@chope.com.sg)
 *        
 */
class Httphelper
{
    public static $http_code = null;
    
    /**
     * init CURL
     *
     * @return resource
     */
    protected static function curl_init()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return $ch;
    }
    
    /**
     * Get Result
     *
     * @param resource $ch            
     * @param string $url            
     * @param mixed $data            
     * @param string $cookie            
     * @param string $referer            
     * @param string $userAgent            
     * @return mixed
     */
    protected static function curl_result($ch, $url, $data = null, $cookie = null, $referer = null, $userAgent = null, $timeout = 15)
    {
        curl_setopt_array($ch, array (
                CURLOPT_URL => $url, 
                CURLOPT_COOKIE => $cookie, 
                CURLOPT_REFERER => $referer, 
                CURLOPT_USERAGENT => $userAgent, 
                CURLOPT_TIMEOUT => $timeout 
        ));
        $data && curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        
        self::$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($result === false) // error
        {
            $msg = curl_error($ch);
            $code = curl_errno($ch);
            self::error($msg, $code);
            trigger_error("[{$code}] {$msg}", E_USER_WARNING);
        }
        // elseif($http_code >= 400) //HTTP exception
        // {
        // $msg = 'Server returns status code exception.';
        // self::error($msg, $http_code);
        // trigger_error("[{$http_code}] {$msg} <br /><b>URL:</b>{$url}<br /><b>data:</b>".print_r($data, true), E_USER_WARNING);
        // }
        else
        {
            self::error('', 0);
        }
        curl_close($ch);
        return $result;
    }
    
    /**
     * read error message or record error
     *
     * @staticvar string $message
     * @param string $msg            
     * @return array
     */
    public static function error($msg = null, $code = null)
    {
        static $error_msg = '', $error_code = 0;
        if ($msg !== null && $code !== null)
        {
            $error_msg = $msg;
            $error_code = $code;
        }
        else
        {
            return array (
                    'errmsg' => $error_msg, 
                    'errno' => $error_code 
            );
        }
    }
    
    /**
     * get call
     *
     * @param string $url            
     * @param string $cookie            
     * @param string $referer            
     * @param string $userAgent            
     */
    static public function get($url, $cookie = null, $referer = null, $userAgent = null, $time_out = 30)
    {
        $ch = self::curl_init();
        return self::curl_result($ch, $url, null, $cookie, $referer, $userAgent, $time_out);
    }
    static public function get_rest($username_password, $url, $cookie = null, $referer = null, $userAgent = null, $time_out = 30)
    {
        $ch = self::curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, $username_password);
        return self::curl_result($ch, $url, null, $cookie, $referer, $userAgent, $time_out);
    }
    static public function get_rest_with_header($header, $url, $cookie = null, $referer = null, $userAgent = null, $time_out = 30)
    {
        $ch = self::curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        return self::curl_result($ch, $url, null, $cookie, $referer, $userAgent, $time_out);
    }
    
    /**
     * post call
     *
     * @param string $url            
     * @param mixed $data            
     * @param string $cookie            
     * @param string $referer            
     * @param string $userAgent            
     */
    static public function post_bin($url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out = 15)
    {
        $ch = self::curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        return self::curl_result($ch, $url, $data, $cookie, $referer, $userAgent, $time_out);
    }
    
    /**
     * post request(urlencode)
     *
     * @param string $url            
     * @param mixed $data            
     * @param string $cookie            
     * @param string $referer            
     * @param string $userAgent            
     */
    static public function post($url, $data, $cookie = null, $referer = null, $userAgent = null)
    {
        is_array($data) && $data = http_build_query($data);
        return self::post_bin($url, $data, $cookie, $referer, $userAgent);
    }
    /**
     * json request
     * @param string    $url
     * @param string      $data
     * @param string    $cookie
     * @param string    $referer
     * @param string    $userAgent
     */
    static public function post_json($url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out=30)
    {
        $ch = self::curl_init();
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        return self::curl_result($ch, $url, $data, $cookie, $referer, $userAgent, $time_out);
    }
    static public function post_rest($username_password, $url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out = 30)
    {
        is_array($data) && $data = http_build_query($data);
        $ch = self::curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $username_password);
        return self::curl_result($ch, $url, $data, $cookie, $referer, $userAgent, $time_out);
    }
    static public function post_rest_with_header($header, $username_password, $url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out = 30)
    {
        is_array($data) && $data = http_build_query($data);
        $ch = self::curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($username_password)
        {
            curl_setopt($ch, CURLOPT_USERPWD, $username_password);
        }
        return self::curl_result($ch, $url, $data, $cookie, $referer, $userAgent, $time_out);
    }
    /**
     * json request
     * @param string    $url
     * @param json      $data
     * @param string    $cookie
     * @param string    $referer
     * @param string    $userAgent
     */
    static public function post_json_rest($username_password, $url, $data, $cookie=null, $referer=null, $userAgent=null, $time_out=30)
    {
        $ch = self::curl_init();
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
        curl_setopt($ch,CURLOPT_USERPWD, $username_password);
        curl_setopt($ch,CURLOPT_POST,1);
        return self::curl_result($ch, $url, $data, $cookie, $referer, $userAgent, $time_out);
    }
    /**
     * Delete request
     *
     * @param string $url            
     * @param mixed $data            
     * @param string $cookie            
     * @param string $referer            
     * @param string $userAgent            
     */
    static public function delete($url, $data, $cookie = null, $referer = null, $userAgent = null, $time_out = 15)
    {
        $ch = self::curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        is_array($data) && $data = http_build_query($data);
        return self::curl_result($ch, $url, $data, $cookie, $referer, $userAgent, $time_out);
    }
}
