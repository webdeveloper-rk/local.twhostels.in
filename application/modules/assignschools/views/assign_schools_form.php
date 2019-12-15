<style>.daily table td{	padding:5px;}</style><section class="content noprint">
<div class="box box-info noprint">
            <div class="box-header with-border">
              <h3 class="box-title"><b><?php echo $user_row->school_code . " - " .$user_row->name;?> </b>- Assigned Schools</h3>	<a href="<?php echo site_url("manage/atdos");?>" class="btn btn-info pull-right">Go back </a>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <?php echo $this->session->flashdata('message'); ?><div style="color:#ff0000;padding:10px;font-weight:bold;"><?php echo validation_errors();?></div>
			<form   role="form" class="form-horizontal noprint"   action=""  method="post"  >				<input type="hidden" name="action" value="assignschools">
              <div class="box-body">
				  <div id="changepwdnotifier"></div> 
				<div class="form-group">                  					<div style="margin-left:30px;">						<table>						<?php 						foreach($rset->result() as $row){						?>						<tr><td><input <?php  if(in_array($row->school_id,$checked_list)){ echo " checked "; }   ?> type="checkbox" name="school_ids[]" value="<?php echo $row->school_id;?>" id="id_<?php echo $row->school_id;?>"> <label for="id_<?php echo $row->school_id;?>"><?php echo $row->school_code." - ".$row->name . " - ".$row->district_name;;?></label></td></tr>						<?php 												}																		?>												</table>															</div>                  <div class="col-sm-10">                                    </div>                </div> 
			
				 
				 
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer noprint">
                
                <button type="submit" class="btn btn-info pull-right">Update</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
      
 	 
 