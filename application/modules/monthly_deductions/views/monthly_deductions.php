<?php 
$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
	?>
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
</style>
<h3>Mothly Deduction Report </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose month and year to get the report</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Choose Month:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                 <select name="month" id="month"  style="height:30px;width:100px;" >
					<option value="">Select Month</option>
					<?php 
					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
					foreach($months as $key_month =>$month_name) { 
					$selected = '';
					if($key_month == $sel_month){ $selected =  " selected =selected ";}
					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';
					  } ?>
					  </select>
                 
                </div>
                <!-- /.input group -->
              </div>
			       <label>Choose Year  :</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                   
					 <select name="year" id="year" style="height:30px;width:100px;" >
					<option value="">Select Year</option>
					<?php 
					for($i=2017;$i<=date('Y');$i++)
					{ 
							$selected = '';
							if( $i ==  $sel_year){ 
												$selected =  " selected =selected ";
											}
								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';
					}
					?>
					  </select> 
                 
                </div>
                <!-- /.input group -->
              </div>
			  
			 
              <!-- /.form group -->

              <!-- Date range -->
              <div class="box-footer">
                 
                <input type="submit" class="btn btn-info pull-right" value="Get Report" name="submit"> 
				 <br><br>
               <input type="submit" class="btn btn-info pull-right" value="Download Report" name="submit">  
              </div>
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

            </div>
            <!-- /.box-body -->
          </div>
		  </form>
  
  <?php  if($display_result==true){ ?>
  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
	 
	<tr class='bold'><td align="center">Mothly Deductions Report  of  <span class='red'><b><?php echo $months[$sel_month];?> - <?php echo $sel_year ;?> </span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td aria-controls="example1">SLNO</td>
					
					<td aria-controls="example1">School Code</td>
					 <td aria-controls="example1">School Name</td>
					
					 
				</tr>
				<?php $i=1; 
				$total_amount = 0;
				foreach($deduction_rs->result() as   $school_data){ 
							<tr > 
							<td><?php echo $i;?></td> 
							<td><?php echo $school_data->school_code;?></td>
							</tr>
				<?php $i++; } ?>
				 <tr><td colspan="2"></td>
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
  
	 
  <?php } ?>