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
    public function __construct()
    {
        $this->load->model('data/d_order');
    }

    public function add_order($data) {
        return $this->d_order->insert($data);
    }
}
