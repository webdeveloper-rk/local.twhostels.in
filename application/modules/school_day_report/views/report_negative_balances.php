<style>
<div class="box box-info noprint">
            <div class="box-header with-border">
              <h3 class="box-title">Negative balances</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal noprint"   action=""  method="post"  >
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
			
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <input type="text" class="datepicker form-control" id="school_date" placeholder="Select Date" name="school_date"  required value="<?php echo $school_date;?>">
                  </div>
                </div>
				 
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer noprint">
                
                <input name="submit" type="submit" class="btn btn-info pull-right" value="Download">
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

<?php } ?>