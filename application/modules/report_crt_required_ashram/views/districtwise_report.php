<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> ASHRAM -  Districtwise Teacher Required Reports  </h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <?php 

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>

<?php echo $this->session->flashdata("message");?>
			<form   role="form" class="form-horizontal"   action=""  method="post"  onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
				   <?php if($this->session->userdata("user_role")=="subadmin"){?>		
 
					 
				  			 <div class="form-group" id="fg_district"   >
                  <label for="inputEmail3" class="col-sm-2 control-label">District Name:</label>

                  <div class="col-sm-10">
                  <select name="district_id" id="district_id"  required >
						<option value="">Select District</option>
						<?php 
						$dis_selected = $this->input->post('district_id');
						foreach($districts_list->result() as $districtObj)
						{
							$selected_text = '';
							if($dis_selected == $districtObj->district_id){
								$selected_text = ' selected ';
							}
						 ?>	
						<option <?php echo $selected_text ;?>  value="<?php echo $districtObj->district_id;?>"><?php echo $districtObj->name;?></option>
						<?php } ?>
					</select>
                    
                  </div>
                </div>
					 
					 
				  			 
				  
				   <?php } ?>
				   <input type="hidden" name="type" value="district">
				 
           						 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
               
			 <input type="submit"  name="submit"     class="btn btn-info pull-right" value="Download">
                <input type="submit"  name="submit"     class="btn btn-info pull-right" value="View Report">
                
              </div>
              <!-- /.box-footer -->
            </form>
			
			 
          </div>
		  
	 
          
		 <?php if($display_result ==true) {
		
		 ?>

 
<h3>TRIBAL WELFARE DEPARTMENT  ,<?php echo $rdata['district_name'];?> DISTRICT  - ASHRAM CRT REQUIRED Report 												
 </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			   <a href='#' class='btn btn-info pull-right noprint' onclick='javascript:window.print();'>Print</a>
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info" style="font-size:12px;">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;"> S.NO</th>
				 																

				 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Name of Institution/Location</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Agency / Non Agency</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">SGT</th>
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">S.A(Maths)/ Science</th>
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">SA (SS)- Social</th>
				
				 
				<th class="sorting" style="width: 75px;">S.A(English)</th>
				<th class="sorting" style="width: 75px;">LP-I(TP 11)</th>
				<th class="sorting" style="width: 75px;">LP-2(HP 11)</th>
				<th class="sorting" style="width: 75px;">S.A.(Maths)</th>
				<th class="sorting" style="width: 75px;">S.A.(Phy. Sci.)</th>
				<th class="sorting" style="width: 75px;">S.A.(Bio. Sci.)</th>
				<th class="sorting" style="width: 75px;">S.A. (1st Lang.)-Telugu</th>
				<th class="sorting" style="width: 75px;">S.A. (2nd Lang.)-Hindhi</th>
				<th class="sorting" style="width: 75px;">S.A./ (PET)</th>
				<th class="sorting" style="width: 75px;">PET</th><!--Craft/ Drawing / Music-->
				<th class="sorting" style="width: 75px;">Total</th> 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 $sno = 0;
				 $grand_total = 0;
				 $posts_total = array();
				 $posts_array = array(2,4,5,6,7,8,10,11,12,13,14,15,16);
				foreach($rows as  $index=>$crt_required_data){ 
				$sno++;
				$school_info = $crt_required_data['school_info'];
				$posts_info = $crt_required_data['posts'];
				 
						
						
						$total_required = 	intval($posts_info[2])+
											intval($posts_info[4])+
											intval($posts_info[5])+
											intval($posts_info[6])+
											intval($posts_info[7])+
											intval($posts_info[8])+
											intval($posts_info[10])+
											intval($posts_info[11])+
											intval($posts_info[12])+
											intval($posts_info[13])+
											intval($posts_info[14])+
											intval($posts_info[15])+
											intval($posts_info[16]);
						foreach($posts_array as $post_id)
						{
							$posts_total[$post_id] = intval($posts_total[$post_id]) + intval($posts_info[$post_id]);
						}		
					$grand_total =  $grand_total + 	$total_required;			
											
											

				?>
						<tr>
								<td><?php echo $sno ;?></td>
								<td><?php echo $school_info->name;?></td>
								<td><?php echo $school_info->school_agency;?></td>
						
							<td  ><?php echo intval($posts_info[2]);?></td> 
							<td  ><?php echo intval($posts_info[4]);?></td> 
							<td  ><?php echo intval($posts_info[5]);?></td>  
							<td  ><?php echo intval($posts_info[6]);?></td>
							<td ><?php echo intval($posts_info[7]);?></td>
							<td ><?php echo intval($posts_info[8]);?></td>
							<td ><?php echo intval($posts_info[10]);?></td>
							<td  ><?php echo intval($posts_info[11]);?></td>
							<td ><?php echo intval($posts_info[12]);?></td>
							<td ><?php echo intval($posts_info[13]);?></td>
							<td  ><?php echo intval($posts_info[14]);?></td>
							<td ><?php echo intval($posts_info[15]);?></td>
							<td  ><?php echo intval($posts_info[16]);?></td>
							<td  ><b><?php echo $total_required;?></b></td> 
				</tr> 
					 
				<?php  } ?>
				
				<tr style="font-weight:bold;">
								<td> </td>
								<td> </td>
								<td><b>Totals</b></td>
						
							<td  ><?php echo intval($posts_total[2]);?></td> 
							<td  ><?php echo intval($posts_total[4]);?></td> 
							<td  ><?php echo intval($posts_total[5]);?></td>  
							<td  ><?php echo intval($posts_total[6]);?></td>
							<td ><?php echo intval(0);?> </td><!--LP 1-- $posts_total[7]-->
							<td ><?php echo intval(0);?> </td><!--LP 1   -- $posts_total[8] -->
							<td ><?php echo intval($posts_total[10]);?></td>
							<td  ><?php echo intval($posts_total[11]);?></td>
							<td ><?php echo intval($posts_total[12]);?></td>
							<td ><?php echo intval($posts_total[13]-$posts_total[7]);?></td><!-- SA TELUGU -minus LP 1 -->
							<td  ><?php echo intval($posts_total[14]-$posts_total[8]);?></td><!-- SA HINDHI -minus LP 2 -->
							<td ><?php echo intval($posts_total[15]-$posts_total[16]);?></td><!-- S.A./ (PET) -minus PET(CRAFT/Music) -->
							<td  ><?php echo intval(0);?> </td><!--PET CRAFT/Music---- $posts_total[16] -->
							<td  ><?php echo $grand_total ;?></td> 
				</tr> 
					 
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
 
    $('#example1').DataTable({
		"pageLength": 300,
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": true,
	  
      "autoWidth": true
    });
  });
</script>
	   <!-- /.box-body -->
         
<?php } ?> </div>