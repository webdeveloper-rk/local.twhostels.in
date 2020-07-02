<?php 
$from_date = '';
$to_date = '';
if($this->input->post('fromdate')!=null)
	$from_date = $this->input->post('fromdate');
if($this->input->post('todate')!=null)
	$to_date = $this->input->post('todate');


$price_read_only = false;
 if($this->config->item("site_name")=="twhostels")
 {
	 
	 
	  $price = $this->common_model->get_item_fixed_price($item_id,$school_id); 
	 $form_data['pprice'] = $price;
	 $form_data['bf_price'] = $price;
	 $form_data['lu_price'] = $price;
	 $form_data['sn_price'] = $price;
	 $form_data['di_price'] = $price;
 }
?>
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
.h1c
{
	font-size:12px;

}
</style>
<h3>Purchase and consumption entries Form</h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<?php echo $this->session->flashdata('message');?>

<form method="post" action=""  >
 
<div class="box box-primary">
            <div class="box-header">
              
            </div>
            <div class="box-body"> 
			 <label>&nbsp;&nbsp;School:&nbsp;&nbsp;  <span style="color:#ff0000"><b><?php echo $school_info->sname;?></b></span>&nbsp;&nbsp;Month:&nbsp;&nbsp; &nbsp;&nbsp; <span style="color:#ff0000"><b><?php echo $date_selected;?></b></span>&nbsp;&nbsp;Item:&nbsp;&nbsp;  <span style="color:#ff0000">&nbsp;&nbsp;&nbsp;<b><?php echo $item_details->item_name." - " .$item_details->telugu_name;?></b></span></label>
                 
			</div>				  
              <!-- /.form group -->

			   <!-- Date -->
             
              <!-- /.form group -->
 <div class="box-body"> <div class="box-footer">
                 <input type="submit" class="btn btn-info pull-right" value="Submit" name="submit"> <br><br>
                 
                <a href="<?php echo site_url("purchase_consumption_bulk");?>" class="btn btn-info pull-right"  > Go Back </a>
                
				 
              </div>
			 <label></label>
                 
			</div>	 <div class="box-body">
				  <div id="changepwdnotifier"></div><div class="form-group" style="padding:10px;"><table class="table">
			<?php foreach($days_rs->result() as $day_obj) { ?>
             


		<tr>
		<td></td>
			<td><span class='h1c'>Purchase Qty</span></td><td><span class='h1c'>Purchase Price</span></td>
			<td><span class='h1c'>Breakfast Qty</span></td><td><span class='h1c'>Breakfast Price</span></td>
			<td><span class='h1c'>Lunch Qty</span></td><td><span class='h1c'>Lunch Price</span></td>
			<td><span class='h1c'>Snacks Qty</span></td><td><span class='h1c'>Snacks Price</span></td>
			<td><span class='h1c'>Dinner Qty</span></td><td><span class='h1c'>Dinner Price</span></td>
			</tr>
			
			<tr>
			<td><span style="color:#0000FF;font-weight:bold"><?php echo $day_obj->display_date;?></span></td>
			<td>  <input type="text"  value="<?php echo $day_obj->purchase_quantity;?>"  placeholder="Enter   quantity" name="purchase_qty[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td><td><input type="text"  value="<?php echo $day_obj->purchase_price;?>"  placeholder="Enter Price" name="purchase_price[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td>
			 
			 
			 <td>  <input type="text"  value="<?php echo $day_obj->session_1_qty;?>"  placeholder="Enter  quantity" name="bf_qty[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td><td><input type="text"  value="<?php echo $day_obj->session_1_price;?>"  placeholder="Enter Price" name="bf_price[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td>
			 
			 
			  <td>  <input type="text"  value="<?php echo $day_obj->session_2_qty;?>"  placeholder="Enter  quantity" name="lunch_qty[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td><td><input type="text"  value="<?php echo $day_obj->session_2_price;?>"  placeholder="Enter Price" name="lunch_price[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td>
			  
			  <td>  <input type="text"  value="<?php echo $day_obj->session_3_qty;?>"  placeholder="Enter  quantity" name="snacks_qty[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td><td><input type="text"  value="<?php echo $day_obj->session_3_price;?>"  placeholder="Enter Price" name="snacks_price[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td>
			  
			  <td>  <input type="text"  value="<?php echo $day_obj->session_4_qty;?>"  placeholder="Enter  quantity" name="dinner_qty[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;"></td><td><input type="text"  value="<?php echo $day_obj->session_4_price;?>"  placeholder="Enter Price" name="dinner_price[<?php echo $day_obj->entry_id;?>]" required="" style="width:80px;">
			 <!-- <a href="<?php echo site_url("purchase_consumption_bulk/delete_entry/".$day_obj->entry_id);?>" onclick="return confirm('Are you sure to delete?')">Delete </a>-->
			  </td>
			</tr>
			
			
			
                   
			<?php } ?></table>
			  <!-- Date range -->
              <div class="box-footer">
                 <input type="submit" class="btn btn-info pull-right" value="Submit" name="submit"> <br><br>
              
                <a href="<?php echo site_url("purchase_consumption_bulk");?>" class="btn btn-info pull-right"  > Go Back </a>
                
				 
              </div>
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

            </div>
            <!-- /.box-body -->
          </div>
		  </form>
 