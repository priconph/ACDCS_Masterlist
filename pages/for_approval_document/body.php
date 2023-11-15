<section class="main">		
		<section class="tab-content" id="tab-content">
		   <section class="tab-pane active fade in content" id="iqc">
				<div class="row">
				   	<div class="col-xs-12">
						<legend style="margin-bottom:0px;"><a href="#" id="a_menu"><i class="fa fa-puzzle-piece"></i> ACDCS Document Review Process</a></legend>
					    <ol class="breadcrumb">
						  <li id="bc_menu"><a href="#" class="active"><i class="fa fa-home"></i> Home</a></li>
	
						</ol>
						<div class="row" id="container_menu">
							<?php //include('main_menu.php'); omodify ?>
						</div>
				   	</div>
					<div class="col-sm-12">
						<div class="panel panel-info" style="overflow:scroll;">
							<div class="panel-heading"><i class="fa fa-file fa-lg"> For Approval</i></div>
							<div class="panel-body">
								<div class="col-sm-12">
									<?php
										// if($sa_user_access){
										// 	echo '<button class="btn btn-success fa fa-plus pull-right" id="btn_sa"> New Special Acceptance</button>';
										// }
									?>
									<button class="btn btn-primary btn-lg fa pull-right" id="btnBatchApproval"> For Approval <span id="countApproval">(5)</span> </button>

								</div> <br><br><br>
									<div class="col-sm-12">
										<table class="table table-condensed table-bordered table-responsive" id="tbl_masterlist_temporary">
											<thead>
												<tr>
													<th></th>
													<th>Approved by</th>
													<th>Approval ID</th>
													<th>Document ID</th>
													<th>Document Status</th>
													<th>Disposition</th>
													<!-- <th>Number of Review</th> -->
													<th>Date Received</th>
													 <th>Model</th>
													<th>Title</th>
													<th>Document No.</th>
													<th>Rev No.</th>
													<th>Orignator Code</th>
													<!--<th>Document Type</th>
													<th>Effectivity Date</th>
													<th>Expiration Date</th> -->

													<!-- <th>Reviewed by</th>
													<th>Date of review</th>
													<th>Person in charge</th>
													<th>Due Date</th>
													<th>Recommitment Date</th>
													<th>Date of DCC Email</th>
													<th>Process Owner Remarks</th>
													<th>DCC Remarks</th> -->
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--  -->
				</div>
			</section>
		</section>
	</section>
	
	<!-- <th><span class="fa fa-attachment"></span>File Attachment</th> -->
<!---------------------
	Modals - Start 
---------------------->
<!-- Modal for Approval -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalForApproval">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-question-circle-o"></i> System Confirmation</h4>
      </div>
		<div class="modal-body">
		<!-- <div class="row" id="">
			<div class="col-sm-12" id="">				
				<label>Remarks: </label>
			</div>
			<div class="col-sm-12">				
				<textarea id="remarks" name="remarks" style="width:100%;" required></textarea>
			</div>
		</div> -->
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success fa fa-thumbs-o-up " id="btnApproved"> Approved</button>
			<button type="button" class="btn btn-danger fa fa-thumbs-o-down" id="btnDisapproved"> Disapproved</button>
		</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for Approver Decision -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalApproverDecision">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-question-circle-o"></i> System Confirmation</h4>
      </div>
	  <form id="formApproverDecision">
		  <div class="modal-body">
		  <div class="row">
				<div class="col-sm-12" id="">				
					<div class="alert" role="alert" id="alertOption">
					</div>
				</div>
			</div>
			<div class="row" id="">
				<div class="col-sm-12" id="">				
					<label>Remarks: </label>
				</div>
				<div class="col-sm-12">				
					<textarea id="remarks" name="remarks" style="width:100%;" required></textarea>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary fa fa-save" id="btn_yes_remove_attachment"> Yes</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> No</button>
		  </div>
		</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Modal Alert -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_alert">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa fa-warning"></i> Error Found</h4>
      </div>
		<div class="modal-body">
		<div class="row">
			<div class="col-sm-12" id="">				
				<div class="alert alert-danger" role="alert" id="alert_type">
					<li class="fa fa-warning" style="font-size:36px;"></li> <h4 id="alert_message"></h4>
				</div>
			</div>
		</div>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> Close</button>
		</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="js/common_functions.js?23456"></script>
<script>
$(document).ready(function () {
	
	$('.modal').on('hidden.bs.modal', function (e) {
		/* Allows the overlayed modal to be scrollable */
		if($('.modal').hasClass('in')) {
			$('body').addClass('modal-open');
		}   
		
	});

	
	/* ***************************
				Start
	*************************** */
	var arrApprovalId = [];
	var handler_qfr = 'handler_qfr';
	var masterlist_temporary_handler = "handler/masterlist_temporary_handler.php";
	var $formApproverDecision 	= $('#formApproverDecision');
	
	var dt_special_acceptance = $(tbl_masterlist_temporary).DataTable({
		"aaSorting"	: [],
		"bProcessing": true,
        "bServerSide": true,
		"columnDefs":[
            {"orderable":false,"targets":[0,1,2,3,4,5]}
        ],
		"sAjaxSource": "server_side_scripts/dt_masterlist_for_approval.php?username="+"<?php echo $_SESSION['username'];?>",
		"drawCallback": function( settings ) {
			$('#tbl_special_acceptance').attr('style','width:100%;')
		}
	});

	$(tbl_masterlist_temporary).on('click','#cBoxBatchSelect', function () {
		approvalId = $(this).attr('data-id');
		if ($(this).prop('checked')) {
			$(this).each(function () {
				arrApprovalId.push(approvalId);
				console.log('push_fkid_document',arrApprovalId);
			});
		}else{
			$(this).each(function () {
				let indexApprovalId = arrApprovalId.indexOf(approvalId);

				arrApprovalId.splice(indexApprovalId, 1);
				console.log('splice_fkid_document',arrApprovalId);
			});
		}
	});

	$('#btnBatchApproval').click(function (e) { 
		e.preventDefault();
        /* Filtered the duplicate number before submiting to BatchApproval */
		const filteredArrApprovalId = Array.from(new Set(arrApprovalId));
		// approverDecision (filteredArrApprovalId);
		$('#modalForApproval').modal();
	});
	var alertSuccess = 'alert-success';
	var alertDanger = 'alert-danger';
	$('#btnApproved').click(function (e) { 
		e.preventDefault();
		// alert('btnApproved');
		$formApproverDecision.find('#alertOption').addClass(alertSuccess);
		$formApproverDecision.find('#alertOption').removeClass(alertDanger);
		$formApproverDecision.find('#alertOption').html('Are you sure you want to approve the request?');
		$('#modalApproverDecision').modal();
	});
	
	$('#btnDisapproved').click(function (e) { 
		e.preventDefault();
		$formApproverDecision.find('#alertOption').addClass(alertDanger);
		$formApproverDecision.find('#alertOption').removeClass(alertSuccess);
		$formApproverDecision.find('#alertOption').html('Are you sure you want to disapprove the request?');
		$('#modalApproverDecision').modal();
	});

	$formApproverDecision.submit(function (e) { 
		e.preventDefault();
		formApproverDecision.find('#alertOption')
		approverDecision (filteredArrApprovalId);
	});
	/*
		Function
	*/
	function approverDecision (filteredArrApprovalId){
		let data = {'action':'save_approver_decision','filtered_arr_approval_id':filteredArrApprovalId}
		call_ajax(data, masterlist_temporary_handler,function(result){
			console.log(result);
		})
	}


	/*
	arrApprovalId = []; 
	$('#cBoxBatchSelect').prop('checked',false);
	
	*/


});
</script>





