<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Category
 *
 * @copyright copyright(2015) chope all rights reserved
 * @author Jane <Jane@chope.co>
 * @package Model
 */
class M_order extends CI_model
{
    public function __construct()
    {
        $this->load->model('data/d_order');
    }

    public function add_order() {
        return $this->d_order->insert();
    }
}
