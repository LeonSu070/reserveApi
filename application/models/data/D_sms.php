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
class d_sms extends CI_model
{
    var $table_name = 'sms_log';
    public function __construct()
    {
        $this->sms_url = "http://sms.aiyohey.com/sms/sendByChope";
        $this->sms_token = "KJekeiJEWGS7YT5jwekj2NCBS7ejK";
        //暂时不会主从库，需要分主从库时再拆分
        $this->load->database();
    }
    /*
     * $template:
     * 1. vcode : 验证码
     * 2. send2B : 发送给主人的短信
     * 3. send2C : 发送给客户的短信 
     */
    public function send($mobile, $param, $template="vcode")
    {
        $data = array(
            "token" => $this->sms_token,
            "phone_number" => $mobile,
            "template" => $template,
            "params" => json_encode($param);
        );
	    $res = Httphelper::post($this->sms_url, $data);
        $result = json_decode($res, TRUE);
        if ($result['code'] == 10000) {
            //记日志
            $sms_log = array(
                "bizid" = result['data']['BizId'];
                "template" => $template,
                "mobile" => $mobile,
                "params" => json_encode($param),
            );
            $this->insert_sms_log($sms_log);

            return true;
        }
        return false;
    }
    /*
     * 插入日志
     */
    private function insert_sms_log($data_arr)
    {
        if (empty($data_arr)) {
            return false;
        }
        return $this->db->insert($this->table_name, $data_arr);
    }

}
