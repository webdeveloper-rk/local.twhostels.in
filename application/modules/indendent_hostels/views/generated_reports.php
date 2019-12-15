<section class="content">
 

<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">      Generated Indents   </h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
		  </div>
		  
		  
		  
		  <div class="box">
            <div class="box-header">
             <h4><b>  Indent  Reports of Hostels </b> </h4>
            </div></b>
            <!-- /.box-header -->
            
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Sno</th>
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th> 
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Date</th> 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">View</th> 
				  
		 
		
</tr>
                </thead>
                <tbody>
                 <?php 
				 $sno = 1;
				 $total_consumed = 0;
				 
				  
				 foreach($generated_list->result() as $item) { 
					//print_a($item,1);
				 
				 $vieweditencoded =   $this->ci_jwt->jwt_web_encode(array('indent_info_id'=>$item->indent_info_id,'indent_date'=>$item->indent_date,"school_id"=>$item->school_id,'dp_date'=>$item->dp_date));
				 ?>
				 <tr  >
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $sno;?></td> 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo  $schools_names[$item->school_id];?></td> 
							
							
							
							 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo  $item->dp_date;?></td> 
							
							 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
							aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;">
							<a href="<?php echo site_url("indendent_hostels/viewindent/".$vieweditencoded); ?>" class="btn btn-info pull-right">View   </a>  
							<a href="<?php echo site_url("indendent_hostels/downloadindent/".$vieweditencoded); ?>" class="btn btn-info  ">Download   </a>
							
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

