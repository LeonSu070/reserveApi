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
    var $limit_order_number = 10;
    public function __construct()
    {
        $this->load->model('data/d_order');
    }

    //下订单
    public function add_order($data) {
        //如果超出额度，则禁止下订单
        if ($data['order_type'] == 2) {
            //获取当天所有订单
            $order_list = $this->get_order_by_date_type(date("Y-m-d", strtotime($data['order_time']), $data['order_type']);
            //计算上午和下午的订单数, 预约送检的订单只分上午和下午，上午以09:00表示，下午以13：00表示
            $morning = $afternoon = 0;
            foreach ($order_list as $order) {
                if (date("H", strtotime($order['order_time'])) == "09" ) {
                    $morning += 1;
                } else {
                    $afternoon += 1;
                }
            }
            if (date("H", strtotime($data['order_time'])) == "09" && $morning >= $this->limit_order_number ) {
                return false;
            }
            if (date("H", strtotime($data['order_time'])) == "13" && $afternoon >= $this->limit_order_number ) {
                return false;
            }
        }
       

        $re = $this->d_order->insert($data);
        if( $re ){
            $this->load->model('m_sms');
            //给自己发短信
            $params = array(
                'user_name' => $data['user_name'], 
                'order_time' => date("Y年m月d日H:i", strtotime($data['order_time'])), 
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
                'order_time' => date("Y年m月d日H:i", strtotime($data['order_time'])), 
                'order_type' => $this->order_type_sms_template[$data['order_type']]['name'], 
                'sample_amount' => $data['sample_amount'], 
                'sample_name' => $data['sample_name'], 
            );
        
            $this->m_sms->send($data['mobile'], $params, $this->order_type_sms_template[$data['order_type']]['C']);
        }
        return $re; 
    }

    //获取可用时间
    public function get_time($param){
        //上门取货直接返回
        if (!in_array($param['order_type'], array(1,2))) {
            return false;
        }
        if ($param['order_type'] == 1) {
            return array(
                array('value' => '09:00', 'text' => '09:00'),
                array('value' => '10:00', 'text' => '10:00'),
                array('value' => '11:00', 'text' => '11:00'),
                array('value' => '13:00', 'text' => '13:00'),
                array('value' => '14:00', 'text' => '14:00'),
                array('value' => '15:00', 'text' => '15:00'),
            );
        }
        //获取当天所有订单
        $order_list = $this->get_order_by_date_type($param['order_date'], $param['order_type']);
        //计算上午和下午的订单数, 预约送检的订单只分上午和下午，上午以09:00表示，下午以13：00表示
        $morning = $afternoon = 0;
        foreach ($order_list as $order) {
            if (date("H", strtotime($order['order_time'])) == "09" ) {
                $morning += 1;
            } else {
                $afternoon += 1;
            }
        }
        $return = array();
        if ($morning < $this->limit_order_number) {
            $return[] = array('value' => '09:00', 'text' => '上午');
        }
        if ($afternoon < $this->limit_order_number) {
            $return[] = array('value' => '13:00', 'text' => '下午');
        }
        return $return;
    }
    //获取某一天的订单
    public function get_order_by_date_type($order_date, $order_type){
        return $this->d_order->get_order_by_date_type($order_date, $order_type);
    }
}
