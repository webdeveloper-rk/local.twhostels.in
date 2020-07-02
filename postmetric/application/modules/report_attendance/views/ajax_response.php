<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 60%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
<div class="box-body">
				   <div><h3><b>School name : </b><?php echo $school_name;?></h3></div>
				<table width="60%" id="customers">
					<thead>
					<tr  >
						<th   > <b>Employee Name</b></th>
						<th><b>Days in month</b></th>
						<th><b>Present Days</b></th>
						<th><b>Leaves</b></th>
					</tr>
					</thead>
			   
				
				 <?php foreach($attendence_rs->result() as $row)
				 {?>
				 	<tr>
							<td><b><?php echo strtoupper($row->fullname);?></b></td>
							<td><b><?php echo $row->total_days; ?></b></td>
							<td><b><?php echo $row->present_days; ?></b></td>
							<td><b><?php echo $row->leaves; ?></b></td>
							 
					</tr>

				 <?php 
				 }
				 
				 if($attendence_rs->num_rows()==0)
				 {
					echo "<h1>No Employee Found.</h1>";
					return '';
				 }
				 ?>
				
				  </table>

				 				
				 
               </div>