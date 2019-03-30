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
class m_order extends CI_model
{
    var $order_type_sms_template = array(
        "1" => array(
            "B" => "send2B1",
            "C" => "send2C1",
            "name" => "上门取样", 
        ),
        "2" => array(
            "B" => "send2B2",
            "C" => "send2C2", 
            "name" => "送样检测",
        ),
    );
    var $Bmobile = "13321179308";
    public function __construct()
    {
        $this->load->model('data/d_order');
    }

    public function add_order($data) {

        $re = $this->d_order->insert($data);
        if( $re ){
            $this->load->model('m_sms');
            //给自己发短信
            $params = array(
                'user_name' => $data['user_name'], 
                'order_time' => $data['order_time'],
                'order_type' => $this->order_type_sms_template[$data['order_type']]['name'],
                'sample_amount' => $data['sample_amount'], 
                'sample_name' => $data['sample_name'], 
                'phone_number' => $data['mobile'],
                'id_number' => $data['id_number'], 
                'address' => $data['province'] . $data['city'] . $data['area'] . $data['address_detail'],
            );
            $this->m_sms->send($this->Bmobile, $params, $this->order_type_sms_template[$data['order_type']]['B']);
        
            //给客户发短信
            $params = array(
                'user_name' => $data['user_name'], 
                'order_time' => $data['order_time'], 
                'order_type' => $this->order_type_sms_template[$data['order_type']]['name'], 
                'sample_amount' => $data['sample_amount'], 
                'sample_name' => $data['sample_name'], 
            );
        
            $this->m_sms->send($data['mobile'], $params, $this->order_type_sms_template[$data['order_type']]['C']);
        }
        return $re; 
    }
}
