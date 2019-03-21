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
    var $table_name = 'vcode';
    public function __construct()
    {
        $this->sms_url = "http://sms.iyouhi.com/sms/sendByChope";
        $this->sms_token = "KJekeiJEWGS7YT5jwekj2NCBS7ejK";
        //暂时不会主从库，需要分主从库时再拆分
        $this->load->database();
    }
    
    public function send_code($mobile, $code)
    {
        $data = array(
            "token" => $this->sms_token,
            "phone_number" => $mobile,
            "template" => "vcode",
            "params" => json_encode(array("number"=>$code))
        );
	$res = Httphelper::post($this->sms_url, $data);
        $result = json_decode($res, TRUE);
        if ($result['code'] == 10000) {
            return true;
        }
        return false;
    }

    public function get_code($mobile)
    {
        $this->db->where('mobile', $mobile);
        $this->db->where('ctime >', date("Y-m-d H:i:s", time()-30*60));
        return $this->db->get($this->table_name)->row_array();
    }
    public function insert_code($data)
    {
        if (empty($data)) {
            return false;
        }
        return $this->db->insert($this->table_name, $data);
    }

}
