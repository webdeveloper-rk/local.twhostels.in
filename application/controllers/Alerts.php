<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alerts extends CI_Controller {

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
			 
	}
	function rice()
	{
		$username = "apswreis";
		$password = "ramaravividya";
		    $sql ="SELECT sc.school_id,name,school_code,sc.district_id,closing_quantity,dc.contact_mobile FROM `balance_sheet` bs inner join schools sc on bs.school_id=sc.school_id inner join dco_contacts dc on dc.district_id = sc.district_id  WHERE item_id=1 and entry_date=CURDATE() and closing_quantity < 1000";
		$rs = $this->db->query($sql);
		foreach($rs->result() as  $row)
		{
			$mobiles = array();
			$message  = $row->school_code."-".$row->name ."-RICE- has low stock ".  number_format($row->closing_quantity,0,"","") ." Kg's";
			$mobiles[]=   $row->contact_mobile ;
			
			//$mobiles[]=   8500089333; 
			$mobiles[]=   9701984455;//vidya rani
			$mobiles[]=    9989334895;//Mohan rao 
			
			if( $row->closing_quantity <= 500)
			{
				$mobiles[]=    9989334895;//secretary Mobile Number  
			}
			
			//$mobiles = array(8500089333);
			$mobiles_list = implode(",",$mobiles);
			 
			
			$url = "http://182.18.163.39/spanelv2/api.php?username=$username&password=$password&to=$mobiles_list&from=APSWRE&message=".urlencode($message);    //Store data into URL variable
			 
				$ret = file($url);  
				//echo "--<br>",$ret;    //$ret stores the msg-id
			
		}
		
		
	}
}
