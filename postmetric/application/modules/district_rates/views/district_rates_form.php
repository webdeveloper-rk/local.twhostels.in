<?php
//print_a($params);
  
 ?>
<script src="<?php echo site_url();?>js/bootbox.min.js"></script>          
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">District Quantity and price for <b><?php echo $params->item_title ; ?></b> </h3>
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
                  <label for="exampleInputPassword1">Price</label>
                   
				  <input type="text"       name="price"  class="form-control"     id="exampleInputPassword1" required placeholder="Price" value="<?php  echo $dataset->price; ?>">
				   
                </div>
                
               
              </div>
              <!-- /.box-body -->
			 
			 
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
			 
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
			
			 var message = '<h5>You are in submitting the   following form </h5><br>Item : <b> <?php echo $params->item_title ; ?></b> <br>  ';
				   
					message =  message + " Price : <b>Rs "+frm.price.value +"</b> per Kg/unit/litre<br>";
					
				   
				  
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
			    
			     
				 
				  if(isNaN(frm.price.value))
				  {
					  alert("Please enter valid   purchase Price");
					  frm.quantity.focus();
					  return false;
				  }
				 
				 
				  
				  
				  return true;
				  
				  
				  
				  
		  }
		  </script>
		  
		  