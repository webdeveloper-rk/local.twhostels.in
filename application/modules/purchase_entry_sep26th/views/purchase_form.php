<?php
$allow_to_modify = true;
 ?>
<script src="<?php echo site_url();?>js/bootbox.min.js"></script>          
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b> date of  <b><?php echo date('d-m-Y');?></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('', $attributes); 	
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo $errors; ?>  </div>
<?php } ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Quantity</label>
                  <?php if($allow_to_modify==true){ ?>
				  <input type="text" name="quantity" value="<?php echo $today_purchases->purchase_quantity ;?>" class="form-control" required  id="exampleInputEmail1" placeholder="Enter Quantity">
				  <?php } else{ echo   floatval($today_purchases->purchase_quantity);} ?>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Price</label>
                   <?php if($allow_to_modify){ ?>
				  <input type="text"   <?php if($price_read_only) { echo "readonly";} ?>   name="price"  class="form-control"     id="exampleInputPassword1" required placeholder="Price" value="<?php 
				  if($this->config->item("site_name")=="twhostels"){
					  echo $item_price;
				  }else { 
				  echo  $today_purchases->purchase_price;
				  }?>">
				  
                  <input type="hidden"  name="action"    value="submit">
				  <?php } else{ if($this->config->item("site_name")=="twhostels"){
					  echo $item_price;
				  }else { 
						echo  $today_purchases->purchase_price;
				  }} ?>
                </div>
                <div class="form-group">
                 <label for="exampleInputPassword1">Bill Number</label> 
                   <?php if($allow_to_modify){ ?>
				  <input type="text"  name="billno"  class="form-control"     id="exampleInputPassword1"   placeholder="Enter Bill Number" value="<?php echo $today_purchases->purchase_biil_no;?>">
				  
                  <input type="hidden"  name="action"    value="submit">
				  <?php } else{ echo  intval($today_purchases->purchase_biil_no) ;} ?>
                </div>
				 <div class="form-group">
                 <label for="exampleInputPassword1">Vendor name</label> <br>
                   <?php if($allow_to_modify){ ?>
				   <select class='form-control search' name="vendor_id" id='vendor_id' required>
					<option value="">select vendor</option>
					<?php foreach($vendor_details->result() as $row)
					{
						?><option value="<?php echo $row->vendor_annapurna_id;?>"><?php echo $row->vendor_name." - ".$row->supplier_name;?></option><?php 
					}?>
				   
				   </select>
				   <div><span>if you didn't find vendor in above list, Please  <a href='<?php echo site_url();?>vendors/entryform'>Click here</a> to add vendors.</span></div>
				   <?php } else { ?><span style='color:#FF0000;font-weight:bold'><?php  echo $vendor_selected;?></span><?php  } ?>
				   <div id="vendor_list" style="display:none">
				   <?php foreach($vendor_details->result() as $row)
					{
						?><div id="vendor_<?php echo $row->vendor_annapurna_id;?>"><?php echo $row->vendor_name." - ".$row->supplier_name;?></div><?php 
					}?>
					 
				   </div>
				  
                  <input type="hidden"  name="action"    value="submit">
				   
                </div>
				
				
				
               
              </div>
              <!-- /.box-body -->
			 <?php if($allow_to_modify){ ?>
			 <div class='notification-alert'>Note: please check the values carefully before submit, once submitted values can't be modified.</div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
			 <?php } ?>
           <?php form_close(); ?>
          </div>
		  
		    <script>
		  
		  
		  		$(document).ready( function () {  $(function(){
    $("#myform").submit(function(e) {       
      e.preventDefault();
		flag = validatemyfrom();
		//alert(flag);
		
		if(flag){
			 frm = document.getElementById("myform");
			
			 var message = '<h5>You are in submitting the Purchase entry for the following item </h5><br>Item : <b><?php echo $item_details->item_name;?></b> <br>  ';
				   
					message =  message + "Quantity : <b>"+ frm.quantity.value + "</b> Kgs  <br> Price : <b>Rs "+frm.price.value +"</b> per Kg<br>";
					
				  total_quantity  = parseFloat(frm.quantity.value)  ;
				  
				 
				total_price = parseFloat(frm.quantity.value * frm.price.value) ;
					 
				  message = message + "<br> Total Price : <b>Rs "+ total_price.toFixed(2);
				  message = message + " </b><br>Vendor name : <span style='color:#FF0000;font-weight:bold;'>"+$("#vendor_"+frm.vendor_id.value).html()+"</span>";
				  message = message + " </b><br><br><span  ><h5 style='color:#ff0000;font-weight:bold;'> Are you sure to Submit ?</h5></span>";
				 
				  
				  bootbox.confirm({ 
									size: "small",
									message: message, 
									callback: function(result){  
									if(result){
										
											var dialog = bootbox.dialog({
											message: '<p class="text-center"><img src="<?php echo site_url();?>images/progress.gif"></p>',
											closeButton: false
											});
											// do something in the background
											//dialog.modal('hide');			
										sec_wait = Math.floor(Math.random() * 2) + 1  ;
											//alert(sec_wait);
											secs = sec_wait * 1000;
										setTimeout(function(){
											
											
																frm.submit();
																}, secs);
										
										
									}
									}
					});
					
			
			
			
			
		}
		
		
		
    });
});
		});
		  
		  
		  
		  function validatemyfrom(frm)
		  {
			
			  frm = document.getElementById("myform");
			 
			    
	 
			//  alert(frm.quantity.value);
			    
			    if(isNaN(frm.quantity.value))
				  {
					  alert("Please enter valid   purchase quantity");
					  frm.quantity.focus();
					  return false;
				  }
				 
				  if(isNaN(frm.price.value))
				  {
					  alert("Please enter valid   purchase Price");
					  frm.quantity.focus();
					  return false;
				  }
				 
				 
				  
				  
				  return true;
				  
				  
				  
				  
		  }
		  </script>
		  
		  