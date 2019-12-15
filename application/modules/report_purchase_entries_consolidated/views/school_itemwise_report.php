<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">  Monthly items Purchase Report</h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"  >
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
				  
	  
					 <?php  if($this->session->userdata("user_role") != "school")
					{?>
					 
					   <div class="form-group">

				 

				 <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>



                  <div class="col-sm-10">  <?php $school_id=$this->input->post("school_id"); echo  school_selection($school_id);?>

                  </div>

				  

                </div>
					<?php } ?>
					  
				  
						 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Month & Year:</label>

                  <div class="col-sm-10">
                   	<select name="month" required>
						<option value="">Select Month</option>
						<?php 
						   $month_selected = $this->input->post('month'); 
						 	  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
						
						for($lmonth=1;$lmonth<13;$lmonth++)
						{
							$month_name = $months[$lmonth];
							if($lmonth < 10)
									$lmonth = "0".$lmonth;
								
								$selected_text = '';
							if($month_selected == $lmonth){
								$selected_text = ' selected ';
							}
							
							?>
						<option <?php echo $selected_text ;?>   value="<?php echo $lmonth;?>"><?php echo $months[$lmonth];?></option>
						<?php } ?>
					</select>
                   	<select name="year"  required>
						<option value="">Select Year</option>
						<?php 
						 $year_selected = $this->input->post('year'); 
						$sdyear = 2017;
						$pyear = date('Y');
						
						for($lyear=$sdyear;$lyear<=$pyear;$lyear++)
						{
								$selected_text = '';
							if($year_selected == $lyear){
								$selected_text = ' selected ';
							}
							?>
						<option  <?php echo $selected_text ;?>    value="<?php echo $lyear;?>"><?php echo $lyear;?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
			 
			 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" name="submit"  value="getreport" class="btn btn-info pull-right">Get Report</button> 
			 
                <button type="submit"  name="submit"  value="download"  class="btn btn-info pull-right">Download</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		  
		  <?php if($display_result==true){ ?>
		  
		  <div class="box">
            <div class="box-header">
             <h4>   Purchase Report of <b>
			 <?php echo  $rdata['month_name'];?> <span style="color:#FF0000;"><?php if($rdata['report_for']!="") { echo "-". $rdata['report_for'];} ?></span></b></b> </h4>
            </div>
            <!-- /.box-header -->
            
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Sno</th>
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Date</th>
			 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Item name</th> 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Vendor type</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Vendor name</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Bank name</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Bank IFSC</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Bank Account Number</th> 
				
			 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Purchase Qty</th> 
				 
		 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Purchase Price</th>

 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Price</th> 				
				 
</tr>
                </thead>
                <tbody>
                 <?php 
				 $sno = 1;
				 $total_purchased = 0;
				 $total_purchased_price = 0;
				 $purchased_total = 0;
				 
				 $rset = $rdata['rset'];
				 foreach($rset->result() as $item) {
 				 
				  $purchased_total = $item->purchase_quantity * $item->purchase_price;
				  $total_purchased_price = $total_purchased_price + $purchased_total;
				  
				     
				 ?>
				 <tr  >
				 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $sno;?></td>
				<td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->entry_date_dp;?></td>
				  <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->item_name;?></td>
				 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->vendor_type;?></td>
				  <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->vendor_name;?></td>
				 
				 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->vendor_bank;?></td>
				 
				 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->vendor_bank_ifsc;?></td>
				 
				 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->vendor_account_number;?></td>
				 
				 
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><?php echo $item->purchase_quantity;?></td>
				 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><?php echo $item->purchase_price;?></td>
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><?php echo number_format($purchased_total,2);?></td>
</tr>
				 <?php $sno++;
				  } 
				  ?>
				   <tr class="bold" >
				  
				  <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="6" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;" align="right"><b>Total Purchased Amount</b></td> 
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;" align="left"><b><?php echo number_format($total_purchased_price,2);?></b></td>
		
				 
				
                </tbody>
                
              </table>
			  
            </div>
		  <?php } ?>
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

