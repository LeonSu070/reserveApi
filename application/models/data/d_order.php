<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
/**
 * Category
 *
 * @copyright copyright(2019) iyouhi all rights reserved
 * @author Leon
 * @package Model
 */
class M_order extends CI_model
{

    public function __construct()
    {
        $this->load->database();
    }
    

    public function insert($data_arr)
    {
        if (empty($data_arr)) {
            return false;
        }
        $this->DB_W->insert($this->table_name, $data_arr);
        return $this->DB_W->insert_id();
    }

    public function update($where_arr, $update_arr)
    {
        if (empty($where_arr) || empty($update_arr)) {
            return false;
        }
        
        // Update
        $this->DB_W->where($where_arr);
        $this->DB_W->update($this->table_name, $update_arr);
        return $this->DB_W->affected_rows();
    }

    public function get_number($where_data)
    {
        $this->DB_R->where($where_data);
        $this->DB_R->from($this->table_name);
        return $this->DB_R->count_all_results();
    }
}
