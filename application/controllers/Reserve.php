<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Notice API
 *
 * @package CodeIgniter
 * @subpackage API
 * @category Controller
 * @author Leon
 *        
 */
require_once APPPATH . '/libraries/REST_Controller.php';
class Reserve extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        @header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie, UserData,Authorization');
    }

    function add_order_post()
    {
        $params['user_name'] = $this->input->post('user_name');
        $params['mobile'] = $this->input->post('mobile');
        $params['company_name'] = $this->input->post('company_name');
        $params['province'] = $this->input->post('province');
        $params['city'] = $this->input->post('city');
        $params['address_detail'] = $this->input->post('address_detail');
        $params['description'] = $this->input->post('description');
        $params['special_note'] = $this->input->post('special_note');
        $params['order_type'] = $this->input->post('order_type');
        $params['order_time'] = $this->input->post('order_time');

        $vcode = $this->input->post('vcode');

        $this->load->model('m_verify');
        if ($vcode != $this->m_verify->get_verify_code($params['mobile'])) {
            $return_message = array (
                    'code' => '20002', 
                    'message' => "验证码错误"
            );
            $this->response($return_message, 200);
        }

        $this->load->model('m_order');
        $result = $this->m_order->add_order($params);
        
        if ($result)
        {
            $return_message = array (
                    'code' => '10000', 
                    'message' => "预约成功"
            );
        }
        else
        {
            $return_message = array (
                    'code' => '20000', 
                    'message' => "预约失败"
            );
        }
        $this->response($return_message, 200);
    }
}
