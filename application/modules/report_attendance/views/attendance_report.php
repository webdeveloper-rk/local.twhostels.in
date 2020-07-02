<?php
$headtitle = '  Purchase Report  -'.$rdata['month_name']."-".$rdata['report_for']." - ".$item_name;
?><section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> Employee  Attendance Reports  </h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"   id="updateentries">
<?php 

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>

<?php echo $this->session->flashdata("message");?>             
			 <div class="box-body">
				   
				    <?php if($this->session->userdata("is_dco")!="1"){?>		
	 
					 
				  			 <div class="form-group" id="fg_district">
                  <label for="inputEmail3" class="col-sm-2 control-label">District Name:</label>

                  <div class="col-sm-10">
                  <select required name="district_id" id="district_id" class="search form-control" style="width:300px" onchange=" load_json_data(this.value)">
						<option value="">Select District</option>
						<?php 
						$dis_selected = $this->input->post('district_id');
						foreach($districts_list->result() as $districtObj)
						{
							$selected_text = '';
							if($dis_selected == $districtObj->district_id){
								$selected_text = ' selected ';
							}
						 ?>	
						<option <?php echo $selected_text ;?>  value="<?php echo $districtObj->district_id;?>"><?php echo $districtObj->name;?></option>
						<?php } ?>
					</select>
                    
                  </div>
                </div>
					<?php }else if($this->session->userdata("is_dco")==1) { ?>
						<input type="hidden" name="district_id" value="<?php echo $this->session->userdata("district_id");?>" required>
					<?php } ?>
					 
					 
				  			 <div class="form-group"   id="fg_school" >
                  <label for="inputEmail3" class="col-sm-2 control-label">School Name:</label>

                  <div class="col-sm-10">
				  <?php 
				 
				    $sid_selected = $this->input->post('school_id');?>
                  <select name="school_id"  id="schools_id" class="search form-control" style="width:300px" required>
						<option value="">Select School</option>
						<?php 
						$sid_selected = $this->input->post('school_id');
						foreach($schools_list->result() as $schoolObj)
						{
							$selected_text = '';
							if($sid_selected == $schoolObj->school_id){
								$selected_text = ' selected ';
							}
						 ?>	
						<option  <?php echo $selected_text ;?>   value="<?php echo $schoolObj->school_id;?>"><?php echo $schoolObj->school_code;?>-<?php echo $schoolObj->name;?></option>
						<?php } ?>
					</select>
                    
                  </div>
                </div>
				  
				  
						 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Month & Year:</label>

                  <div class="col-sm-10">
                   	<select name="month" required>
						<option value="">Select Month</option>
						<?php 
						   $month_selected = $this->input->post('month'); 
						 	  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
						
						for($lmonth=1;$lmonth<13;$lmonth++)
						{
							$month_name = $months[$lmonth];
							if($lmonth < 10)
									$lmonth = "0".$lmonth;
								
								$selected_text = '';
							if($month_selected == $lmonth){
								$selected_text = ' selected ';
							}
							
							?>
						<option <?php echo $selected_text ;?>   value="<?php echo $lmonth;?>"><?php echo $months[$lmonth];?></option>
						<?php } ?>
					</select>
                   	<select name="year"  required>
						<option value="">Select Year</option>
						<?php 
						 $year_selected = $this->input->post('year'); 
						$sdyear = 2020;
						$pyear = date('Y');
						
						for($lyear=$sdyear;$lyear<=$pyear;$lyear++)
						{
								$selected_text = '';
							if($year_selected == $lyear){
								$selected_text = ' selected ';
							}
							?>
						<option  <?php echo $selected_text ;?>    value="<?php echo $lyear;?>"><?php echo $lyear;?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
				 
           						 
              </div>
			  <div id="changepwdnotifier" style="margin-left:30px;"></div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" name="submit"  value="getreport" class="btn btn-info pull-right">Get Report</button> 
			 
                <!--<button type="submit"  name="submit"  value="download"  class="btn btn-info pull-right">Download</button>-->
              <br><br>
			   <div id="error_div"></div>
			  </div>
              <!-- /.box-footer -->
            </form>
          </div>
		 
		   
	 
 
</section>

<!-- jQuery 1.10.2 -->
 
     
 
  <script>
 
 function load_json_data(district_id)
 {
  var html_code = '';
  console.log(district_id);
  $.getJSON('<?php echo site_url('Report_attendance/getschoolslist');?>/'+district_id, function(data){

   html_code += '<option value="">Select School</option>';
   $.each(data, function(key, value){
     
	// console.log(key);
	 //console.log(value);
      html_code += '<option value="'+value.id+'">'+value.name+'</option>';
     
   });
   $('#schools_id').html(html_code);
  });
 }
  
  
  </script>

  
  	<script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js"></script>


<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js"></script>
<script type="text/javascript">


    $(document).ready(function() {


        $('#updateentries').ajaxForm({beforeSubmit : function(arr, $form, options){
                $("#changepwdnotifier").html("<div class='alert alert-warning'><h4>Fetching please wait...</h4></div>");
                 $("#error_div").html('');


          },dataType: 'json', success: processJson});


        


    });


    function processJson(data) {

		//alert(data);
        if (data.success) {


            $("#changepwdnotifier").html(data.message); 
            $("#error_div").html(data.html_table);


        } else {


           


        }


    }


</script>