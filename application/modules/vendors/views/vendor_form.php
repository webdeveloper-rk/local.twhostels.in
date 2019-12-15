<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
 
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
              <h3 class="box-title"><b>Add New Vendor</b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
 
  
  <div class="box-body">
  
   <?php   $attributes = array('class' => 'email', 'id' => 'myform','action'=>site_url().'vendors/ajax_add_vendor');
echo form_open('', $attributes); ?>
    
	<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> Vendor Type *</label>
						   
						  <select name='vendor_type' id='vendor_type' class="form-control"  required   >
							<option value="">Please select vendor type</option>
							<option value="local">Local</option>
							<option value="central">Central</option>
						  </select>
						  
						</div>
					 
                
                </div>
				
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> Vendor name *</label>
						   
						  <input type="text" name="vendor_name" value="" class="form-control alphaonly"  required  id="vendor_name" placeholder="Enter vendor name">
						  
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">business nature * </label>
						   
						  <input type="text" name="business_nature" value="" class="form-control alphaonly"  required  id="business_nature" placeholder="Enter business nature">
						  
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> tin number</label>
						   
						  <input type="text" name="tin_number" value="" class="form-control"     id="tin_number" placeholder="Enter tin number">
						  
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> vendor address</label><br>
						   
						  <textarea name="vendor_address" cols="60" rows="4"  ></textarea>
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> vendor contact number</label>
						   
						  <input type="text" name="vendor_contact_number" value="" class="form-control number"     id="vendor_contact_number" placeholder="Enter vendor contact number">
						  
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> supplier name</label>
						   
						  <input type="text" name="supplier_name" value="" class="form-control alphaonly"  required    id="supplier_name" placeholder="Enter supplier name">
						  
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> supplier contact number</label>
						   
						  <input type="text" name="supplier_contact_number" value="" class="form-control number"     id="supplier_contact_number" placeholder="Enter supplier contact number">
						  
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> vendor bank *</label>
						   
						  <input type="text" name="vendor_bank" value="" class="form-control alphaonly"  required  id="vendor_bank" placeholder="Enter vendor bank">
						  
						</div>
					 
                
                </div>
				
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> vendor bank branch *</label>
						   
						  <input type="text" name="vendor_bank_branch" value="" class="form-control alphaonly"  required  id="vendor_bank_branch" placeholder="vendor bank branch  ">
						  
						</div>
					 
                
                </div>
                 <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> vendor bank ifsc * </label>
						   
						  <input type="text" name="vendor_bank_ifsc" value="" class="form-control"  required  id="vendor_bank_ifsc" placeholder="Enter vendor bank ifsc">
						  
						</div>
					 
                
                </div>
                 <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> vendor account number * </label>
						   
						  <input type="text" name="vendor_account_number" value="" maxlength="20" class="form-control number"  required  id="vendor_account_number" placeholder="Enter vendor account number">
						  
						</div>
					 
                
                </div>
                 <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> supplier aadhar number</label>
						   
						  <input type="text" name="supplier_aadhar_number" value="" class="form-control number"     id="supplier_aadhar_number" placeholder="Enter supplier aadhar number">
						  
						</div>
					 
                
                </div>
                 
 
   
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			<div class='error_div'></div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

           <?php form_close(); ?>
			 
			
          </div>
		   
			 
		  <script>
		   
		  
		  		$(document).ready( function () {  $(function(){
    $("#myform").submit(function(e) {       
      e.preventDefault();
		flag =1;
		//alert(flag);
		
		if(flag){
			 frm = document.getElementById("myform");
			$(".error_div").html("");
			 var message = '<h5>You are in submitting the New Vendor Details </h5>  <br>  ';
				   
					message =  message + "vendor Type : <b>"+ frm.vendor_type.value + "</b>   <br>  ";
					message =  message + "vendor name : <b>"+ frm.vendor_name.value + "</b>   <br>  ";
					message =  message + "Business nature : <b>"+ frm.business_nature.value + "</b>   <br>  "; 					 
					message =  message + "Tin number : <b>"+ frm.tin_number.value + "</b>   <br>  ";
					message =  message + "Vendor contact number : <b>"+ frm.vendor_contact_number.value + "</b>   <br>  ";
					message =  message + "Supplier name : <b>"+ frm.supplier_name.value + "</b>   <br>  ";
					message =  message + "Supplier contact number : <b>"+ frm.supplier_contact_number.value + "</b>   <br>  ";
					message =  message + "Vendor bank : <b>"+ frm.vendor_bank.value + "</b>   <br>  ";
					message =  message + "Vendor bank branch : <b>"+ frm.vendor_bank_branch.value + "</b>   <br>  ";
					message =  message + "Vendor bank ifsc : <b>"+ frm.vendor_bank_ifsc.value + "</b>   <br>  ";
					message =  message + "Vendor account number : <b>"+ frm.vendor_account_number.value + "</b>   <br>  ";
					message =  message + "Supplier aadhar number : <b>"+ frm.supplier_aadhar_number.value + "</b>   <br>  ";
					
				   
				  message = message + " </b><br><br><span  ><h5 style='color:#ff0000;font-weight:bold;'> Are you sure to Submit ?</h5></span>";
				 
				  
				  bootbox.confirm({ 
									size: "small",
									message: message, 
									callback: function(result){  
									if(result){
										
											 
										  $.ajax({
																			   type: "POST",
																			   url: '<?php echo site_url();?>vendors/ajax_add_vendor',
																			   data: $("#myform").serialize(), // serializes the form's elements.
																			   success: function(data)
																			   {
																				   if(data.success == true)
																				   {
																					  $(".error_div").html(data.msg);
																					   window.location.href="<?php echo site_url();?>vendors"
																				   }else
																				   {
																						$(".error_div").html(data.msg);
																				   }
																			   }
																			 });
										
										
									}
									}
					});
					
			
			
			
			
		}
		
		
		
    });
});
		});
		  
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
		   
		  
		  