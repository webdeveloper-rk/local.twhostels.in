<?php
//print_a($emp_info);
?>
<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
<?php
 $posts_rs = $this->db->query("select * from posts ");
 $districts_rs1 =  $districts_rs2   = $this->db->query("select * from districts ");
	
?>
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
.new{
	display:none;
}
.recal
{
	font-weight:bold;
	color:#2C8C02;
}

.box.box-primary {
    border-top-color: #3c8dbc;
    padding: 10px;
}
</style>
<?php echo $this->session->flashdata('message');?>
	 
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b><?php echo  strtoupper($process_type);?></b> -<span style='color:#FF0000;font-weight:bold;'>Regular</span>  Empolyee Info  <b> </b></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
	 
         <?php   $attributes = array('class' => 'email', 'id' => 'myform',
		 'action'=>site_url('employees/updateemployee/'.$process_type."/".$school_id."/".$emp_id));
//echo form_open('', $attributes); 	
 
 
?>

   <form    class="email" id="myform" action="<?php echo site_url('employees/updateemployee/'.$process_type."/".$school_id."/".$emp_id);?>" method="post" accept-charset="utf-8">
   <input type="hidden" name="param[employee_type]" value="regular">
<!-- <div class="validation_errors"><?php echo @$errors; ?>  </div>-->
 <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
			
			
			
			
			 <div >
                <div class="box-header">
                  <h3>Employee Details</h3>
                </div>
                <div class="box-body">
                  <div class="row">
				  
				  <div  >
					  <label>School Name *</label>
                      <?php if($process_type=="add") { 
						echo school_selection2(0,"onchange='load_json_data(this.value)'");
					  }
						elseif($process_type=="edit")
						{
							echo "<b>".$school_name."</b>";
							?>
							<input type="hidden" name="school_id" value="<?php echo $emp_info->school_id;?>" required>
							<?php 
						}
					  ?>
					  <br><br> 
                    </div>
					
					
                    <div class="col-xs-6">
					  <label>Full Name as per 10th Certificate *</label>
                      <input type="text" name="param[fullname]" value="<?php echo @$emp_info->fullname;?>" required class="form-control" placeholder="Full Name">
                    </div>
                   
                   <div class="col-xs-6">
					  <label>Father Name as per 10th Certificate *</label>
                      <input type="text" name="param[fathername]"  value="<?php echo @$emp_info->fathername;?>" required class="form-control" placeholder="Father Name">
                    </div>
					
					 <div class="col-xs-6">
					  <label>Mobile No *</label>
                      <input type="text" maxlength="11" class="form-control number" name="param[mobile]" value="<?php echo @$emp_info->mobile;?>" required  placeholder="Enter 10 digit Mobile No"/>
                    </div>
					
					
					 <div class="col-xs-6">
					  <label>Treasury Code/DDO Code *</label>
                      <input type="text" class="form-control" name="param[ddocode]" value="<?php echo @$emp_info->ddocode;?>" required  placeholder="Treasury Code/DDO Code ..."/>
                    </div>
					
					
					
					 <div class="col-xs-6">
					   <label>Date of Birth(As per 10th Class Certificate)*</label>
                      <input type="text" class="datepicker  form-control" name="param[dob]"  value="<?php echo @$emp_info->dob;?>"  required    value="" id="dob" placeholder="Select Date"  > 
                    </div>
					
					
					
					
					 <div class="col-xs-6">
					 <label for="exampleInputEmail1">Email address *</label>
                      <input type="email" class="form-control" required name="param[email]" value="<?php echo @$emp_info->email;?>" id="exampleInputEmail1" placeholder="Enter email">
                    </div>
					
					
					
					<div class="col-xs-6">
					 <label>Present Working School *</label>
                      <input type="text" class="form-control" name="param[p_workingschool]" value="<?php echo @$emp_info->p_workingschool;?>" required  placeholder="Present Working School"/>
                    </div>
					
					
					<div class="col-xs-6">
					 <label>Present School Joining Date *</label>
                      <input type="text" class="datepicker  form-control" value="<?php echo @$emp_info->p_schooljoining_date;?>" name="param[p_schooljoining_date]" required    value="" id="p_schooljoining_date" placeholder="Select Date"   > 
                    </div>
					
					
					
					<div class="col-xs-6">
					<label>Designation *</label>
                      <select class="form-control" name="param[post_id]" id="post_id" required  >
                        <option value="" selected="selected" disabled="disabled">-- select one --</option>
									 
					</select>
	</div>
					
					
					
					<div class="col-xs-6">
					<label>Gender *</label>
                      <select class="form-control" name="param[gender]"  id="gender" required >
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Transgender">Transgender</option>
                        
                      </select>
					
					</div>
					
					
					<div class="col-xs-6">
					 <label>Aadhar No*</label>
                      <input type="text" class="form-control number" name="param[aadhar]" value="<?php echo @$emp_info->aadhar;?>" required  placeholder="Enter 12 digit Aadhar No"/>
					
					</div>
					
					
					<div class="col-xs-6">
					<label>Pan card No*</label>
                      <input type="text" class="form-control" name="param[pancard]"  value="<?php echo @$emp_info->pancard;?>" placeholder="Pan card No"/>
					
					</div>
					
					
					
					
				<div class="col-xs-6">
				<label>Community</label>
                     <select class="form-control" name="param[community]"  id="community">
                        <option value="" selected="selected" disabled="disabled">-- select one --</option>
    <option value="Un Reserved">Un Reserved</option>
    <option value="OBC">OBC</option>
    <option value="SC">SC</option>
    <option value="ST">ST</option>
   
                        
                      </select>
				</div>
				
				
		
		 <div class="col-xs-6">
                      <label>Are you a Person with Disability * : </label>
                     	 
					<select class="form-control" name="param[disability]" id="disability" required >
                        <option value="" selected="selected" disabled="disabled">-- select one --</option>
   
    <option value="Yes">Yes</option>
    <option value="No">No</option>
   
                        
                      </select>
						   
                           </div>
					
					
					

                    <div class="col-xs-6">
                      <label>Type of Disability:</label>
                      <select class="form-control" name="param[type_disability]" id="type_disability">
								<option value="Blindness">Blindness</option>
								<option value="Low Vision">Low Vision</option>
								<option value="Leprosoy Cured persons">Leprosoy Cured persons</option>
								<option value="Locomotor Disability">Locomotor Disability</option>
								<option value="Dwarfism">Dwarfism</option>
								<option value="Intellectual Disability">Intellectual Disability</option>
								<option value="Mental Illness">Mental Illness</option>
								<option value="Cerebral Palsy">Cerebral Palsy</option>
								<option value="Specific Learning Disabilities">Specific Learning Disabilities</option>
								<option value="Speech and Language disability">Speech and Language disability</option>
								<option value="Hearing Impairment ( Deaf and Hard of Hearing)">Hearing Impairment ( Deaf and Hard of Hearing)</option>
								<option value="Muscular Dystrophy">Muscular Dystrophy</option>
								<option value="Acid Attack Victim">Acid Attack Victim</option>
								<option value="Parkinsons disease">Parkinsons disease</option>
								<option value="Multiple Sclerosis">Multiple Sclerosis</option>
								<option value="Thalassemia">Thalassemia</option>
								<option value="Hemophilia">Hemophilia</option>
								<option value="Sickle Cell disease">Sickle Cell disease</option>
								<option value="Autism Spectrum Disorder">Autism Spectrum Disorder</option>
								<option value="Chronic Neurological conditions">Chronic Neurological conditions</option>
								<option value="Multiple Disabilities including Deaf Blindness">Multiple Disabilities including Deaf Blindness</option>
                      
                        
                      </select>
                    </div>
					
					
					 <div class="col-xs-6">
                      <label>Percentage of Disability</label>
                      <input type="text" name="param[percentage_disability]" value="<?php echo @$emp_info->percentage_disability;?>" class="form-control number" max="100" placeholder="Enter from 1-100% Percentage"/>
                    </div>
		
			</div>
				   
   <h3>A. Present Residential Address</h3>				  
				
             
                
                  <div class="row">
				 <div class="col-xs-12">
                      <label>Door/House No, Street Name, Mandal, Revenue Village *</label>
                      <textarea class="form-control" name="param[present_address]"   required  rows="3" placeholder="Door/House No, Street Name, Mandal, Revenue Village ..."><?php echo @$emp_info->present_address;?></textarea>
                    </div>
					
				 <div class="col-xs-6">
                      <label>Select District *</label>
                      <select   name="param[present_district]" required  id="present_district" class="form-control">
					  <option value="">Please select</option>
                         <?php foreach($districts_rs1->result() as $trow){ ?>
									<option value="<?php echo $trow->district_id;?>"><?php echo $trow->name;?></option>
									<?php } ?>
                      </select>
					</div>
					
					
					
					
					<div class="col-xs-6">
                      <label>Pincode *</label>
                      <input type="text" name="param[present_pincode]" value="<?php echo @$emp_info->present_pincode;?>" required  class="form-control number" placeholder="Pincode"/>
                    </div>
				 
				 
				 </div>
				 
				 
				 
				 
				 
				 <br />
				 
				<div class="form-group">
<label>If Present Address Is Same
as Permanent Address:* </label>
                     	 [
					
                        <label>Yes </label>
                          <input type="radio" name="param[present_address_same_as_permenent]" <?php if($emp_info->present_address_same_as_permenent=="yes") { echo " checked "; } ?> id="optionsRadios1" value="yes"  >
                                               
						   <label>No </label>
						  					  
                          <input type="radio" name="param[present_address_same_as_permenent]" <?php if($emp_info->present_address_same_as_permenent=="no") { echo " checked "; } ?>  id="optionsRadios2" value="no">]
						   <label>(If select No) </label>
                           </div>
				 
		
				 
				 
				 <h3 class="box-title"> B. Permanent Residential Address</h3>
				 
				 <div class="row">
				 <div class="col-xs-12">
                      <label>Door/House No, Street Name, Mandal, Revenue Village *</label>
                      <textarea class="form-control" name="param[permanent_address]"     required  rows="3" placeholder="Door/House No, Street Name, Mandal, Revenue Village ..."><?php echo @$emp_info->permanent_address;?></textarea>
                    </div>
					
				 <div class="col-xs-6">
                      <label>Select District *</label>
                      <select   name="param[permanent_district]" id="permanent_district" required  class="form-control">
					  <option value="">Please select</option>
                        <?php foreach($districts_rs2->result() as $trow){ ?>
						 
									<option value="<?php echo $trow->district_id;?>"><?php echo $trow->name;?></option>
									<?php } ?>
                      </select>
					</div>
					
					
					
					
					<div class="col-xs-6">
                      <label>Pincode *</label>
                      <input type="text" name="param[permanent_pincode]"   value="<?php echo @$emp_info->permanent_pincode;?>"  required  class="form-control number" placeholder="Pincode"/>
                    </div>
						
						</div>
						
						
					</div>
                
             
			  
			 

              <!-- Input addon -->
             
              </div><!-- /.box -->

            </div><!--/.col (left) -->
            
			
			
			
			
			
			
			
			
			
			
			
			
			<!-- right column -->
            <div class="col-md-6">
              <!-- general form elements disabled -->
              <div >
                <div class="box-header">
                 <h3>Details of First Appointment</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
               
                   
				  <div class="row">
				   
				 <div class="col-md-6">
                      <label>DSC/APPSC Selected Year *</label>
                      <input type="text" name="param[appsc_year]"  value="<?php echo @$emp_info->appsc_year;?>" required  class="form-control number" placeholder="DSC/APPSC Selected Year"/>
                    </div>  
					 <div class="col-md-6">
				    <label>Local/Non-Local</label>
                      <select class="form-control" name="param[local_nonlocal]" id="local_nonlocal" >
                        <option value="local"> Local</option>
                        <option value="Non-Local"> Non-Local</option>
                        
					 </select></div>
								   
				   <div class="col-md-6">
					  <label>Date of first appointment *</label>
                      <input class="datepicker form-control input-sm" name="param[first_app_date]" value="<?php echo @$emp_info->first_app_date;?>"  required  type="text" placeholder="Date">
                    </div>
				   
				  <div class="col-md-6">
                      <label>Category of the post *:</label>
                      <input type="text" class="form-control" name="param[catogory_post]" value="<?php echo @$emp_info->catogory_post;?>" required  placeholder="Category of the post"/>
                    </div>
					
					  <div class="col-md-6">
                      <label>Appointing authority * DSC</label>
                      <input type="text" class="form-control" name="param[appointing_authority]" value="<?php echo @$emp_info->appointing_authority;?>"  required  placeholder="Appointing authority"/>
                    </div>
					
					  <div class="col-md-6">
                      <label>Mandal, Revenue Village *:</label>
                      <input type="text" class="form-control" name="param[mandal_rev_village]" value="<?php echo @$emp_info->mandal_rev_village;?>" required  placeholder="Mandal, Revenue Village"/>
                    </div>
					
					 
					 
				   
				    
											  
							</div>				  
        						<br />			 
											 
											 
											 
											 <div class="form-group">
                     <label>Are You Selected in another DSC Under Lein *</label>
					   <label>Yes </label>
                          <input type="radio" name="param[selected_another_dsc]" <?php if($emp_info->selected_another_dsc=="yes") { echo " checked "; } ?>  id="optionsRadios1" value="yes"   >
						   <label>No </label>
						  	 
                          <input type="radio" name="param[selected_another_dsc]" <?php if($emp_info->selected_another_dsc=="no") { echo " checked "; } ?> id="optionsRadios2" value="no">
                           </div>
				   
				   
				  
				   
				   
				    <h3>Departmental Test Details</h3>
				   
				   
				   <div class="row">
				    <div class="col-md-12">
                     <label>Have you passed any Departmental Test</label>
					   <label>Yes </label>
                          <input type="radio" name="param[department_test_passed]" <?php if($emp_info->department_test_passed=="yes") { echo " checked "; } ?> id="optionsRadios1" value="yes"  >
						   <label>No </label>
						  	 
                          <input type="radio" name="param[department_test_passed]" id="optionsRadios2" value="no" <?php if($emp_info->department_test_passed=="no") { echo " checked "; } ?>>
                           </div>
				   				   
				   
				 <div class="col-md-2">
                      <label>No.of Test*:</label>
                      <input type="text" name="param[no_test]" value="<?php echo @$emp_info->no_test;?>"  required  class="form-control number" placeholder="No"/>
                    </div>
					
					<div class="col-md-10">
                      <label>Details:</label>
                      <textarea class="form-control" name="param[test_details]"   rows="3" placeholder="Details ..."><?php echo @$emp_info->test_details;?></textarea>
                    </div>
				   </div>
				   
				    <h3>Transfer Details</h3>
				   
				    <div class="row">
				   
				    <div class="col-md-12">
                     <label>District/State Transfer Details (Yes/No)*</label>
					 <label> If yes fill the fallowing details</label>
					   <label>Yes </label>
					    
                          <input type="radio" name="param[districts_transfers]" <?php if($emp_info->districts_transfers=="yes") { echo " checked "; } ?> id="optionsRadios1" value="yes"  >
						   <label>No </label>
						  	 
                          <input type="radio" name="param[districts_transfers]" id="optionsRadios2" value="no" <?php if($emp_info->districts_transfers=="no") { echo " checked "; } ?>>
                           </div>
				   
				   
				   
				   
				  
					
					  <div class="col-md-6">
                      <label>Teachers affected by (Inter-district/G.O 610)*</label>
                      <input type="text" name="param[teachers_effectedby]"  value="<?php echo @$emp_info->teachers_effectedby;?>"  required  class="form-control" placeholder="Enter Details"/>
                    </div>
					
					  <div class="col-md-6">
                      <label>Date of Joining in Present District</label>
                     <input type="text" class="datepicker  form-control" name="param[joining_date]" value="<?php echo @$emp_info->joining_date;?>" placeholder=" Date of Joining in Present District" > 
                    </div>
					
					<div class="col-md-6">
                      <label>District/State Transfer Details</label>
                      <input type="text" name="param[state_transfer_details]"  value="<?php echo @$emp_info->state_transfer_details;?>" class="form-control" placeholder="District/State Transfer Details"/>
                    </div>
				   
				    <div class="col-md-6">
                      <label>Category of the Post*</label>
                      <input type="text" class="form-control" name="param[catogory_post]" value="<?php echo @$emp_info->catogory_post;?>"  required  placeholder="Category of the Post"/>
                    </div>
					
					 <div class="col-md-6">
                      <label>Medium*</label>
                      <input type="text" name="param[medium_select]" value="<?php echo @$emp_info->medium_select;?>"  required class="form-control" placeholder="Medium"/>
                    </div>
					
					 <div class="col-md-6">
                      <label>Subject*</label>
                      <input type="text" name="param[subject_select]" value="<?php echo @$emp_info->subject_select;?>" required  class="form-control" placeholder="Subject"/>
                    </div>
					
					</div>
				   
				   <h3>General Transfer Details</h3> 
				   
				   
				    <div class="form-group">
                     <label>No.of Transfers (Date of First Appointment to Present)*</label>
					   <label>Yes </label>
                          <input type="radio" name="param[no_of_transfers]" id="optionsRadios1" value="yes" <?php if($emp_info->no_of_transfers=="yes") { echo " checked "; } ?> >
						   <label>No </label>
						  	 
                          <input type="radio" name="param[no_of_transfers]" id="optionsRadios2" value="no" <?php if($emp_info->no_of_transfers=="no") { echo " checked "; } ?> >
                           </div>
				   
				   
				   
				   
				 <h3>Educational Qualification </h3>   
				   
				    
				  
				  
				     <label style="color:#000099;">C. Details of Degree or Equivalent Examinations Passed </label>
				   
				   
			<div class="row"><br />
			
                    <div class="col-xs-3">
					 
                     <input class="form-control input-sm" name="param[degree_name_board]" value="<?php echo @$emp_info->degree_name_board;?>" type="text" placeholder="Name of the Board">
                    </div>
					
					<div class="col-xs-3">
					 
                       <input class="form-control input-sm" type="text" name="param[degree_medium]" value="<?php echo @$emp_info->degree_medium;?>" placeholder="Medium">
                    </div>
					
					<div class="col-xs-3">
					 
                      <input class="form-control input-sm" type="text" name="param[degree_second_launguage]"  value="<?php echo @$emp_info->degree_second_launguage;?>" placeholder="Second Language">
                    </div>
					
					
					<div class="col-xs-3">
					 <input class="form-control input-sm" type="text" name="param[second_language_option1]"  value="<?php echo @$emp_info->second_language_option1;?>"  placeholder="Option 1">
					 <input class="form-control input-sm" type="text" name="param[second_language_option2]"  value="<?php echo @$emp_info->second_language_option2;?>"  placeholder="Option 2 *">
					 <input class="form-control input-sm" type="text" name="param[second_language_option3]"  value="<?php echo @$emp_info->second_language_option3;?>"  placeholder="Option 3 *">
                     
                    </div>
					
				
					
					<div class="col-xs-3">
					
                      <input class="form-control input-sm" type="text" name="param[degree_month_year]"  value="<?php echo @$emp_info->degree_month_year;?>"  required  placeholder="Passed Month / Year *">
                    </div>
					
					<div class="col-xs-3">
					 
                      <input class="form-control input-sm  number" type="text" name="param[degree_marks]" value="<?php echo @$emp_info->degree_marks;?>"  required  placeholder="Marks Secured *">
                    </div>
					
					<div class="col-xs-3">
					 
                      <input class="form-control input-sm number" type="text" name="param[degree_max_marks]"  value="<?php echo @$emp_info->degree_max_marks;?>" placeholder="Maximum Marks">
                    </div>
					
					<div class="col-xs-3">
					 
                      <input class="form-control input-sm" type="text" name="param[degree_hallticket]" value="<?php echo @$emp_info->degree_hallticket;?>" placeholder="Hall Ticket No">
                    </div>
					
					<div class="col-xs-4">
					
                      <input class="form-control input-sm" type="text" name="param[degree_university]" value="<?php echo @$emp_info->degree_university;?>"  placeholder="Name of the University*">
                    </div>
                  </div>
				  
				  
				
				  <hr />
				   
				    <label style="color:#000099;">D. Details of Post Graduate Degree or Equivalent Examinations Passed</label>
				   
			<div class="row">
                    <div class="col-xs-3">
					 
                      <input class="form-control input-sm" type="text" name="param[pg_name_degree]" value="<?php echo @$emp_info->pg_name_degree;?>"  required  placeholder="Name of the Degree *">
                    </div>
					
					<div class="col-xs-3">
					 
                       <input class="form-control input-sm" type="text" name="param[pg_subject]"  value="<?php echo @$emp_info->pg_subject;?>"  required  placeholder="Subject *">
                    </div>
					
					
					
					<div class="col-xs-3">
					
                      <input class="form-control input-sm" type="text" name="param[pg_passed_month]"  value="<?php echo @$emp_info->pg_passed_month;?>"  required  placeholder="Passed Month / Year *">
                    </div>
					
					<div class="col-xs-3">
					 
                      <input class="form-control input-sm number" type="text" name="param[pg_marks]"  value="<?php echo @$emp_info->pg_marks;?>" placeholder="Marks Secured">
                    </div>
					
					
					
					<div class="col-xs-6">
					
                      <input class="form-control input-sm" type="text" name="param[pg_university_name]"  value="<?php echo @$emp_info->pg_university_name;?>"  placeholder="Name of the University *">
                    </div>
                  </div>	   
				   
				   
				    
				   
				  <hr /> 
				   
				<label style="color:#000099;">E. Details of B.Ed/B.P.ED or similar Examination Passed</label>   
				   <div class="row">
                    <div class="col-xs-4">
					 
                      <input class="form-control input-sm" type="text" name="param[bed_professional_graduation]"  value="<?php echo @$emp_info->bed_professional_graduation;?>" required  placeholder="Professional Graduation *">
                    </div>
					
					<div class="col-xs-4">
					 
                       <input class="form-control input-sm" type="text" name="param[bed_sub1]" value="<?php echo @$emp_info->bed_sub1;?>"  required  placeholder="Methodology Subject 1*">
                    </div>
					
					<div class="col-xs-4">
					 
                       <input class="form-control input-sm" type="text" name="param[bed_sub2]"  value="<?php echo @$emp_info->bed_sub2;?>"   placeholder="Methodology Subject 2">
                    </div>
					
									
					<div class="col-xs-4">
					
                      <input class="form-control input-sm" type="text" name="param[bed_month_year]"  value="<?php echo @$emp_info->bed_month_year;?>"   required  placeholder="Passed Month / Year *">
                    </div>
					
					<div class="col-xs-4">
					 
                      <input class="form-control input-sm number" type="text" name="param[bed_marks]"  value="<?php echo @$emp_info->bed_marks;?>"  placeholder="Marks Secured">
                    </div>
					
					<div class="col-xs-4">
					 
                      <input class="form-control input-sm number" type="text" name="param[bed_max_marks]" value="<?php echo @$emp_info->bed_max_marks;?>"  placeholder="Maximum Marks ">
                    </div>
					
					
					
					<div class="col-xs-6">
					
                      <input class="form-control input-sm" type="text" name="param[bed_name_university]" value="<?php echo @$emp_info->bed_name_university;?>" placeholder="Name of the University *">
                    </div>
                  </div>	   
				   
				  
				   
				  <hr /> 
				  
				<label style="color:#000099;">D. Details of M.Ed/M.P.ED or similar Examination </label>     
				  
				  
				  <div class="row">
                    <div class="col-xs-4">
					 
                      <input class="form-control input-sm" type="text" name="param[med_professional_qualification]"  value="<?php echo @$emp_info->med_professional_qualification;?>"  required placeholder="Professional Qualification * ">
                    </div>
					
				
									
					<div class="col-xs-4">
					
                      <input class="form-control input-sm" type="text" name="param[med_passed_month]" value="<?php echo @$emp_info->med_passed_month;?>"  required  placeholder="Passed Month / Year *">
                    </div>
					
					<div class="col-xs-4">
					 
                      <input class="form-control input-sm number" type="text" name="param[med_marks]" value="<?php echo @$emp_info->med_marks;?>"  placeholder="Marks Secured">
                    </div>
					
					<div class="col-xs-4">
					 
                      <input class="form-control input-sm number" type="text" name="param[med_max_marks]" value="<?php echo @$emp_info->med_max_marks;?>"  placeholder="Maximum Marks ">
                    </div>
					
					
					
					<div class="col-xs-6">
					
                      <input class="form-control input-sm" type="text" name="param[med_name_university]"  value="<?php echo @$emp_info->med_name_university;?>" required placeholder="Name of the University *">
                    </div>
                  </div>	   
				   
				  <hr /> 
				  
				   
				   
				    <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
					
					<div id="changepwdnotifier"></div>
					<div id="error_div"></div>
					
                  </div>
 
           <?php form_close(); ?>
			 
          </div>
		   <script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js"></script>


<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js"></script>  
			 
		  <script type="text/javascript">


    $(document).ready(function() {


        $('#myform').ajaxForm({beforeSubmit : function(arr, $form, options){
                $("#changepwdnotifier").html("<div class='alert alert-warning'><h3>Updating please wait...</h3></div>");
                 $("#error_div").html('');


          },dataType: 'json', success: processJson});


        


    });


    function processJson(data) {

		//alert(data);
        if (data.success) {


            $("#changepwdnotifier").html(data.message);

			 
             setTimeout(function() { 
				if(data.process_type=="edit"){
					window.location = "<?php echo site_url('employees/viewlist/'); ?>"+data.school_id;  
				}else
				{
					document.getElementById("myform").reset(); 
				}
            }, 3000); 
			 document.getElementById("myform").reset(); 
        } else {


            $("#changepwdnotifier").html(data.message);
            $("#error_div").html(data.html_table); 
        }


    }


</script>
		  
		   <script>

  $( function() {

			$( ".datepicker" ).datepicker({ 

			startDate: '09-01-1970',

			endDate: '+0d'});

  } );
$("#post_id").val('<?php echo @$emp_info->post_id;?>');
$("#gender").val('<?php echo @$emp_info->gender;?>');
$("#community").val('<?php echo @$emp_info->community;?>');
$("#disability").val('<?php echo @$emp_info->disability;?>');
$("#type_disability").val('<?php echo @$emp_info->type_disability;?>');
$("#present_district").val('<?php echo @$emp_info->present_district;?>');
$("#permanent_district").val('<?php echo @$emp_info->permanent_district;?>');
$("#local_nonlocal").val('<?php echo @$emp_info->local_nonlocal;?>');




 $(".alphaonly").keypress(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });
	
	$( "input[class*='number']" ).keyup(function(e)
         {
				  if (/\D/g.test(this.value))
				  {
					// Filter non-digits from input value.
					this.value = this.value.replace(/\D/g, '');
				  }
		});	 


  </script>
  
   <script>
 
 function load_json_data(school_id)
 {
  var html_code = '';
  //console.log(school_id);
  $.getJSON('<?php echo site_url('employees/get_designation_list');?>/'+school_id, function(data){

   html_code += '<option value="">Select Designation</option>';
   $.each(data, function(key, value){
     
	// console.log(key);
	 //console.log(value);
      html_code += '<option value="'+value.id+'">'+value.name+'</option>';
     
   });
   $('#post_id').html(html_code);
  });
 }
  
  
  </script>