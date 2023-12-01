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
							<div class="panel-heading"><i class="fa fa-file fa-lg"> For Review</i></div>
							<div class="panel-body">
								<div class="col-sm-12">
									<?php
										// if($sa_user_access){
										// 	echo '<button class="btn btn-success fa fa-plus pull-right" id="btn_sa"> New Special Acceptance</button>';
										// }
									?>
								</div> <br><br><br>
									<div class="col-sm-12">
										<table class="table table-condensed table-bordered table-responsive" id="tbl_masterlist_temporary">
											<thead>
												<tr>
													<th></th>
													<th><span class="fa fa-cogs"></span></th>
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
													<th>Doc Type</th>
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
<!-- Modal for system message -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_remove_attachment_confirmation">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-question-circle-o"></i> System Confirmation</h4>
      </div>
	  <form id="form_remove_attachment_confirmation">
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-12" id="">				
					<div class="alert alert-danger" role="alert" id="">
						<h4>Are you sure you want to delete the document? <br> *Document will permanently be deleted to server.</h4>
					</div>
				</div>
			</div>
			<div class="row" id="">
				<div class="col-sm-12" id="">				
					<label>If yes, please write the reason:</label>
				</div>
				<div class="col-sm-12">				
					<textarea id="reason" name="reason" style="width:100%;" required></textarea>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary fa fa-trash" id="btn_yes_remove_attachment"> Yes</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> Close</button>
		  </div>
		</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal for Approver Decision -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_approver_decision">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-question-circle-o"></i> System Confirmation</h4>
      </div>
	  <form id="form_remove_attachment_confirmation">
		  <div class="modal-body">
			<div class="row" id="">
				<div class="col-sm-12" id="">				
					<label>If yes, please write the reason:</label>
				</div>
				<div class="col-sm-12">				
					<textarea id="reason" name="reason" style="width:100%;" required></textarea>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary fa fa-trash" id="btn_yes_remove_attachment"> Yes</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> Disapproved</button>
		  </div>
		</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Modal Alert  #modal_alert-->
<?php
	include_once('pages/components/modal.php');
?>

<!---------------------
	Modals - Add Review 
---------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="modalReviewDocu">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Review</h4>
      </div>
	  <form id="formReviewDocu">
		  <div class="modal-body">
				<div class="row">
					<div class="col-sm-12">		
						<input type="text" name="doc_review_masterlist_id" id="doc_review_masterlist_id" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">		
						<label>Approved by:</label>
					</div>
					<div class="col-sm-12">		
						<select class="form-control" id="sec_heads_username" name="sec_heads_username" style="width:100%;" required>
						</select>
					</div>
					<div class="col-sm-12">		
						<label>Disposition:</label>
					</div>
					<div class="col-sm-12">				
						<select class="form-control" id="disposition" name="disposition" style="width:100%;" required>
							<option value="" selected disabled>--Select--</option>
							<option value="1">Revision</option>
							<option value="2">Retain</option>
							<option value="3">Obsolete</option>
							<option value="4">Extended</option>
						</select>
					</div>
				</div>
				<div class="row" id="isRevisionRow" style="display:none;">
					<div class="col-sm-12">				
						<label>Person in charge</label>
					</div>
					<div class="col-sm-12">
						<!-- <input type="text"> -->
						<select class="form-control" id="person_in_charge" name="person_in_charge" style="width:100%;"></select>
					</div>
					<div class="col-sm-12">				
						<label>Due Date</label>
					</div>
					<div class="col-sm-12">				
						<input type="date" class="form-control" id="due_date" name="due_date" style="width:100%;" required>
					</div>
				</div>
				<div class="row" id="isExtension" style="display:none;">
					<div class="col-sm-12">				
						<label>Extension Date</label>
					</div>
					<div class="col-sm-12">				
						<input type="date" class="form-control" id="extension_date" name="extension_date" style="width:100%;" required>
					</div>
					<div class="col-sm-12">				
						<input type="file" class="form-control" id="file_memo" name="file_memo" style="width:100%;">
					</div>
				</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary fa fa-save" id="btn_yes_remove_attachment"> Save</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> Close</button>
		  </div>
		</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!---------------------
	Modals - Add Review 
---------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="modalDocReviewDisposition">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> View Details</h4>
      </div>
	  <form id="formDocReviewDisposition">
		  <div class="modal-body">
				<div class="row">
					<div class="col-sm-12">		
						<input type="text" name="doc_review_masterlist_id" id="doc_review_masterlist_id" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">		
						<label>Approved by:</label>
					</div>
					<div class="col-sm-12">		
						<select class="form-control" id="sec_heads_username" name="sec_heads_username" style="width:100%;" required>
						</select>
					</div>
					<div class="col-sm-12">		
						<label>Disposition:</label>
					</div>
					<div class="col-sm-12">				
						<select class="form-control" id="disposition" name="disposition" style="width:100%;" required>
							<option value="" selected disabled>--Select--</option>
							<option value="1">Revision</option>
							<option value="2">Retain</option>
							<option value="3">Obsolete</option>
							<option value="4">Extended</option>
						</select>
					</div>
				</div>
				<div class="row" id="isRevisionRow" style="display:none;">
					<div class="col-sm-12">				
						<label>Person in charge</label>
					</div>
					<div class="col-sm-12">
						<!-- <input type="text"> -->
						<select class="form-control" id="person_in_charge" name="person_in_charge" style="width:100%;"></select>
					</div>
					<div class="col-sm-12">				
						<label>Due Date</label>
					</div>
					<div class="col-sm-12">				
						<input type="date" class="form-control" id="due_date" name="due_date" style="width:100%;" required>
					</div>
				</div>
				<div class="row" id="isExtension" style="display:none;">
					<div class="col-sm-12">				
						<label>Extension Date</label>
					</div>
					<div class="col-sm-12">				
						<input type="date" class="form-control" id="extension_date" name="extension_date" style="width:100%;" required>
					</div>
					<div class="col-sm-12">				
						<input type="file" class="form-control" id="file_memo" name="file_memo" style="width:100%;">
					</div>
				</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary fa fa-save" id="btn_yes_remove_attachment"> Save</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> Close</button>
		  </div>
		</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script src="js/common_functions.js?23456"></script>
<script>
$(document).ready(function () {
	/* Date Range Picker */
	function date_time_picker(id) {
		$('#'+id).daterangepicker(
				{
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
						'Last 7 Days': [moment().subtract('days', 6), moment()],
						'Last 30 Days': [moment().subtract('days', 29), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
					},
					startDate: moment().subtract('days', 29),
					endDate: moment()
				},
			function(start, end) {
				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
				if(id == "date_created_search_group") {
					load_all_control_number_per_date_created('control_number_search_list_group','control_number_search',$('#date_created_search_group').val());
				}
			}
		);
		return false;
	}
	
	$('.modal').on('hidden.bs.modal', function (e) {
		/* Allows the overlayed modal to be scrollable */
		if($('.modal').hasClass('in')) {
			$('body').addClass('modal-open');
		}    
	});

	/* ***************************
				Start
	*************************** */
	var arr_doc_review_masterlist_id = [];
	var handler_ = 'handler_qfr';
	var masterlist_temporary_handler = 'handler/masterlist_temporary_handler.php'
	var $formReviewDocu = $('#formReviewDocu');
	var $formDocReviewDisposition = $('#formDocReviewDisposition');
	var dt_special_acceptance = $(tbl_masterlist_temporary).DataTable({
		"aaSorting"	: [],
		"bProcessing": true,
        "bServerSide": true,
		"sAjaxSource": "server_side_scripts/dt_masterlist_temporary.php",
		"drawCallback": function( settings ) {
			$('#tbl_special_acceptance').attr('style','width:100%;')
		}
	});

	function readTemporaryReviewDocu(dataId){
		let data = {'action':'read_temporary_review_docu','temporary_document_id':dataId}

		call_ajax(data, masterlist_temporary_handler,function(result){
			console.log(result);
			$('#modalDocReviewDisposition').modal();
		})
	}

	$(tbl_masterlist_temporary).on('click','#btnReviewDocu',function (e) { 
		e.preventDefault()
		$('#modalReviewDocu').modal()
		$formReviewDocu.find('#doc_review_masterlist_id').val($(this).attr('data-id'))
		re_initialize_select2_server_side('#modalReviewDocu #person_in_charge','#modalReviewDocu #formReviewDocu',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");
		re_initialize_select2_server_side('#modalReviewDocu #sec_heads_username','#modalReviewDocu #formReviewDocu',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");

	});

	$(tbl_masterlist_temporary).on('click','#cBoxBatchSelect' ,function () {
		let doc_review_masterlist_id = $(this).attr('data-id');

		if ($(this).prop('checked')) {
			$(this).each(function () {
				arr_doc_review_masterlist_id.push(doc_review_masterlist_id);
				console.log('Checked '+arr_doc_review_masterlist_id);
			});
		}else{
			$(this).each(function () {
				let index = arr_doc_review_masterlist_id.indexOf(doc_review_masterlist_id);
				arr_doc_review_masterlist_id.splice(index, 1);
				console.log('Unchecked '+arr_doc_review_masterlist_id);
			});
		}
	});

	$(tbl_masterlist_temporary).on('click','#btnViewDocReviewDisposition' ,function () {
		let doc_review_masterlist_id = $(this).attr('data-id');
		console.log('doc_review_masterlist_id',doc_review_masterlist_id);
		let data = {
			'action' : 'read_doc_review_masterlist',
			'doc_review_masterlist_id' : doc_review_masterlist_id,
		}
		call_ajax(data, masterlist_temporary_handler,function(result){
			console.log('disposition',result['disposition']);
			let disposition = result['disposition'];
			
			$('#modalDocReviewDisposition').modal();
			$formDocReviewDisposition.find('#doc_review_masterlist_id').val(doc_review_masterlist_id);
			$formDocReviewDisposition.find('#disposition').val(disposition);
			assign_value_select2('#formDocReviewDisposition #sec_heads_username',result['sec_heads_username']);
			if(disposition === '1'){
				$formDocReviewDisposition.find('#due_date').val(result['due_date']);
				assign_value_select2('#formDocReviewDisposition #person_in_charge',result['person_in_charge']);
			}else if(disposition === '4'){
				$formDocReviewDisposition.find('#extension_date').val(result['extension_date']);
			}
			re_initialize_select2_server_side('#modalDocReviewDisposition #person_in_charge','#modalDocReviewDisposition #formDocReviewDisposition',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");
			re_initialize_select2_server_side('#modalDocReviewDisposition #sec_heads_username','#modalDocReviewDisposition #formDocReviewDisposition',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");
			dispositionStatus($formDocReviewDisposition,disposition);

		});
	});
	
	$('#btnBatchApproval').click(function (e) { 
		e.preventDefault();
		const filtered_doc_review_masterlist_id = Array.from(new Set(arr_doc_review_masterlist_id));
		console.log(filtered_doc_review_masterlist_id);
		$('#modal_approver_decision').modal();

	});
	
	$formReviewDocu.find('#disposition').change(function (e) { 
		e.preventDefault()
		changeEventDisposition($formReviewDocu,$(this).val());
	});

	$formDocReviewDisposition.find('#disposition').change(function (e) { 
		e.preventDefault();
		console.log($formDocReviewDisposition);
		changeEventDisposition($formDocReviewDisposition,$(this).val());
	});

	function changeEventDisposition($formId,disposition){
		$formId.find('#person_in_charge').empty()
		$formId.find('#due_date').val('')
		$formId.find('#extension_date').val('')
		$formId.find('#file_memo').val('')
		dispositionStatus($formId,disposition);
	}

	function dispositionStatus($formId,disposition){
		if(disposition === '1'){
			$formId.find('#isRevisionRow').show()
			$formId.find('#isExtension').hide()
			$formId.find('#due_date').attr('required',true)
			$formId.find('#extension_date').removeAttr('required',true)
		}else if(disposition === '4'){
			$formId.find('#isRevisionRow').hide()
			$formId.find('#isExtension').show()
			$formId.find('#due_date').removeAttr('required',true)
			$formId.find('#extension_date').attr('required',true)
		}else{
			$formId.find('#isRevisionRow').hide()
			$formId.find('#isExtension').hide()
			$formId.find('#due_date').removeAttr('required',true)
			$formId.find('#extension_date').removeAttr('required',true)
		}
	}

	$formReviewDocu.submit(function (e) { 
		e.preventDefault();
		var form_data = new FormData(this);
		form_data.append("action","create_disposition_details")
		// form_data.append("pkid",$('#modal_sa_edit').data('id'));
		// form_data.append("username",username);
		call_ajax_attachment(form_data, masterlist_temporary_handler, function(response){
			if(response['is_success'] === 'true'){
				$('#modalReviewDocu').modal('hide')
				notif_success('Save Successfully')
				dt_special_acceptance.draw()
			}else{
				$('#modalReviewDocu').modal('hide');
				$('#alert_message').text(response['result_msg'])
				$('#modal_alert').modal()
			}
		});
	});
	
	/* ***************************
			Closing of Modal
	*************************** */
	$('#modalReviewDocu').on('hidden.bs.modal', function (e) {
		$formReviewDocu[0].reset()
		$('#sec_heads_username').empty()
		$('#person_in_charge').empty()
		$('#isRevisionRow').hide()
		$('#isExtension').hide()
	});
	
});
</script>





