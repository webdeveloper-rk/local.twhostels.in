<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter URL Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/url_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('get_price'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_price($item_id=0, $vendor_id = NULL)
	{
		//$this->session->userdata("school_id");
		return 10.00;
	}
	 
}
if ( ! function_exists('print_a'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	 
	function print_a($arr= array() ,$exist=0)
	{
		echo "<pre>";print_r($arr);echo "</pre>";
		if($exist)
		{
			die();
		}
	}
}

if ( ! function_exists('get_posted_value'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_posted_value($key,$default_value=null,$print= false)
	{
		$value = '';
		if( $this->input->post($key) !="" && $default_value==null)
		{
			$value =  $this->input->post($key);
		}
		else
		{
			$value = $default_value;
		}
		if($print)	
				echo $value;
		else
				return $value;
	}
	 
}
if ( ! function_exists('ip_allowed_to_edit'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function ip_allowed_to_edit($ip_address)
	{ 
		 $allowed_ips = array('175.101.67.194', '103.49.53.149','157.48.108.86');
		 
		if(in_array($ip_address,$allowed_ips )){
		 return true;
		}
		else{
			return false;
		}
	}
	 
}
if ( ! function_exists('get_entry_id'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_entry_id($school_id,$item_id,$date)
	{
		 return $school_id."_".$item_id."_".$date;
	}
	 
}


if ( ! function_exists('get_db_date2'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_db_date2($date = null)
	{
		 $date_explode= explode("/",$date);
		 $date =  $date_explode[2]."-".$date_explode[0]."-".$date_explode[1];
		 
		 
		 //$date = date('Y-m-d',strtotime($date));
		  return  $date;
		  
	}
	 
}


if ( ! function_exists('get_db_date'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_db_date($date = null)
	{
		 $date_explode= explode("/",$date);
		 $date =  $date_explode[2]."-".$date_explode[1]."-".$date_explode[0];
		 
		 
		 //$date = date('Y-m-d',strtotime($date));
		  return  $date;
		  
	}
	 
}
if ( ! function_exists('get_display_date'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_display_date($date = null)
	{
		 $date = date('d-M-Y',strtotime($date));
		 if($date=="01-Jan-1970")
				$date = '';
		  return  $date;
		  
	}
	 
}

if ( ! function_exists('send_json_result'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function send_json_result($data_array = null)
	{
		   header("Content-type:application/json");
		 echo json_encode($data_array,JSON_UNESCAPED_SLASHES);
		 die;
		  
	}
	 
}




if ( ! function_exists('input_date'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function input_date($inputs= array())
	{
		extract($inputs);
		///$prefix  selected_day selected_month selected_year 
		
		$day_name = $prefix ."_day";
		$month_name = $prefix ."_month"; 
		$year_name= $prefix ."_year"; 
		$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
		 $select_text = '';
		 $select_text = "<select name='$day_name' id='$day_name'  required style='width:100px'><option value=''>Select Day</option>";
		 for($day=1;$day<=31;$day++)
			{
				$day_text = $day;
				if($day<10)
					$day_text="0".$day;
				
					 $select_text .= "<option value='$day_text'>$day_text</option>";
			}
			$select_text .= "</select>";
			
			 $select_text .= "<select name='$month_name' id='$month_name' required  style='width:100px'><option value=''>Select Month</option>";
			 
		 for($month=1;$month<=12;$month++)
			{
				$month_key= $month;
				 if($month<10)
					$month_key="0".$month;
				
					 $select_text .= "<option value='$month'>".$months[$month_key]."</option>";
			}
			$select_text .= "</select>";
			
			 $select_text .= "<select name='$year_name' id='$year_name' required  style='width:100px'><option value=''>Select Year</option>";
		 for($year=date('Y');$year>=2017; $year--)
			{ 
					 $select_text .= "<option value='$year'>$year</option>";
			}
			$select_text .= "</select>";
			return $select_text;
	}
	 
}

if ( ! function_exists('check_valid_date'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function check_valid_date($prefix)
	{
		$ci_instance =& get_instance(); 
		///$prefix  selected_day selected_month selected_year 
		
		$day_name = $prefix ."_day";
		$month_name = $prefix ."_month"; 
		$year_name= $prefix ."_year"; 
		
			 $px_day = intval($ci_instance->input->post($day_name));
			 $px_month = intval($ci_instance->input->post($month_name));
			 $px_year = intval($ci_instance->input->post($year_name));
			 $formated_date = $px_year."-".$px_month."-".$px_day;
			 //bool checkdate ( int $month , int $day , int $year )
			 
			 $is_valid =  checkdate ($px_month,  $px_day ,  $px_year );
			 if(! $is_valid)
			 {
				 return false;
			 }else{
				 return  true;
			 }
		 
	}
	 
}

if ( ! function_exists('get_prefix_date'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_prefix_date($prefix)
	{
		 $ci_instance =& get_instance();
		
		$day_name = $prefix ."_day";
		$month_name = $prefix ."_month"; 
		$year_name= $prefix ."_year"; 
		
			 $px_day = intval($ci_instance->input->post($day_name));
			 $px_month = intval($ci_instance->input->post($month_name));
			 $px_year = intval($ci_instance->input->post($year_name));
			 $formated_date = $px_year."-".$px_month."-".$px_day;
			  return $formated_date;
	}
	 
}


if ( ! function_exists('get_prefix_date_indian'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_prefix_date_indian($prefix)
	{
		 $ci_instance =& get_instance();
		
		$day_name = $prefix ."_day";
		$month_name = $prefix ."_month"; 
		$year_name= $prefix ."_year"; 
		
			 $px_day = intval($ci_instance->input->post($day_name));
			 $px_month = intval($ci_instance->input->post($month_name));
			 $px_year = intval($ci_instance->input->post($year_name));
			 $formated_date =  $px_day."-".$px_month."-".$px_year ;
			  return $formated_date;
	}
	 
}

if ( ! function_exists('chk_date_format'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function chk_date_format($date_string)
	{
			if (1 !== preg_match('#^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$#', $date_string)) 
		{
				return false;
		}else{
		return true;
		}
	}
	 
}

if(!function_exists('injection_check'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function injection_check()
	{
		 //check posted data and redirect to referer page;
	}
	 
}



if ( ! function_exists('school_selection'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function school_selection($school_id_selected=0)
	{
		 $ci_instance =& get_instance();
			if($ci_instance->session->userdata("district_id")=="school")
			{
				 return '';
			}
			$uid  = $ci_instance->session->userdata("user_id"); 
			$district_id  = $ci_instance->session->userdata("district_id");  
			if( $ci_instance->session->userdata("district_id")==0  ) 
			{ 
				$school_rs = $ci_instance->db->query("SELECT s.school_id,s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s inner join districts d 
													on d.district_id = s.district_id and s.name not like 'coll%' and is_school=1 order by school_code");	
			}

			else if( $ci_instance->session->userdata("district_id") >0 ) 
			{
					$is_atdo = $ci_instance->db->query("select * from users where uid=?",array($uid))->row()->is_atdo;
					if($is_atdo ==1)
					{
						$schools_list = array();
								$data_selected_set  =  $ci_instance->db->query("select *  from assigned_schools where user_id=?",array($uid));
								foreach($data_selected_set->result() as $asrow)
								{
									$schools_list[] = $asrow->school_id;
								}
								if( count($schools_list)==0)
									$schools_list[] = 0;
								
								$school_rs = $ci_instance->db->query("SELECT s.school_id,s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s 
											inner join districts d on d.district_id = s.district_id and s.name not like 'coll%' and d.district_id=? and s.school_id in ?  and is_school=1 order by school_code ",array($district_id,$schools_list));	
								
								
					}
					else{
					$school_rs = $ci_instance->db->query("SELECT s.school_id,s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s 
											inner join districts d on d.district_id = s.district_id and s.name not like 'coll%' and d.district_id=? and is_school=1 order by school_code ",array($district_id));	
					}

			}
			
			$option_selected = '';
			$school_id_selected = intval($school_id_selected);
				if($school_id_selected == 0)
							$option_selected = ' selected ';
						
		 $select_text = "<select name='school_id' id='school_id' class='search' required style='height:30px;' >
							<option value='' $option_selected>Select School</option>"; 
		foreach($school_rs->result() as $school_row)
			{
				$option_selected = '';
				if($school_id_selected == $school_row->school_id)
							$option_selected = ' selected ';
				$select_text .= "<option value='".$school_row->school_id."' ".$option_selected.">".$school_row->school_code."-".$school_row->sname."-".$school_row->dname."-".$school_row->school_id."</option>";
			}
			$select_text .= "</select>";
			
			 
			return $select_text;
	}
	 
}


if(!function_exists('print_statement'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function print_statement($statement)
	{
		  switch(ENVIRONMENT)
		  {
				case 'development':
						 echo $statement;
						break;	
				case 'testing':
						 echo $statement;
						break;
				default:
						break;
		  }
		 
		  
	}
	 
}