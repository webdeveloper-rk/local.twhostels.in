<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ehostelids extends CI_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		$this->load->library("CI_Jwt"); 		$this->load->library("excel");         // Configure limits on our controller methods    }
 
	public function index()
	{		$file = 'assets/uploads/premetric_schools.xls';									$objPHPExcel = PHPExcel_IOFactory::load($file);									 									//get only the Cell Collection									$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();									 									//extract to a PHP readable array format									foreach ($cell_collection as $cell) {										$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();										$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();										$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();									 										//header will/should be in row 1 only. of course this can be modified to suit your need.										if ($row == 1) {											$header[$row][$column] = $data_value;										} else {											$arr_data[$row][$column] = $data_value;										} 									}								//print_a($arr_data,1);							$queries = array();						foreach($arr_data as $key=>$obj)						{							 $ehostel_id = $obj['D'];							 $ddo_code = $obj['F'];							 $this->db->query("update schools set ehostel_id=? where school_code=?",array($ehostel_id,$ddo_code));							echo "<br>",$this->db->last_query(),"<br>";						}						echo "Done";					 	} 
}
