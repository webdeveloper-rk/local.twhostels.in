  
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
body {
	background-image: none;
}
.w3-btn, .w3-btn:link, .w3-btn:visited {
    color: #FFFFFF;
    background-color: #4CAF50;
	margin:5px;
	padding:10px;
}
</style>
<?php $this->load->view("site_css");?>



 

    <tr>
				
        <td align="center" bgcolor="#FFFFFF" style="margin:10px" align="center"><table width="800px" class='table' width="99%" cellspacing="0" cellpadding="0">
		
		
		
		<tr><th colspan='5' align='center'>Click on school name to get the school consumption report.</th></tr>
		<tr><th>Sno</th><th>School Name </th><th>School Code</th><th colspan="2">Reports</th></tr>
        <?php 
		$rs = $this->db->query("select * from schools where is_school='1'");
		$sno = 0;
			foreach($rs->result() as $row){
				$sno++;
				echo "<tr><td align='center'>".$sno."</td><td><a href='".site_url("dreport/listview/".$row->school_code)."'>".$row->name.	"</a></td><td>".$row->school_code."</td><td><a href='".site_url("dreport/listview/".$row->school_code)."'>Day Report</a></td><td><a href='".site_url("dreport/reports/".$row->school_code)."'>Consolidated Report </a></td></tr>";
			}
		
		
		?>
		

 </table>
 </td></tr> 