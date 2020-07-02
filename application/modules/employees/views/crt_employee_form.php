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
	<!--<h3><span style='color:red'>Time extended for snacks and supper till 7 PM for March 8th 2017 only.</span></h3>-->
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b><?php echo  strtoupper($process_type);?></b> -  <span style='color:#FF0000;font-weight:bold;'>CRT </span>Empolyee Info - <b><?php echo $school_name;?></b></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
	 
         <?php   $attributes = array('class' => 'email', 'id' => 'myform',
		 'action'=>site_url('employees/updateemployee/'.$process_type."/".$school_id."/".$emp_id));
//echo form_open('', $attributes); 	
 
 
?>

   <form    class="email" id="myform" action="<?php echo site_url('employees/updateemployee/'.$process_type."/".$school_id."/".$emp_id);?>" method="post" accept-charset="utf-8">
     <input type="hidden"  name="param[employee_type]"  value="crt">
<!-- <div class="validation_errors"><?php echo @$errors; ?>  </div>-->
 <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
			
			
			
			
			 <div >
                <div class="box-header">
                  <h3>CRT Details</h3>
                </div>
                <div class="box-body">
                  <div class="row">
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
					  <label>DDO Code *</label>
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
					
					
					
					
					
					<div class="col-xs-12">
					 <label>Name of the school at which CRT was joined first *</label>
                      <input type="text" class="form-control" required name="param[first_school_name]" value="<?php echo @$emp_info->first_school_name;?>" id="firstschoolname" placeholder="Enter first school name">
                    </div>
					
					
					<div class="col-xs-12">
					 <label>Date of CRT first Engaged School *</label>
                      <input type="text" class="datepicker  form-control" value="<?php echo @$emp_info->crt_first_engaged_school;?>" name="param[crt_first_engaged_school]" required    value="" id="crt_first_engaged_school" placeholder="Select Date"   > 
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
									<?php foreach($posts_rs->result() as $trow){ ?>
									<option value="<?php echo $trow->post_id;?>"><?php echo $trow->post_title;?></option>
									<?php } ?> 
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
			
			
			
			
			<h3>CRT BANK DETAILS</h3>
              <div class="row">
				 				  
        	<div class="col-md-6">
                      <label>BANK NAME *</label>
                      
					  <select class="form-control" name="param[bank_name]" id="bank_name" required  >
								
					<option value="0" selected="selected">Select Bank Name</option>
						<option value="ALLAHABAD BANK">ALLAHABAD BANK</option>
<option value="ANDHRA BANK">ANDHRA BANK</option>
<option value="ANDHRA PRADESH GRAMEENA VIKAS BANK">ANDHRA PRADESH GRAMEENA VIKAS BANK</option>
<option value="ANDHRA PRAGATHI GRAMEENA BANK">ANDHRA PRAGATHI GRAMEENA BANK</option>
<option value="AXIS BANK">AXIS BANK</option>
<option value="BANK OF BAHARAIN AND KUWAIT BSC">BANK OF BAHARAIN AND KUWAIT BSC</option>
<option value="BANK OF BARODA">BANK OF BARODA</option>
<option value="BANK OF INDIA">BANK OF INDIA</option>
<option value="BANK OF MAHARASHTRA">BANK OF MAHARASHTRA</option>
<option value="B N P PARIBAS">B N P PARIBAS</option>
<option value="CANARA BANK">CANARA BANK</option>
<option value="CATHOLIC SYRIAN BANK LIMITED">CATHOLIC SYRIAN BANK LIMITED</option>
<option value="CENTRAL BANK OF INDIA">CENTRAL BANK OF INDIA</option>
<option value="CITI BANK">CITI BANK</option>
<option value="CITY UNION BANK LIMITED">CITY UNION BANK LIMITED</option>
<option value="CORPORATION BANK">CORPORATION BANK</option>
<option value="DCB BANK LIMITED">DCB BANK LIMITED</option>
<option value="DENA BANK">DENA BANK</option>
<option value="DHANALAKSHMI BANK">DHANALAKSHMI BANK</option>
<option value="FEDERAL BANK">FEDERAL BANK</option>
<option value="HDFC BANK">HDFC BANK</option>
<option value="HSBC BANK">HSBC BANK</option>
<option value="ICICI BANK LIMITED">ICICI BANK LIMITED</option>
<option value="IDBI BANK">IDBI BANK</option>
<option value="INDIAN BANK">INDIAN BANK</option>
<option value="INDIAN OVERSEAS BANK">INDIAN OVERSEAS BANK</option>
<option value="INDUSIND BANK">INDUSIND BANK</option>
<option value="JAMMU AND KASHMIR BANK LIMITED">JAMMU AND KASHMIR BANK LIMITED</option>
<option value="KARNATAKA BANK LIMITED">KARNATAKA BANK LIMITED</option>
<option value="KARUR VYSYA BANK">KARUR VYSYA BANK</option>
<option value="KOTAK MAHINDRA BANK LIMITED">KOTAK MAHINDRA BANK LIMITED</option>
<option value="LAXMI VILAS BANK">LAXMI VILAS BANK</option>
<option value="ORIENTAL BANK OF COMMERCE">ORIENTAL BANK OF COMMERCE</option>
<option value="PUNJAB AND SIND BANK">PUNJAB AND SIND BANK</option>
<option value="PUNJAB NATIONAL BANK">PUNJAB NATIONAL BANK</option>
<option value="SOUTH INDIAN BANK">SOUTH INDIAN BANK</option>
<option value="STANDARD CHARTERED BANK">STANDARD CHARTERED BANK</option>
<option value="STATE BANK OF BIKANER AND JAIPUR">STATE BANK OF BIKANER AND JAIPUR</option>
<option value="STATE BANK OF INDIA">STATE BANK OF INDIA</option>
<option value="STATE BANK OF MYSORE">STATE BANK OF MYSORE</option>
<option value="STATE BANK OF PATIALA">STATE BANK OF PATIALA</option>
<option value="STATE BANK OF TRAVANCORE">STATE BANK OF TRAVANCORE</option>
<option value="SYNDICATE BANK">SYNDICATE BANK</option>
<option value="TAMILNAD MERCANTILE BANK LIMITED">TAMILNAD MERCANTILE BANK LIMITED</option>
<option value="TELANGANA STATE COOP APEX BANK">TELANGANA STATE COOP APEX BANK</option>
<option value="THE ANDHRA PRADESH STATE COOPERATIVE BANK LIMITED">THE ANDHRA PRADESH STATE COOPERATIVE BANK LIMITED</option>
<option value="THE A.P. MAHESH COOPERATIVE URBAN BANK LIMITED">THE A.P. MAHESH COOPERATIVE URBAN BANK LIMITED</option>
<option value="THE COSMOS CO OPERATIVE BANK LIMITED">THE COSMOS CO OPERATIVE BANK LIMITED</option>
<option value="UCO BANK">UCO BANK</option>
<option value="UNION BANK OF INDIA">UNION BANK OF INDIA</option>
<option value="UNITED BANK OF INDIA">UNITED BANK OF INDIA</option>
<option value="VIJAYA BANK">VIJAYA BANK</option>
<option value="YES BANK">YES BANK</option></select>  
					  
					  
                    </div>  
					
					
					<div class="col-md-6">
                      <label>ACCOUNT HOLDER NAME *</label>
                      <input type="text" name="param[bank_account_holder_name]"  value="<?php echo @$emp_info->bank_account_holder_name;?>" required  class="form-control alphaonly" placeholder="Enter bank account holder name"/>
                    </div>  
					
					<div class="col-md-6">
                      <label>ACCOUNT NUMBER *</label>
                      <input type="number" name="param[bank_account_number]" id="tentacles" value="<?php echo @$emp_info->bank_account_number;?>" required  class="form-control number" placeholder="Enter bank account number"/>
                    </div>  
					
					<div class="col-md-6">
                      <label>IFSC CODE *</label>
                      <input type="text" name="param[bank_ifsc]"  value="<?php echo @$emp_info->bank_ifsc;?>" required  class="form-control" placeholder="Enter bank ifsc code"/>
                    </div>  
					 
								   
				</div>
			
			
			
			
			
			
			
							   
   <h3>Residential Address</h3>				  
				
             
                
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
				 
				 
		
				 
				
				 
		
				 
				 
				
           
                 
					
					 
					 
				   
				    
											  
							</div>   
             
			  
			 

              <!-- Input addon -->
             
              </div><!-- /.box -->

            </div><!--/.col (left) -->
            
			
			
			
			
			
			
			
			
			
			
			
			
			
		
               
            
               
                   
			<!-- right column -->
            <div class="col-md-6">
              <!-- general form elements disabled -->
              <div >
			  <h3>Educational Qualification </h3>   
				   
				    
				  
				  
				     <label style="color:#000099;">C. Details of Degree or Equivalent Examinations Passed </label>
			  
			  <div class="row">
			
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
                window.location = "<?php echo site_url('employees/viewlist/'.$school_id); ?>";  
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
$("#bank_name").val('<?php echo @$emp_info->bank_name;?>');




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