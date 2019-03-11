<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
/**
 *
 * @copyright copyright(2019) iyouhi
 * @author Leon
 * @package Model
 */
class d_order extends CI_model
{
    var $table_name = 'reservation';
    public function __construct()
    {
        //暂时不会主从库，需要分主从库时再拆分
        $this->load->database();
    }
    

    public function insert($data_arr)
    {
        if (empty($data_arr)) {
            return false;
        }
        return $this->db->insert($this->table_name, $data_arr);
    }

    public function update($where_arr, $update_arr)
    {
        if (empty($where_arr) || empty($update_arr)) {
            return false;
        }
        
        // Update
        $this->db->where($where_arr);
        $this->db->update($this->table_name, $update_arr);
        return $this->db->affected_rows();
    }

    public function get_number($where_data)
    {
        $this->db->where($where_data);
        $this->db->from($this->table_name);
        return $this->db->count_all_results();
    }
}
