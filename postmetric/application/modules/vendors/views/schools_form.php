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
	 
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Vendors :: Schools</b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
 
  
  <div class="box-body">
  
   <?php   $attributes = array('class' => 'email ', 'id' => 'myform' );
echo form_open('', $attributes); ?>
    
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> School name</label>
						   
						  <select class="form-control search"  required  id="school_id"  name="school_id" >
							<option value="">Please select </option>
							<?php foreach($schools_rs->result() as $row) { ?>
							<option value="<?php echo  $row->school_id;?>"><?php echo  $row->school_code." - ".  $row->name;  ?></option>
							<?php } ?>
						  </select>
						  <div>if you didn't find the hostel in the above list means ehostel id not mapped with hostel. please contact administrator.</div>
						</div>
					 
                
                </div>
				 
                 
 
   
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			<div class='error_div'></div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

           <?php form_close(); ?>
			 
			
          </div>
		   
		 
		  