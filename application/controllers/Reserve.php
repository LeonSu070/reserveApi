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
        $user_name = $this->post('username');
        var_dump($user_name);exit;
        $this->load->model('m_order');

        $this->m_order->add_order();
        if ($res)
        {
            $return_message = array (
                    'code' => '10000', 
                    'message' => "预约成功"
            );
        }
        else
        {
            $return_message = array (
                    'CODE' => '20001', 
                    'MESSAGE' => "预约失败"
            );
        }
        $this->response($return_message, 200);
    }
}
