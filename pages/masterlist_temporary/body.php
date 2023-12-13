<?php
use Pages\GetUserRoles\Rapid;
$is_dcc_staff = Rapid::getIsDccStaff();
$is_dcc_access = ($is_dcc_staff['department'][0] =="Quality Assurance Dept." OR $is_dcc_staff['section'][0] == "EMO/QMS" OR $is_dcc_staff['division'][0] =="Quality Assurance Di" ) ? 'true': 'false';
?>

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
					<!-- Nav tabs -->
					<div class="col-sm-12">
						<div class="">
							<ul class="nav nav-tabs" role="tablist">
							<?php 
								if($is_dcc_access == 'true'){
									echo '
										<li class="nav-item active">
												<a class="nav-link" data-toggle="tab" href="#menu1">Pending</a>
										</li>
									
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#menu3">For Review</a>
										</li>
									';
									$dcc_active = 'active';
									$process_owner_active = '';
								}else{
									echo '
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#menu2">Production - Active Document</a>
										</li>
										<li class="nav-item  active">
											<a class="nav-link " data-toggle="tab" href="#menu3">For Review</a>
										</li>
									';
									$dcc_active = '';
									$process_owner_active = 'active';
								}
								
							?>
							</ul>
						</div>
					</div>
					<!-- Tab content -->
					<div class="tab-content">
						<div role="tabpanel" id="menu1" class="tab-pane <?php echo $dcc_active?>"><br>
							<div class="col-sm-12">
							<div class="panel panel-info" style="overflow:scroll;">
								<div class="panel-heading"><i class="fa fa-file fa-lg"> Pending</i></div>
									<div class="panel-body">
											<iframe src="../../ACDCS/dcc_home.php?page=./search_masterlist_temporary.php" width="100%" height="100%" style="border:none;" title="Pending">
											</iframe>
									</div>
								</div>
							</div> <!--end div panel info -->
						</div> <!--end tabpanel-->
						<!-- <div role="tabpanel" id="menu2" class="tab-pane <?php //echo $process_owner_active?>"><br> -->
						<div role="tabpanel" id="menu2" class="tab-pane"><br>
							<div class="col-sm-12">
							<div class="panel panel-info" style="overflow:scroll;">
								<div class="panel-heading"><i class="fa fa-file fa-lg"> Production - Active Document</i></div>
									<div class="panel-body">
										<iframe src="../../ACDCS/prdn_home.php" width="100%" height="100%" style="border:none;" title="Pending">
										</iframe>
									</div>
								</div>
							</div> <!--end div panel info -->
						</div> <!--end tabpanel-->
						<div role="tabpanel" id="menu3" class="tab-pane <?php echo $process_owner_active?>"><br>
							<div class="col-sm-12">
							<div class="panel panel-info" style="overflow:scroll;">
								<div class="panel-heading"><i class="fa fa-file fa-lg"> For Review</i></div>
								<div class="panel-body">
									<div class="card" style="border-color: #D3D3D3;">
										<div class="card-body">
											<div class="row">

												<div class="col-sm-4">
												<span class="badge highlight-color-yellow">FOR REVIEW</span>
												<label><b>- Process Owner</b></label>
												</div>
												<div class="col-sm-4">	
												<span class="badge highlight-color-lime">FOR APPROVAL</span>
												<label><b>- Section / Dept Head</b></label>
												</div>
												<div class="col-sm-4">	
												<span class="badge highlight-color-blue">DCC CONFORMANCE</span>
												<label><b>- DCC / QAS</b></label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<!-- <button class="btn btn-success fa fa-excel-o pull-right" id="btnExportDocReviewMasterlist"> Export </button> -->
									</div> <br><br>
										<div class="col-sm-12">
											<table class="table table-condensed table-bordered table-responsive" id="tbl_masterlist_temporary">
												<thead>
													<tr>
														<th></th>
														<th><span class="fa fa-cogs"></span></th>
														<!-- <th>Document ID</th> -->
														<th>Document Status</th>
														<th>Disposition</th>
														<!-- <th>Number of Review</th> -->
														<th>Date Received</th>
														<th>Expiration Date</th>
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
							</div> <!--end div panel info -->
						</div> <!--end tabpanel-->
					</div> <!--end tab-content--> 
				</div>
			</section>
		</section>
</section>
	
	<!-- <th><span class="fa fa-attachment"></span>File Attachment</th> -->
<!---------------------
	Modals - Start 
---------------------->

<!-- Modal for Approver Decision -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalAdvanceSearch">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-question-circle-o"></i> Export Data</h4>
      </div>
	   <form id="formAdvanceSearch">
		  <div class="modal-body">
			<div class="row" id="">
				<div class="col-sm-4">	
					<label>Expiration Date:</label>
				</div>
				<div class="col-sm-8">				
					<input type="text" class="form-control" id="expiration_date_from_to" name="expiration_date_from_to" required></input>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-success fa fa-export" id="btn_search"> Export</button>
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
			<button type="submit" class="btn btn-primary fa fa-check" id="btn_yes_remove_attachment"> Yes</button>
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
				<div class="row" style="display:none;">
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
	Modals - View Review 
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
				<div class="row" style="display:block;">
					<div class="col-sm-3">		
						<label>DocMasterListId:</label>
					</div>
					<div class="col-sm-3">		
						<input type="text" name="doc_review_masterlist_id" id="doc_review_masterlist_id" class="form-control">
					</div>
					<div class="col-sm-3">		
						<label>DocDispositionId:</label>
					</div>
					<div class="col-sm-3">		
						<input type="text" name="docu_review_disposition_id" id="docu_review_disposition_id" class="form-control">
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
			<button type="submit" class="btn btn-primary fa fa-save" id="btnEditDispositionSave"> Save</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> Close</button>
		  </div>
		</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script src="js/common_functions.js?4324"></script>
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
	var $formAdvanceSearch = $('#formAdvanceSearch');
	var dt_masterlist_temporary = $(tbl_masterlist_temporary).DataTable({
		"aaSorting"	: [],
		"bProcessing": true,
        "bServerSide": true,
		"sAjaxSource": "server_side_scripts/dt_masterlist_temporary.php?username="+"<?php echo $_SESSION['username'];?>",
		"drawCallback": function( settings ) {
			$(tbl_masterlist_temporary).attr('style','width:100%;')
		}
	});

	function readTemporaryReviewDocu(dataId){
		let data = {'action':'read_temporary_review_docu','temporary_document_id':dataId}

		call_ajax(data, masterlist_temporary_handler,function(result){
			console.log(result);
			$('#modalDocReviewDisposition').modal();
		})
	}

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

	function getDocReviewDisposition () {
		( this.id === 'btnViewDocReviewDisposition' ) ? $('#btnEditDispositionSave').hide() : $('#btnEditDispositionSave').show()
		let doc_review_masterlist_id = $(this).attr('data-id');
		let data = {
			'action' : 'read_doc_review_masterlist',
			'doc_review_masterlist_id' : doc_review_masterlist_id,
		}

		call_ajax(data, masterlist_temporary_handler,function(result){
			console.log('disposition',result['disposition']);
			let disposition = result['disposition'];
			let doc_review_disposition_id = result['id'];
			
			$('#modalDocReviewDisposition').modal();
			$formDocReviewDisposition.find('#doc_review_masterlist_id').val(doc_review_masterlist_id);
			$formDocReviewDisposition.find('#docu_review_disposition_id').val(doc_review_disposition_id);
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

	$(tbl_masterlist_temporary).on('click','#btnSearchDocNum', function () {
		let doc_no = $(this).attr('doc_no');
		window.open("http://rapid/ACDCS/prdn_home_acdcs_masterlist?doc_no="+doc_no)
	});

	/*
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
		$(tbl_masterlist_temporary).on('click','#btnEditDocReviewDisposition' ,function () {
			let doc_review_masterlist_id = $(this).attr('data-id');
			console.log('doc_review_masterlist_id',doc_review_masterlist_id);
			let data = {
				'action' : 'read_doc_review_masterlist',
				'doc_review_masterlist_id' : doc_review_masterlist_id,
			}
			call_ajax(data, masterlist_temporary_handler,function(result){
				console.log('disposition',result['disposition']);
				let disposition = result['disposition'];
				let doc_review_disposition_id = result['id'];
				
				$('#modalDocReviewDisposition').modal();
				$formDocReviewDisposition.find('#doc_review_masterlist_id').val(doc_review_masterlist_id);
				$formDocReviewDisposition.find('#doc_review_disposition_id').val(doc_review_disposition_id);
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
	*/

	$(tbl_masterlist_temporary).on('click','#btnViewDocReviewDisposition' ,getDocReviewDisposition);
	
	$(tbl_masterlist_temporary).on('click','#btnEditDocReviewDisposition' ,getDocReviewDisposition);
	
	$('#btnBatchApproval').click(function (e) { 
		e.preventDefault();
		const filtered_doc_review_masterlist_id = Array.from(new Set(arr_doc_review_masterlist_id));
		console.log(filtered_doc_review_masterlist_id);
		$('#modal_approver_decision').modal();

	});

	$('#btnExportDocReviewMasterlist').click(function (e) { 
		e.preventDefault();
		$('#modalAdvanceSearch').modal();
		date_time_picker('expiration_date_from_to');
	});

	$('a').click(function (e) { 
		($(this).attr('href') === '#menu3')? dt_masterlist_temporary.draw() : '';
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

	$formDocReviewDisposition.submit(function (e) { 
		e.preventDefault();
		var form_data = new FormData(this);
		form_data.append("action","edit_disposition_details")
		// form_data.append("pkid",$('#modal_sa_edit').data('id'));
		// form_data.append("username",username);
		call_ajax_attachment(form_data, masterlist_temporary_handler, function(response){
			console.log('asdsa');
			if(response['is_success'] === 'true'){
				$('#modalDocReviewDisposition').modal('hide')
				notif_success('Save Successfully')
				dt_masterlist_temporary.draw()
			}else{
				$('#modalDocReviewDisposition').modal('hide');
				$('#alert_message').text(response['result_msg'])
				$('#modal_alert').modal()
			}
		});
	});

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
				dt_masterlist_temporary.draw()
			}else{
				$('#modalReviewDocu').modal('hide');
				$('#alert_message').text(response['result_msg'])
				$('#modal_alert').modal()
			}
		});
	});
	
	$formAdvanceSearch.submit(function (e) { 
		e.preventDefault();
		let data = {
			'action' : 'advanced_search',
		}
		call_ajax_serialize(data, $(this).serialize() , masterlist_temporary_handler, function(result){
			// window.location.href = " ?query="+result['script'];
			window.location.href = "reports/temporary_document/excel_iqc_visual_inspection.php?query="+result['script'];
			$('#expiration_date_from_to').val('');
			$('#modalAdvanceSearch').modal('hide');
		});
	});
	//
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
	$('#modalAdvanceSearch').on('hidden.bs.modal', function (e) {
		$('#expiration_date_from_to').val('');
	});
	
});
</script>





