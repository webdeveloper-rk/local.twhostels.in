<?php  echo $this->session->flashdata('message'); 
<h3>Vendors List</h3>
 <style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<div class="box">
          
            <!-- /.box-header --><br><br>
            <div class="box-body">
               <?php if($vendors->num_rows()==0){
			  <span><b><h4>Total Vendors  : <?php echo $vendors->num_rows();?></h4></span>
			  <table id="example1" class="table vendors table-bordered">
		   
	 