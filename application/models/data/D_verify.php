<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
/**
 *
 * @copyright copyright(2019) iyouhi
 * @author Leon
 * @package Model
 */
require_once APPPATH . '/libraries/Httphelper.php';
class d_verify extends CI_model
{
    public function __construct()
    {
        $this->sms_url = "http://sms.iyouhi.com/sms/sendByChope";
        $this->sms_token = "KJekeiJEWGS7YT5jwekj2NCBS7ejK";
    }
    
    public function send_code($mobile, $code)
    {
        $data = array(
            "token" => $this->sms_token,
            "phone_number" => $mobile,
            "template" => "vcode",
            "params" => json_encode(array("number"=>$code))
        );
        $result = json_decode(Httphelper::post($this->sms_url, $data), TRUE);
        if ($result['Code'] == 10000) {
            return true;
        }
        return false;
    }

}
