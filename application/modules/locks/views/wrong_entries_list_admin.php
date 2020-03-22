<style>
.daily table td{
	padding:5px;
}

</style>

<section class="content noprint">

<div class="box box-info noprint">

            <div class="box-header with-border">

              <h3 class="box-title">Wrong Entries report</h3>

            </div>

            <!-- /.box-header -->

            <!-- form start -->

             
<?php 
echo $this->session->flashdata('message');
 

?>
<div style="color:#ff0000;padding:10px;font-weight:bold;"><?php echo validation_errors();?></div>
			<form   role="form" class="form-horizontal noprint"   action=""  method="post"  >

              <div class="box-body">

				  <div id="changepwdnotifier"></div>
 

			

				 <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">From Date</label>



                  <div class="col-sm-10">

                    <input type="text" class="datepicker form-control" id="from_date" placeholder="Select From Date" name="from_date"  required value="<?php echo @$this->input->post("from_date");?>">

                  </div>

                </div>
<div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">To Date</label>



                  <div class="col-sm-10">

                    <input type="text" class="datepicker form-control" id="to_date" placeholder="Select To Date" name="to_date"  required value="<?php echo @$this->input->post("to_date");?>">

                  </div>

                </div>
				 

           

              </div>

              <!-- /.box-body -->

              <div class="box-footer noprint">

                

                <input type="submit" name="submit" class="btn btn-info pull-right" value="Download">
                <input type="submit" name="submit" class="btn btn-info pull-right" value="Get Report">

              </div>

              <!-- /.box-footer -->

            </form>

          </div>

 

</section>



<!-- jQuery 1.10.2 -->

 

     
 

 <script>

  $( function() {

			$( ".datepicker" ).datepicker({ 

			startDate: '09-01-2016',

			endDate: '+0d'});

  } );

  </script>





<?php if($result_display ==true) { ?>

<h3>Wrong Entries List between <?php echo $from_date_dp;?> and <?php echo $to_date_dp;?> </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			   <a href='#' class='btn btn-info pull-right noprint' onclick='javascript:window.print();'>Print</a>
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th> 
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th> 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Wrong Entry Date</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Caretaker name</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Session name</th>
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Actual Qty</th>
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Actual Rate</th>
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Wrong entry Qty</th>
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Wrong Entry Rate</th>
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Raised Date</th> 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($wrong_entries_list->result() as $item) {  
				 
						 
				 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"> <b> <?php echo  $item->school_code."-".$item->name;?></b></td> 
                  <td class="sorting_1"> <b> <?php echo  $item->item_name;?></b></td> 
				
                   <td> <?php echo $item->entry_date_dp; ?>   </td> 
				    
				   </td>  <td> <?php echo  $item->caretaker_name; ?>   </td> 
				   </td>  <td> <?php echo  $item->session_name; ?>   </td> 
				   </td>  <td> <?php echo  $item->actual_quantity; ?>   </td> 
				   </td>  <td> <?php echo  $item->actual_rate; ?>   </td> 
				   </td>  <td> <?php echo  $item->wrong_entry_quantity; ?>   </td> 
				   </td>  <td> <?php echo  $item->wrong_entry_rate; ?>   </td> 
				   </td>  <td> <?php echo  $item->raised_date_dp; ?>   </td> 
				   	 
                </tr>
				 <?php 
				 
						 
				 } ?>
				
                </tbody>
                
              </table>
            </div>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example1').DataTable({
		"pageLength": 300,
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
	  
      "autoWidth": true
    });
  });
</script>
	 
<?php } ?>