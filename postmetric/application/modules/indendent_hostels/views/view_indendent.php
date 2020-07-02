<section class="content">

<?php echo $this->session->flashdata('message');?>
<a href="javascript:window.history.back();" class="btn btn-info pull-center">Go Back</a>
<br><br><br>

 
		  
		  <div class="box">
            <div class="box-header">
             <h4><b>  Indent  Report of <b><?php echo   $dp_date ;?></b>[<b><?php echo $school_info->school_code."-".$school_info->name;?></b>] </h4>
            </div></b>
            <!-- /.box-header -->
            
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Sno</th>
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th> 
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">No Of Days</th> 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Multiples</th> 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Strength</th> 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Opening Balance Qty</th> 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Monthly Required Qty</th> 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;"><?php echo $multiples;?> Months Required Qty</th> 
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Required Qty per selected Days </th> 
		
		
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">DTDO FINAL QTY </th> 
				<th></th>
		
</tr>
                </thead>
                <tbody>
                 <?php 
				 $sno = 1;
				 $total_consumed = 0;
				 
				  
				 foreach($schools_data->result() as $item) {
 				 
				 // print_a($item,1);
				  /* [item_id] => 77
            [item_name] => Rice-Rice
            [opening_quantity] => 0.000
            [opening_price] => 0.00
            [opening_amount] => 0.00000
            [overall_month] => 120
            [number_of_days] => 31
            [school_strength] => 
            [required_qty] => 1550*/
				  
				  
				 ?>
				 <tr  >
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $sno;?></td> 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo  $item->item_name;?></td> 
							
							
							
							 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo  $item->number_of_days;?></td> 
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo  $multiples;?></td> 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo  $item->school_strength;?></td> 
							
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo number_format( $item->opening_quantity,2);?></td> 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"> <?php echo  number_format($item->monthly_required_qty,3);?></td> 
							
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"> <?php echo  $item->multiples_monthly_required_qty;?></td> 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo   number_format($item->balance_qty,3);;?></td> 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo   number_format($item->indent_raised_by_dtdo,3);;?> </td> 
							<?php
							
							$enc_indent_auto_id = $this->ci_jwt->jwt_web_encode(array('indent_info_id'=>$item->indent_info_id,'indent_auto_id'=>$item->indent_auto_id))
							
							?>
							<td><?php //echo $indent_info_gcc_submitted;?>
							 </td>
							
							
							
							
				</tr>
				 <?php $sno++;
				  } 
				  ?>
				  
				
                </tbody>
                
              </table>
			  
            </div>
		  
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example11').DataTable({
		"pageLength": 3000,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	   
      "autoWidth": true
    });
  });
</script>
	 
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 
    function validate(form) {
       
	   if(form.school_code.value.trim()=="")
	   {
		   alert("Please enter school_code");
		   form.school_code.focus();
		   return false;
	   }
	    if(form.school_date.value.trim()=="")
	   {
		   alert("Please select date");
		   form.school_date.focus();
		   return false;
	   }
    }
</script>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  function showElement(sval)
  {
	//  console.log(sval);
	  switch(sval)
	  {
		  case 'district':
							$("#fg_school").hide();
							$("#fg_district").show();
					break;
		  case 'school':
							$("#fg_school").show();
							$("#fg_district").hide();
					break;
		default: 
						$("#fg_school").hide();
							$("#fg_district").hide();
					break;
	  }
  }
  function validate(frm)
  {
	  
	  rtype = $('input[name=type]:checked').val();
	  
	  if(rtype=="school")
	  {
		  if($("#school_id").val()==""){
				  alert("Please select School ");
				  $("#school_id").focus();
				  return false ;
		  }
	  }
	   if(rtype=="district")
	  {
		   if($("#district_id").val()==""){
						  alert("Please select district ");
						  $("#district_id").focus();
						  return false ;
		   }
	  }
  }
  </script>

