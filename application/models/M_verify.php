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
        $this->load->model('m_sms');
        return $this->m_sms->send($mobile, array('jymcode'=>$code), "vcode");
    }
    public function get_verify_code($mobile)
    {
        if (empty($mobile)) {
            return false;
        }
        //获取code
        $code = $this->d_verify->get_code($mobile);
        //session key已经存在
        if (!empty($code)) {
            return $code['code'];
        }
        //不存在则重新生成
        
        $code = $this->genarate_code($mobile);
        return $code;
    }
    /*
     * 生成新的验证码
     */
    public function genarate_code($mobile){
        if (empty($mobile)) {
            return false;
        }
        srand((double)microtime()*1000000); 
        $code = rand(1000,9999);
        //插入code
        $this->d_verify->insert_code(array("mobile"=>$mobile,"code"=>$code));
        return $code;
    }

}
