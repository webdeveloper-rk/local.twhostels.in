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
              <h3 class="box-title"><b>Monthly Attendance</b></h3>
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
				 <b>Choose month and year : </b>
					<select name="month" id="month" required >
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
					 
					  
					 <select name="year" id="year" required>
					<option value="">Select Year</option>
					<?php 
					for($i=date('Y');$i>=2019;$i--)
					{ 
							$selected = '';
							if( $i == $year){ 
												$selected =  " selected =selected ";
											}
								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';
					}
					?>
					  </select><input type="submit" value= "Get Attendance "  > 
					  </td> </tr></table>
				 </div>
				 </form>
				 