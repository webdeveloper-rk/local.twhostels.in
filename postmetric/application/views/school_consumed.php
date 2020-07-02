  <style>.bold td{	font-weight:bold;}.red{	color:#FF0000;}body {	background-image: none;}.w3-btn, .w3-btn:link, .w3-btn:visited {    color: #FFFFFF;    background-color: #4CAF50;	margin:5px;	padding:10px;}</style><?php $this->load->view("site_css");?> 
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">School Daily Consumption Report </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			 
  <div class="form-group">   
                  <label for="inputEmail3" class="col-sm-2 control-label">Date</label>

                  <div  >
                    <?php echo $reportdate;?>
                  </div>
                </div>

  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">School Name</label>

                  <div  ><?php echo $school_info->name;?>
                  </div>
                </div>
	  <div class="form-group">                  <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>                  <div  ><?php echo $school_info->school_code;?>                  </div>                </div>			
			    <div class="form-group">
                  

                  <div  >
                     
					 <table class="table table-bordered table-striped dataTable no-footer ">
					 <tr ><td class='bold'>&nbsp;</td><td class='bold'>Category 1(5,6,7) </td><td class='bold'>Category 2(8,9,10) </td><td class='bold'>Category 3(Intermediate) </td><td class='bold'>Total</td></tr>
					 
					 <tr  ><td class='bold'>Per Day Price</td><td><?php echo $student_prices['gp_5_7']; ?> / <?php echo $days_count;?> = <?php echo $group_1_perday_price;?></td><td><?php echo $student_prices['gp_8_10']; ?> / <?php echo $days_count;?> = <?php echo $group_2_perday_price;?></td><td><?php echo $student_prices['gp_inter']; ?> / <?php echo $days_count;?> = <?php echo $group_3_perday_price;?></td><td></td></tr>
					<tr ><td class='bold'>Attendance</td><td><?php echo $group_1_attendence; ?> </td><td><?php echo $group_2_attendence; ?>  </td><td><?php echo $group_3_attendence; ?> </td><td class='bold'><?php echo $attendence;?></td></tr>
					 <tr ><td class='bold'>Price</td><td><?php echo $group_1_price; ?> </td><td><?php echo $group_2_price; ?>  </td><td><?php echo $group_3_price; ?> </td><td class='bold'><?php echo  $today_allowed_Amount;?></td></tr>
					 </table>
                  </div>
                </div>
				<!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Per Student Amount</label>

                  <div  >
                    <?php echo $per_stundent;?>
                  </div>
                </div>-->
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Allowed Amount</label>

                  <div  >
                    <?php  echo $today_allowed_Amount;?>
                  </div>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Consumed Amount</label>

                  <div  >
                    <?php  echo $today_consumed_Amount;?>
                  </div>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Remaining Amount</label>

                  <div  >
                   <?php  echo $today_remaining_Amount;?><br><br>
                  </div>
                </div>
				 <a  class='w3-btn' href='<?php echo site_url('dreport');?>'>Go Back</a><br><br>
          </div>
 
</section> 

<!-- jQuery 1.10.2 -->
  