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
class m_sms extends CI_model
{
    public function __construct()
    {
        $this->load->model('data/d_sms');
    }

    public function send($mobile, $param, $template="vcode") 
    {
        //发送短信到用户手机号
        return $this->d_sms->send($mobile, $param, $template);
    }
}
