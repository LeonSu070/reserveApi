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
class D_order extends CI_model
{
    var $DB_W = NULL;
    var $DB_R = NULL;
    var $table_name = 'reservation';
    public function __construct()
    {
        //暂时不会主从库，需要分主从库时再拆分
        $this->DB_W = $this->DB_R = $this->load->database();
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
