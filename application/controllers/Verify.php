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
class Verify extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function send_verify_code_post()
    {
        $mobile = $this->input->post('mobile');

        $this->load->model('m_verify');
        $result = $this->m_verify->send_verify_code($mobile);
        
        if ($result)
        {
            $return_message = array (
                    'code' => '10000', 
                    'message' => "发送成功"
            );
        }
        else
        {
            $return_message = array (
                    'code' => '20000', 
                    'message' => "发送失败"
            );
        }
        $this->response($return_message, 200);
    }
}
