<?php 
 $only_zeros = intval($this->input->post("only_zeros"));?> 
 <section class="content no-print">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> OB Missed Entries </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              
			  
			  <div class='formgroup' style="padding:30px;">
			  
			 
			  <input   type="radio" name="only_zeros" required id="abc" value="1" <?php  if($only_zeros==1) { echo " checked "; }?>><label for="abc">Only Missed Hostels </label>
			   <input    type="radio" name="only_zeros" required id="abcd" value="0" <?php  if($only_zeros==0) { echo " checked "; }?>><label for="abcd">All Hostels With Filled Count</label>
			  
              <!-- /.box-body -->
              <div class="box-footer">
                
                <input type="submit" class="btn btn-info pull-right" name="submit" value="Get Report">
				 <input type="submit" class="btn btn-info pull-right" name="submit" value="Download Report">
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 
    function validate(form) {
       
	   
    }
</script>
 <script>
  $( function() {
	  
	  
	  
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>
 

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
.daily table{
	border-collapse: collapse;
	 border: 1px;
}
.daily   td{
	padding:5px;
}

</style>


<?php 
 $only_zeros = intval($this->input->post("only_zeros"));
echo $this->session->flashdata('message');?>

<h3>OB Hostels Missed Entries [ <?php echo $report_date;?> ] </h3>
 
  <a href="javascript:window.print();" class="btn btn-info pull-right no-print"><b>Print</b></a><br><br>

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
			<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">SNO</th> 
				 
				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">District Name</th> 
				 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">DDO Code</th> 
				
				 	<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">  Name</th>
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Filled Items</th>
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Contact number</th>
				
				
				</tr>
                </thead>
                <tbody>
                 <?php 
				 $i=1;
				 $only_zeros = intval($this->input->post("only_zeros"));
				 foreach($rset->result() as $school) {  
				 
				 if($school->ddo_code=="85000") continue; 
				 
				  if($only_zeros==1 && $school->filled_count !=0)
					  continue;
				 ?>                <tr role="row" class="odd">
                <td>
					 <?php echo $i; ?> 
				   </td> 
			    <td>
					 <?php echo $school->district_name; ?> 
				   </td> 
				  
                   <td>
					 <?php echo $school->ddo_code; ?> 
				   </td> 
				       <td class="sorting_1"> 
				 <b> <?php echo $school->name;?></b></td>
                  
				      <td class="sorting_1"> 
				 <b> <?php echo $school->filled_count;?></b></td><td class="sorting_1"> 
				 <b> <?php echo $school->contact_number;?></b></td>
                </tr>
				 <?php $i++;} ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 
