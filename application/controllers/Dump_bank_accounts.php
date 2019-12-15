<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dump_bank_accounts extends CI_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		$this->load->library("CI_Jwt"); 		$this->load->library("excel");         // Configure limits on our controller methods    }
 
	public function index()
	{		$file = 'assets/uploads/dtdo_banks.xls';									$objPHPExcel = PHPExcel_IOFactory::load($file);									 									//get only the Cell Collection									$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();									 									//extract to a PHP readable array format									foreach ($cell_collection as $cell) {										$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();										$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();										$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();									 										//header will/should be in row 1 only. of course this can be modified to suit your need.										if ($row == 1) {											$header[$row][$column] = $data_value;										} else {											$arr_data[$row][$column] = $data_value;										} 									}								//	print_a($arr_data,1);						$queries = array();						$queries_executed = 0;						foreach($arr_data as $key=>$obj)						{								//print_a($obj,1);							$school_ddo_code = $obj['F']; //ddo code from excel sheet 							$bank_ifsc = $obj['J'];//ifsc from excel sheet 							$bank_acc_number  = $obj['K'];//account number from excel sheet 							$vendor_name  = $obj['E'];//vendor name  from excel sheet 							  $schools_rs= $this->db->query("select * from schools where school_code=?",array($school_ddo_code));							  //echo $this->db->last_query();die;							  if($schools_rs->num_rows()>0)							  {									$school_info = $schools_rs->row();									//print_a($school_info,1);									$school_id = $school_info->school_id;									$vendor_rs = $this->db->query("select * from tw_vendors where school_id=? and vendor_bank_ifsc=? and vendor_account_number=? ",array($school_id,$bank_ifsc,$bank_acc_number));									if($vendor_rs->num_rows()==0)									{																													$ehostel_id = 0;											$sc_rs = $this->db->query("select * from schools where school_id=?",array($school_id));											$sc_info = $sc_rs->row();											$ehostel_id = intval($sc_info->ehostel_id);																						$ehostel_vendor_id = $this->db->query("select max(ehostel_vendor_id) as ehostel_vendor_id from tw_vendors")->row()->ehostel_vendor_id; 										$ehostel_vendor_id = $ehostel_vendor_id + 1;									/****************************************************************************/											$ins_data['school_id'] = $school_id;											$ins_data['vendor_type'] = 'local';											$ins_data['vendor_name'] = $vendor_name;											$ins_data['ehostel_id'] = $ehostel_id;											$ins_data['ehostel_vendor_id'] = $ehostel_vendor_id;											 											 											$ins_data['vendor_bank'] = $obj['H']; 											$ins_data['vendor_bank_branch'] = $obj['I']; 											$ins_data['vendor_bank_ifsc'] = $obj['J'];	 											$ins_data['vendor_account_number'] = $obj['K'];	 											$ins_data['is_dtdo_account'] = '1';										 											$this->db->insert("tw_vendors",$ins_data);										//	echo $this->db->last_query();die;									/****************************************************************************/																											$queries_executed++;									}																  }else							  {														  }							 													}						echo "Executed : ".$queries_executed;						 	} 
}
