<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
<?php
 
	
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
              <h3 class="box-title"><b><?php echo  strtoupper($process_type);?></b> -  Empolyee Info</b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
	 
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('', $attributes); 	
 
 
?>
 <div class="validation_errors"><?php echo $errors; ?>  </div>
 
 
           <?php form_close(); ?>
			<?php } 
			else{
				echo $data_entry_text;
			}
			?>
			
          </div>
		   
			 
		  <script>
		  function toggle(flag)
		  {
			if(flag==1){
					$("#oldstockform").show();
					$(".new").show();
			}
			else{
					$("#oldstockform").hide();
					$(".new").hide();
			}
		  }
		  
		  
		  		$(document).ready( function () {  $(function(){
    $("#myform").submit(function(e) {       
      e.preventDefault();
		flag = validatemyfrom();
		//alert(flag);
		
		if(flag){
			 frm = document.getElementById("myform");
			
			 var message = '<h5>You are in submitting the consumption entry for the following item </h5><br>Item : <b><?php echo $item_details->item_name;?></b> <br>  ';
				  if(combined_value==1)
					{
							message =  message + "Old Quantity : <b>"+ frm.old_quantity.value + "</b> Kgs <br>Old quantity Price : <b>Rs "+frm.old_price.value +"</b>  per Kg<br>";
					}
					message =  message + "Quantity : <b>"+ frm.quantity.value + "</b> Kgs  <br> Price : <b>Rs "+frm.price.value +"</b> per Kg<br>";
					
				  total_quantity  = parseFloat(frm.quantity.value)  + parseFloat(frm.old_quantity.value);
				  
				  if(combined_value==1)
					{
						total_price = parseFloat(frm.quantity.value * frm.price.value) + parseFloat(frm.old_quantity.value * frm.old_price.value)
					}
					else {
						total_price = parseFloat(frm.quantity.value * frm.price.value) ;
					}
				 
				  message = message + "<br> Total Price : <b>Rs "+ total_price.toFixed(2);
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
			  combined_value  = frm.combined_stock.value;
			    
			  if(combined_value==1)
			  {
				  if(isNaN(frm.old_quantity.value))
				  {
					  alert("Please enter valid Old  stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
			    if(isNaN(frm.old_quantity.value))
				  {
					  alert("Please enter valid   Old stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
				  if(parseFloat(frm.old_quantity.value)==0)
				  {
					  alert("Please enter valid old stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
				  if(parseFloat(frm.old_price.value)==0)
				  {
					  alert("Please enter valid old stock Price");
					  frm.old_price.focus();
					  return false;
				  }
				  
				  
			  }
			//  alert(frm.quantity.value);
			   if(isNaN(frm.quantity.value))
				  {
					  alert("Please enter valid   stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
			    if(isNaN(frm.quantity.value))
				  {
					  alert("Please enter valid   stock quantity");
					  frm.quantity.focus();
					  return false;
				  }
				   if(parseFloat(frm.quantity.value)==0)
				  {
					  alert("Please enter valid   stock quantity");
					  frm.quantity.focus();
					  return false;
				  }
				  
				  
				  
				   <?php if(isset( $min_item_value)){ ?>
							if(parseFloat(frm.price.value)<<?php echo $min_item_value;?>)
								{
									//alert("Price should not less than  <?php echo  $min_item_value;?>");
									alert("Please check the price value  ");
									frm.price.focus();
									return false;
								}
				  <?php  } ?>
				  
				  <?php if(isset( $max_item_value)){?>
							if(parseFloat(frm.price.value)><?php echo $max_item_value;?>)
								{
									//alert("Price should not exceed <?php echo  $max_item_value;?> RS");
									alert("Please check the price value  ");
									frm.price.focus();
									return false;
								}
				  <?php } ?>
				  
				 
				  
				  
				  return true;
				  
				  
				  
				  
		  }
		  </script>
		  
		  