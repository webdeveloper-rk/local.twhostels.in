 
<style>
  .bold{
	font-weight:bold
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">School Attendance Report </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <?php 		$errors = validation_errors();if($errors !=""){?> <div class="validation_errors"><?php echo $errors; ?>  </div><?php } echo $this->session->flashdata('message');?>	 			 
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

			  <div class="form-group">
				 
				 <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>

                  <div class="col-sm-10">  <?php echo  school_selection($school_id);?>
                  </div>
				  
                </div>
				 
				 <div class="form-group">
				 <table  style="margin-left:75px;"><tr>
					<td style="margin-left:175px;">
				 <b>Choose Month and year : </b>
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
					  </select> 
					  </td> </tr></table>
				 </div>
				 
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Get Report</button>
              </div>
              <!-- /.box-footer -->
            </form>
			 
 
 
				
			    
			 
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 
    function validate(form) {
        
	 
    }
</script>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '01-01-2017',
			endDate: '+0d'});
  } );
  </script>

