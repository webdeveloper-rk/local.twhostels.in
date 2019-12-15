<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class assigned_schools{

        public function get_list($uid,$add_zero=0)
        {
			$CI =& get_instance();
			$checked_list = array();
			$data_selected_set  =  $CI->db->query("select *  from assigned_schools where user_id=?",array($uid));
			foreach($data_selected_set->result() as $asrow)
			{
				$checked_list[] = $asrow->school_id;
			}
			if($add_zero==1 && count($checked_list)==0)
				$checked_list[] = 0;
			return $checked_list;
        }
}