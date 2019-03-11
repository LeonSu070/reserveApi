<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
/**
 *
 * @copyright copyright(2015) iyouhi
 * @author Leon
 * @package Model
 */
class m_verify extends CI_model
{
    public function __construct()
    {
        $this->load->model('data/d_verify');
    }

    public function send_verify_code($mobile) 
    {
        //生成code并种入session
        $code = $this->get_verify_code($mobile);

        //发送code到用户手机号
        return $this->d_verify->send_code($mobile, $code);
    }
    private function get_verify_code($mobile)
    {
        if (empty($mobile)) {
            return false;
        }
        session_start();
        $key = "verify_code_" . $mobile;
        //session key已经存在
        if (isset($_SESSION[$key]) && !empty($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        //不存在则重新生成
        srand((double)microtime()*1000000); 
        $code = rand(1000,9999);
        $_SESSION[$key]= $code;
        return $code;
    }

}
