<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendence extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		 
		$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
		$webservice_url_connected = true;
		$class_actual_names = array();//kk
		libxml_disable_entity_loader(false); 
		
		 $this->load->library('webservices_lib');
		  
		 $cat1_list = array('class3','class4','class5','class6','class7');
		 $cat2_list = array('class8','class9','class10');
		 $cat3_list = array('mpc1year','mpc2year','bipc1year','bipc2year','cec1year','cec2year','hec1year','hec2year','ct1year','ct2year','aandt1year','aandt1year','aandt2year','mec1year','mec2year','mlt1year','mlt2year','cga1year','cga2year','emcetiitmpc1year','emcetiitmpc2year','emcetbipc1year','emcetbipc2year','iitmpc2year','iitmpc1year');
		 
		 
		 
		 $all_cat_list = array_merge($cat1_list,$cat2_list,$cat3_list);
		 $cat1_total = 0;
		 $cat2_total = 0;
		 $cat3_total = 0;
		 $date = '';
		 if(isset( $_GET['date'])){
			$date = $_GET['date'];
		 }
		if($date=="")
		{
			$date = date('m-d-Y');
		} 
		$date_split = explode("-",$date);
		
		
		$db_date  = $date_split[2]."-".$date_split[0]."-".$date_split[1];
		 
		 
		 $this->db->query("insert into school_attendence(school_id ,  entry_date)
								select school_id ,'$db_date' as entry_date from schools where school_id not in
												(select school_id  from school_attendence where entry_date='$db_date') ");
												
												
		 echo "Done";
	}
	
	
}