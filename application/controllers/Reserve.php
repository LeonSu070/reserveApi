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
    }

    function add_order_post()
    {
        $params['user_name'] = $this->input->post('user_name');
        $params['memberid'] = $this->input->post('memberid');
        $params['mobile'] = $this->input->post('mobile');
        $params['id_number'] = $this->input->post('id_number');
        $params['company_name'] = $this->input->post('company_name');
        $params['province'] = $this->input->post('province');
        $params['city'] = $this->input->post('city');
        $params['area'] = $this->input->post('area');
        $params['address_detail'] = $this->input->post('address_detail');
        $params['sample_name'] = $this->input->post('sample_name');
        $params['sample_amount'] = $this->input->post('sample_amount');
        $params['sample_description'] = $this->input->post('sample_description');
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
            //使验证码失效
            $this->m_verify->genarate_code($params['mobile']);
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
    //获取可用时间
    function get_time_get()
    {
        
        $params['order_type'] = $this->input->post('order_type');
        $params['order_date'] = $this->input->post('order_date');

        $this->load->model('m_order');
        $result = $this->m_order->get_time($params);
        
        if ($result)
        {
            $return_message = array (
                    'code' => '10000', 
                    'message' => "成功",
                    'data' => $result,
            );
            
        }
        else
        {
            $return_message = array (
                    'code' => '20000', 
                    'message' => "失败"
            );
        }
        $this->response($return_message, 200);
    }
}
