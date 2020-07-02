<style>
.alert-success
{
	 color: #FFF;
    background-color: #4CAF50;
    border-color: #ebccd1;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.bold{
	font-weight:bold;
}

</style> 

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Monthly Consumed</b></h3>
            </div>
            <!-- /.box-header -->
 <?php 
			 
			 
			 $errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors" style="padding:10px;"><?php echo $errors; ?>  </div>
<?php } ?>
			 <?php 
echo $this->session->flashdata('message');


?><form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
			 
              <div class="box-body">
			  
			  			  <div  >

				 <?php  if(in_array($this->session->userdata("user_role"),array("subadmin","secretary"))){ ?>

				 <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>



                  <div class="col-sm-10">
							<?php echo school_selection($this->input->post("school_id"));?>
				  
				  	  

                    </select>

				  

				 

                  </div>

				  <?php } ?>

                </div>
				<br><br>
				 
                 <div>
				 <table><tr>
					<td style="margin-left:15px;">
				 <b>Choose Date : </b>
					<select name="month" id="month" >
					<option value="">Select Month</option>
					<?php 
					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
					foreach($months as $key_month =>$month_name) { 
					$selected = '';
					if($key_month == $month){ $selected =  " selected =selected ";}
					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';
					  } ?>
					  </select></td><td>
					 
					  
					 <select name="year" id="year" >
					<option value="">Select Year</option>
					<?php 
					for($i=2017;$i<=date('Y');$i++)
					{ 
							$selected = '';
							if( $i == $year){ 
												$selected =  " selected =selected ";
											}
								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';
					}
					?>
					  </select><input type="submit" value= "Get Report"  > 
					  </td> </tr></table>
				 </div>
				 </form>
				 <?php if($result_flag ==true) { ?>
				  <div class="form-group">
                <label> </label>
				&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;<h1 style="color:#FF0000;margin-left:30px;"><?php echo $school_info->school_code;?>-<?php echo $school_info->name;?></h1>
                <div class="input-group date">
                  <table class="table table-bordered table-striped dataTable no-footer">
					<tr>
					  <td>&nbsp;</td>
					  <td class='bold'>Category 1(upto 7th)</td>
					  <td class='bold'>Category 2(8,9,10)</td>
					  <td class='bold'>Category 3(Intermediate and above)</td>
					  <td class='bold'>Grand Total</td>
					</tr>
					
					<tr>
					  <td class='bold'>Per Day</td>
					  <td  ><?php echo $student_prices['gp_5_7']; ?>  / <?php echo $days_count;?> = <?php echo number_format($group_1_per_day,4);?></td>
					  <td ><?php echo $student_prices['gp_8_10']; ?>  / <?php echo $days_count;?> = <?php echo number_format($group_2_per_day,4);?></td>
					  <td ><?php echo $student_prices['gp_inter']; ?>  / <?php echo $days_count;?> = <?php echo number_format($group_3_per_day,4);?></td>
					 
					  <td class='bold'> </td>
					</tr>
					<tr>
					  <td class="bold">Attendance</td>
					  <td ><?php echo $group_1_attendence;?></td>
					  <td ><?php echo $group_2_attendence;?></td>
					  <td ><?php echo $group_3_attendence;?></td>
					  <td class='bold'><?php echo $attendence;?></td>
					</tr>
					
					<tr>
					  <td class="bold">Total</td>
					  <td ><?php echo number_format($group_1_price,4);?></td>
					  <td ><?php echo number_format($group_2_price,4);?></td>
					  <td ><?php echo number_format($group_3_price,4);?></td>
					  <td class='bold'><?php echo number_format($allowed_amount,2);?></td>
					</tr>
				  </table>
              </div>
			  </div>
				
<div style='padding-left:30px;'>				
				    <!-- Date -->
              <div class="form-group">
                <label>Total Purchased:</label>

                <div class="input-group date">
                 <?php echo number_format($purchased_amount,2);?>
              </div>
			  </div> <div class="form-group">
                <label>Total Consumed:</label>

                <div class="input-group date">
                 <?php echo number_format($consumed_amount,2);?>
              </div>
			  </div>
			  
			   <div class="form-group">
                <label>Allowed Amount</label>

                <div class="input-group date">
                  <?php echo number_format($allowed_amount,2);?>
              </div>
			  </div>
			   <div class="form-group">
                <label>Remaing Balance</label>

                <div class="input-group date">
                 <?php echo number_format($balance,2);?>
              </div>
			  </div>
             
            </div>
			 
			
          </div> 
				 <?php } ?>
		  
		 