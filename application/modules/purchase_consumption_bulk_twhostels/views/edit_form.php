<?php 
$from_date = '';
$to_date = '';
if($this->input->post('fromdate')!=null)
	$from_date = $this->input->post('fromdate');
if($this->input->post('todate')!=null)
	$to_date = $this->input->post('todate');


$price_read_only = false;
 if($this->config->item("site_name")=="twhostels")
 {
	 
	 
	  $price = $this->common_model->get_item_fixed_price($item_id,$school_id); 
	 $form_data['pprice'] = $price;
	 $form_data['bf_price'] = $price;
	 $form_data['lu_price'] = $price;
	 $form_data['sn_price'] = $price;
	 $form_data['di_price'] = $price;
 }
?>
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
.h1c
{
	font-size:12px;
	font-weight:bold;

}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<h2 class='red'> <?php echo $school_info->school_code. " - ".$school_info->name;?> - <?php echo $item_details->item_name;?></h2>
<h3>Purchase and consumption entries Form</h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<?php echo $this->session->flashdata('message');?>
<div style="background-color:#FFFFFF;">
<input type="hidden" name="action_url" id = "action_url">
<table id="mygrid" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Date</th>
                <th align='left'>Opening Quantity</th>
                <th>Purchased Quantity</th>
                <th>Purchased Price</th>
                <th>Vendor Name</th>
                <th>Used Quantity</th>
                <th>Price</th>
                <th>Closing Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
		
        <tfoot>
            <tr>
               <th>Date</th>
                <th align='left'>Opening Quantity</th>
                <th>Purchased Quantity</th>
                <th>Purchased Price</th>
				 <th>Vendor Name</th>
                <th>Used Quantity</th>
                <th>Price</th>
                <th>Closing Quantity</th>
				<th>Action</th>
            </tr>
        </tfoot>
    </table>
	</div>
	 <!-- Modal -->
            <div class="modal fade" id="empModal" role="dialog">
                <div class="modal-dialog">
                
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Purchase and Consumption</h4>
                        </div>
                        <div class="modal-body">
                          <iframe src="#" id="iframe_src" height="400" width="580" frameBorder="0"></iframe>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                  
                </div>
            </div>
			
			
			
			
<script type="text/javascript">
var table = $('#mygrid').DataTable( {
	"paging":   false,
        "ordering": false,
     ajax: "<?php echo site_url();?>purchase_consumption_bulk_twhostels/ajax_monthly_data/<?php echo $encoded_data;?>"
} );
	// table.ajax.reload();
	 // table.ajax.reload( null, false ); // user paging is not reset on reload
	 
	
	$("#contact_info").hide();
 	
	</script>
	
	<script type='text/javascript'>
            
$(document).ready(function() {
               // $('.purchase_info').click(function(){
               // $('a').on('click', 'a.purchase_info', function() { 
 $("body").on("click", "#mygrid tbody tr .purchase_info", function (e) {
			e.preventDefault(); 
             // console.log($(this).attr('href'));
				//$("#action_url").val($(this).attr('href'));
				//this_url = $(this).attr('href');
				//alert($(this).attr('href'));
				//$('.modal-body').html($(this).attr('href')); 

                            // Display Modal
                      $('#empModal').modal('show'); 
					  //$('#iframe_src').html(response); 
					   $('#iframe_src').attr('src', $(this).attr('href'))
                    // AJAX request
                   /* $.ajax({
                        url: $(this).attr('href'),
                        type: 'get',
						 
                         
                        success: function(response){ 
						// Display Modal
                            $('#empModal').modal('show'); 
                            // Add response in Modal body
                            $('.modal-body').html(response); 

                            
                        }
                    });*/
                });
             });
            </script>