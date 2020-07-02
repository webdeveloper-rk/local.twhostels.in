<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
<?php
 //echo "<pre>"; print_r($item_used); echo "</pre>";
$school_id = $this->session->userdata("school_id");
$district_id = $this->session->userdata("district_id");
$entry_date = date('Y-m-d');
$att_rs= $this->db->query("select attendence_id,cat1_attendence+ cat1_guest_attendence as cat1,cat2_attendence+ cat2_guest_attendence as cat2,cat3_attendence+ cat3_guest_attendence as cat3 from school_attendence where school_id=? and entry_date=?",array($school_id,$entry_date));
if($att_rs->num_rows()==0)
{
		$this->db->query("insert into school_attendence(school_id,entry_date) select ? as school_id,cal_date as entry_date from calender where cal_date between '2018-12-01' and CURRENT_DATE and cal_date not in (select entry_date as cal_date from school_attendence where school_id=?)",array($school_id,$school_id));
		
		$att_rs= $this->db->query("select attendence_id,cat1_attendence+ cat1_guest_attendence as cat1,cat2_attendence+ cat2_guest_attendence as cat2,cat3_attendence+ cat3_guest_attendence as cat3 from school_attendence where school_id=? and entry_date=?",array($school_id,$entry_date));
		
}
$att_data = $att_rs->row();
 
$att_date_id = $att_data->attendence_id;


$item_default_price = $this->db->query("select * from district_item_prices where district_id=? and item_id=? and status=1",array($district_id,$item_id))->row()->price;


$item_default_qty = 0.00;
 $qrs = $this->db->query("select * from indent_grams where item_id=? and status=1",array( $item_id));
 $cat1_qty = 0;
 $cat2_qty = 0;
 $cat3_qty = 0;
 foreach($qrs->result() as $qtydata)
 {
	 if($qtydata->category==1)
	 {
		$cat1_qty = $att_data->cat1 * $qtydata->grams;
	 }
	 if($qtydata->category==2)
	 {
		$cat2_qty = $att_data->cat2 * $qtydata->grams;
	 }
	 if($qtydata->category==3)
	 {
		$cat3_qty = $att_data->cat3 * $qtydata->grams;
	 }
 }
 
 $total_default_qty = $cat1_qty  + $cat2_qty  + $cat3_qty;



 
$min_item_value = $item_details->min_price;
$max_item_value = $item_details->max_price;

$price_read_only = false;
  
 
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
              <h3 class="box-title"><b><?php //echo $current_session->name;?>All Sessions </b> - Consumption entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b> date of  <b><?php echo date('d-m-Y');?></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php 
			//check special item or not and approved or not 
			 //print_a($item_details);
			// print_a($this->session->all_userdata());
			if($item_details->item_special=="special")
			{
				$chk_date = date('Y-m-d');
				$sch_id = $this->session->userdata('school_id');
				$sql_chk = "select * from item_approvals where 
										school_id='".$sch_id."' and 
										item_id='".$item_details->item_id."' and 
										entry_date='$chk_date' and status='Approved'";
				//echo $sql_chk;
				$rs_chk = $this->db->query($sql_chk);
				if($rs_chk->num_rows()==0)
				{
					 echo "<h1>This item has not been Approved to use on ".date('d-m-Y')."</h1>";
					 return;
					
				}					
										
			}
			
			if($data_entry_allowed == true) {
				
			
			?>
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('', $attributes); 	
 
if($this->input->post('quantity') !="")
{
	 $qty = $this->input->post('quantity');
}
	
if($this->input->post('price') !="")
{
	 $price = $this->input->post('price');
}
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo $errors; ?>  </div>
<?php } ?>

  <input type="hidden"  name="consumptionprime"    value="<?php echo  $entry_id  ;?>">
  
  <div class="box-body">
  
  <div class="form-group">
                  
					<table style='font-family: Verdana, Geneva, sans-serif;font-size: 15px;' width="40%"><tr  ><tr style='padding-top: 11px;padding-bottom: 11px;background-color: #3c8dbc;color: white;border: 1px solid #ddd;text-align: left;padding: 8px;'><th colspan='2' align='left' style="padding:10px;">Attendence </th></tr>
								<tr style="background-color:#7FD4FF"> <td style="padding:10px;">Up to 7th class</td><td style="padding:10px;"><?php echo $att_data->cat1;?></td></tr>
								<tr style="background-color:#FFDCAD"> <td style="padding:10px;"> 8,9,10th classes</td><td style="padding:10px;"><?php echo $att_data->cat2;?></td></tr>
								<tr style="background-color:#7FD4FF"> <td style="padding:10px;">Intermediate and above</td><td style="padding:10px;"><?php echo $att_data->cat3;?></td></tr>
								<tr style="background-color:#FFDCAD"> <td style="padding:10px;">Total</td><td style="padding:10px;"><?php echo $att_data->cat1+$att_data->cat2+$att_data->cat3;?></td></tr>
								<tr style="background-color:#FFFFFF"> <td style="padding:10px;"> </td><td style="padding:10px;">
								<?php if($locked_check==false) { ?>
								<a class='btn btn-info pull-right' href='<?php echo site_url('attendence/index/edit/'.$att_date_id);?>'>Update Attendence</a><?php } ?></td></tr>
						 </table>
						 <?php if(($att_data->cat1+$att_data->cat2+$att_data->cat3) == 0)
						 {
							 ?><div class='notification-alert'>Looks Like you didn't updated the attendence.please  click on update Attendence button and update the attendence and please come back to same page .</div>
							 <?php 
							 return '';
						 }
						 ?><?php if($locked_check==false) { ?>
						 <div class='notification-alert'>Note: if you want to update attendence click on update Attendence button and update the attendence and please come back to same page .</div><?php } ?>
 
						      </div>
  
			  	   <div class="form-group">
                  <label for="exampleInputEmail1">Avilable Quantity</label>
						<span class="bold " style="color:#0000FF;font-weight:bold;font-size:18px"><?php echo $item_used->closing_quantity;?></span>&nbsp;&nbsp;KG
						      </div>
				<div class="form-group">
					<?php if($locked_check==false  ) { ?>
				  <label for="exampleInputEmail1" style="color:#0000FF">Is Combined Stock ?</label>
                   <label ><input type="radio" name="combined_stock" value="0"  <?php if($item_used->$old_qty==0) { echo 'checked=checked'; } ?> onchange="toggle(this.value)"> No </label>
				    <label ><input type="radio" name="combined_stock" value="1" <?php  if($item_used->$old_qty>0) { echo 'checked=checked'; } ?> onchange="toggle(this.value)"> Yes </label>
                </div>
					<?php }
					 
					?>
				<div class="form-group" id="oldstockform" style="display: <?php  if($item_used->$old_qty==0) { echo 'none'; } ?> ">
						  <div class="form-group">
						  <label for="exampleInputEmail1"><span class='new1'>Old Stock </span>Quantity</label>
						  	<?php if($locked_check==false) { ?>
						  <input type="text" name="old_quantity" value="<?php echo $item_used->$old_qty;?>" class="form-control"  required  id="old_quantity" placeholder="Enter Quantity">
						  <?php } else {echo $item_used->$old_qty;}?>
						</div>
						 <div class="form-group">
						  <label for="exampleInputEmail1"><span class='new1'>old Stock </span>Price</label>
						 
							<?php if($locked_check==false) { ?>
								
						 <input type="text"  <?php if($price_read_only) { echo "readonly";} ?> name="old_price" value="<?php echo  number_format($item_used->$old_price,2);?>" class="form-control"  required  id="old_price" placeholder="Enter Price">
							<?php } else {echo number_format($item_used->$old_price,2);}?>
						</div>
                
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1"><span class='new'>New Stock </span>Quantity</label>
                  <?php if($locked_check==false) { 
					
				  ?>
				  <input type="text" onchange="toggle_comments()" name="quantity" value="<?php echo floatval($total_default_qty) ;?>" class="form-control"  required  id="exampleInputEmail1" placeholder="Enter Quantity">
				  <?php 
							  } else {echo $item_used->$qty;}?>
                </div>
				 <div class="form-group">
                  <label for="exampleInputEmail1"><span class='new'>New Stock </span>Price</label>
				   <?php if($locked_check==false) {  $dpc_rates = $this->session->userdata('dpc_rates');  ?>
                  <input type="text" name="price"  onchange="toggle_comments()" <?php if($price_read_only) { echo "readonly";} ?>   <?php if($this->config->item('dpc_rates')==true) {  echo " readonly "; } ?> value="<?php if($this->config->item('dpc_rates')==true) { 
					$dpc_rates = $this->session->userdata('dpc_rates');
				  echo number_format($dpc_rates[$item_id],2);}else if($this->config->item("site_name")=="twhostels"){
					  echo   number_format($item_price,2) ;
				  } else { echo number_format($item_used->$price,2) ;}?>" class="form-control"  required  id="exampleInputprice" placeholder="Enter Price">
				    <?php } else {echo number_format($item_used->$price,2); }?>
                </div>
                
               
              </div>
                
                <div class="form-group" id="comments_div" style="display:none">
                  <label for="exampleInputEmail1"><span class='new'>  </span>Reason to Change Quantity or Price</label><br>
				    <textarea cols="120" rows="10" name="comments" id="comments"></textarea>
                </div>
              </div>
              <!-- /.box-body -->
 <?php if($locked_check==false) { ?>
  <div class='notification-alert'>Note: please check the values carefully before submit, once submitted values can't be modified.</div>
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
 <?php } ?>
 <input type="hidden" name="default_qty" id="default_qty" value="<?php echo  $total_default_qty;?>">
 <input type="hidden" name="default_price" id="default_price" value="<?php echo  $item_default_price;?>">
 <input type="hidden" name="comments_post" id="comments_post" value="0">
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
		  function toggle_comments()
		  {
			if( $("#default_qty").val()!=$("#qty_id").val()){
					$("#comments_div").show();
					$("#comments_post").val(1);
			}
			else if( $("#default_price").val()!=$("#price_id").val()){
					$("#comments_div").show();
					$("#comments_post").val(1);
			}
			else
			{
				$("#comments_div").hide();
				$("#comments_post").val(0);
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
				   
					message =  message + "Quantity : <b>"+ frm.quantity.value + "</b> Kgs  <br> Price : <b>Rs "+frm.price.value +"</b> per Kg/litre/unit<br>";
					
				  total_quantity  = parseFloat(frm.quantity.value)  ;
				   
				  
				  
				  
						total_price = parseFloat(frm.quantity.value * frm.price.value) ;
					 
				 
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
				  
				 
				  if($("#comments_post").val()==1)
				  {
						if($("#comments").val().trim()=="")
						{
							alert("Please enter reason to change quantity or price  ");
									$("#comments").focus();
									return false;
						}
				  }
		   
				  
				  return true;
				  
				  
				  
				  
		  }
		  </script>
		  
		  