<?php
use Pages\GetUserRoles\Rapid;
$is_dcc_staff = Rapid::getIsDccStaff();
$is_dcc_access = ($is_dcc_staff['department'][0] =="Quality Assurance Dept." OR $is_dcc_staff['section'][0] == "EMO/QMS" OR $is_dcc_staff['division'][0] =="Quality Assurance Di" ) ? 'true': 'false';

?>

<section class="main">
	<section class="tab-content" id="tab-content">
		<section class="tab-pane active fade in content">
			<div class="row">
				<div class="col-xs-12">
					<legend style="margin-bottom:0px;"><a href="#" id="a_menu"><i class="fa fa-puzzle-piece"></i> PERMANENT DOCUMENT</a></legend>
					<ol class="breadcrumb">
						<li id="bc_menu"><a href="#" class="active"><i class="fa fa-home"></i> Home </a></li>

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
							if($is_dcc_access == 'true' || $_SESSION['username'] == 'mclegaspi'){
								echo '
									<li class="nav-item active">
											<a class="nav-link" data-toggle="tab" href="#menu1">Pending</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#menu2">For Review</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#menu3">Reviewed Tab</a>
									</li>
								';
								$dcc_active = 'active';
								$process_owner_active = '';
							}else{
								echo '
									<li class="nav-item  active">
										<a class="nav-link " data-toggle="tab" href="#menu2">For Review</a>
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
					<!-- <div role="tabpanel" id="menu1" class="tab-pane <?php echo $dcc_active?>"><br> -->
					<div role="tabpanel" id="menu1" class="tab-pane"><br>
						<div class="col-sm-12">
						<div class="panel panel-warning" style="overflow:scroll;">
							<div class="panel-heading"><i class="fa fa-file fa-lg"> Pending</i></div>
								<div class="panel-body">
										<iframe src="../../ACDCS/dcc_home.php?page=./search_masterlist_temporary.php" width="100%" height="100%" style="border:none;" title="Pending">
										</iframe>
								</div>
							</div>
						</div> <!--end div panel info -->
					</div> <!--end tabpanel-->
					<!-- <div role="tabpanel" id="menu2" class="tab-pane <?php echo $process_owner_active?>"><br> -->
					<div role="tabpanel" id="menu2" class="tab-pane"><br>
						<div class="col-sm-12">
							<div class="panel panel-warning" style="overflow:scroll;">
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
								<br>
								<div class="col-sm-12 mt-5">
									<button class="btn btn-success fa fa-file-excel-o pull-right" id="btnExportDocReviewMasterlist"> Export </button>
									<button class="btn btn-info fa fa-file-pdf-o pull-left" id="btnUserManual"> User Manual </button>
								</div> <br><br><br><br>
									<div class="col-sm-12">
										<table class="table table-condensed table-bordered table-responsive" id="tbl_permanent_masterlist">
											<thead>
												<tr>
													<!-- <th></th> -->
													<th><span class="fa fa-cogs"></span></th>
													<th>Document Status</th>
													<th>Disposition</th>
													<th>Date Received</th>
													<th>Model</th>
													<th>Title</th>
													<th>Document No.</th>
													<th>Rev No.</th>
													<th>Orignator Code</th>
													<th>Effectivity Date</th>
													<th>Expiration Date</th>
													<th>Active/Obsolete</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div> 
					</div>
					<div role="tabpanel" id="menu3" class="tab-pane active"><br>
						<div class="col-sm-12">
							<div class="panel panel-warning" style="overflow:scroll;">
							<div class="panel-heading"><i class="fa fa-file fa-lg"> Reviewed Tab</i></div>
							<div class="panel-body">
								<div class="card" style="border-color: #D3D3D3;">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-4">	
											<span class="badge highlight-color-blue">DCC CONFORMANCE</span>
											<label><b>- DCC / QAS</b></label>
											</div>
										</div>
									</div>
								</div>
								<br>
								<div class="col-sm-12 mt-5">
									<button class="btn btn-success fa fa-file-excel-o pull-right" id="btnExportDocReviewMasterlistDcc"> Export </button>
									<!-- <button class="btn btn-info fa fa-file-pdf-o pull-left" id="btnUserManual"> User Manual </button> -->
								</div> <br><br><br><br>
									<div class="col-sm-12">
										<table class="table table-condensed table-bordered table-responsive" id="tbl_masterlist_temporary_for_dcc">
											<thead>
												<tr>
													<!-- <th></th> -->
													<th><span class="fa fa-cogs"></span></th>
													<th>Document Status</th>
													<th>Disposition</th>
													<th>Date Received</th>
													<th>Model</th>
													<th>Title</th>
													<th>Document No.</th>
													<th>Rev No.</th>
													<th>Orignator Code</th>
													<th>Effectivity Date</th>
													<th>Expiration Date</th>
													<th>Active/Obsolete</th>
													<!--<th>Document Type</th> -->
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div> <!--end tab-content--> 
			</div>
		</section>
	</section>
</section>
<style lang="">
	th {
		background-color:orange
	}
</style>

<?php
	include_once('pages/components/modal.php');
?>

<script src="js/common_functions.js?12345"></script>
<script src="js/doc_review_masterlist.js?123456"></script>
<script>
	$(document).ready(function () {
		var arr_dcc_review_again_masterlist_id = [];
		var handler_ = 'handler_qfr';
		var masterlist_temporary_handler = 'handler/masterlist_temporary_handler.php'
		var $formReviewDocu = $('#formReviewDocu');
		var $formDocReviewDisposition = $('#formDocReviewDisposition');
		var $formAdvanceSearch = $('#formAdvanceSearch');
		var tbl_masterlist_temporary_for_dcc = 'tbl_masterlist_temporary_for_dcc';
		var tbl_permanent_masterlist = 'tbl_permanent_masterlist';
		
		/* DataTables 
		
		SELECT SQL_CALC_FOUND_ROWS obsolete.expiration_date AS obsolete_expiration_date, obsolete.date_received AS obsolete_date_received, obsolete.model AS obsolete_model, obsolete.doc_title AS obsolete_doc_title, obsolete.doc_no AS obsolete_doc_no, obsolete.rev_no AS obsolete_rev_no, obsolete.effectivity_date AS obsolete_effectivity_date, active.expiration_date, masterlist.id, masterlist.fkid_document, masterlist.document_status, masterlist.is_expiration_date_exist, masterlist.deleted_at, active.date_received, active.model, active.doc_title, active.doc_no, active.rev_no, active.effectivity_date, masterlist.originator_code, disposition.reviewed_by, disposition.disposition
		FROM   tbl_doc_review_masterlist masterlist
		LEFT JOIN tbl_active_docs active ON active.fkid_document = masterlist.fkid_document LEFT JOIN tbl_obsolete_docs obsolete ON obsolete.fkid_document = masterlist.fkid_document LEFT JOIN tbl_doc_review_disposition disposition ON disposition.tbl_doc_review_masterlist_id = masterlist.id
		WHERE  masterlist.deleted_at IS NULL AND masterlist.document_status = 3 AND disposition.deleted_at IS NULL AND MONTH(disposition.dcc_email) = 2 AND ( (active.`view_status` = 1 AND active.`logdel` = 0) OR (obsolete.`logdel` = 0) )
		ORDER BY masterlist.id ASC
		LIMIT 0, 10
	
		
		*/
		var dt_permanent_masterlist = $('#'+tbl_permanent_masterlist).DataTable({
			"aaSorting"	: [],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_side_scripts/dt_permanent_masterlist.php?username="+"<?php echo $_SESSION['username'];?>",
			"columnDefs":[
				{"orderable":false,"targets":[0]}
			],
			"ordering":false,
			"drawCallback": function( settings ) {
				$('#'+tbl_permanent_masterlist).attr('style','width:100%;')
			}
		});
		var dt_permanent_masterlist_for_dcc = $('#'+tbl_masterlist_temporary_for_dcc).DataTable({
			"aaSorting"	: [],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_side_scripts/dt_permanent_masterlist_for_dcc.php?username="+"<?php echo $_SESSION['username'];?>",
			// "columnDefs":[
			// 	{"orderable":false,"targets":[0]}
			// ],
			"ordering":false,
			"drawCallback": function( settings ) {
				$('#tbl_masterlist_temporary_for_dcc').attr('style','width:100%;')
			}
		});
		
		/* FUNCTION */
		const dccReviewAgain = (doc_review_masterlist_id) => {
			let data = {'action':'dcc_review_again','doc_review_masterlist_id':doc_review_masterlist_id}
			call_ajax(data, masterlist_temporary_handler,function(result){
				if(result['is_success'] === 'true'){
					$('#modal_dcc_review_again').modal('hide');
					notif_success(result['is_success']);
					dt_permanent_masterlist_for_dcc.draw();
					dt_permanent_masterlist.draw();
				}
			})
		}
		
		/* EVENT */
		$('#'+tbl_permanent_masterlist).on('click','#btnReviewDocu',function (e) { 
			e.preventDefault()
			$('#modalReviewDocu').modal()
			$formReviewDocu.find('#doc_review_masterlist_id').val($(this).attr('data-id'))
			re_initialize_select2_server_side('#modalReviewDocu #person_in_charge','#modalReviewDocu #formReviewDocu',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");
			re_initialize_select2_server_side('#modalReviewDocu #sec_heads_username','#modalReviewDocu #formReviewDocu',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");

		});

		$('#'+tbl_permanent_masterlist).on('click','#cBoxBatchSelect' ,function () {
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

		$('#'+tbl_permanent_masterlist).on('click','#btnSearchDocNum', function () {
			let doc_no = $(this).attr('doc-no');
			let doc_type = $(this).attr('doc-type');

			(doc_type === 'active') ? window.open("http://rapid/ACDCS/prdn_home_acdcs_masterlist_active?doc_no="+doc_no) :
									window.open("http://rapid/ACDCS/prdn_home_acdcs_masterlist_obsolete?doc_no="+doc_no);
		});
		
		$('#'+tbl_masterlist_temporary_for_dcc).on('click','#btnSearchDocNum', function () {
			let doc_no = $(this).attr('doc-no');
			let doc_type = $(this).attr('doc-type');

			(doc_type === 'active') ? window.open("http://rapid/ACDCS/prdn_home_acdcs_masterlist_active?doc_no="+doc_no) :
									window.open("http://rapid/ACDCS/prdn_home_acdcs_masterlist_obsolete?doc_no="+doc_no);
		});

		$('#'+tbl_permanent_masterlist).on('click','#btnViewDocReviewDisposition' ,getDocReviewDisposition);
		
		$('#'+tbl_permanent_masterlist).on('click','#btnEditDocReviewDisposition' ,getDocReviewDisposition);

		$('#'+tbl_masterlist_temporary_for_dcc).on('click','#btnReviewAgain' , function () {
			let doc_review_masterlist_id = $(this).attr('data-id');
			arr_dcc_review_again_masterlist_id.push(doc_review_masterlist_id);
			$('#modal_dcc_review_again').modal();
		});
		$('#yes_dcc_review_again').click(function (e) { 
			e.preventDefault();
			dccReviewAgain(arr_dcc_review_again_masterlist_id);
		});

		$('#'+tbl_masterlist_temporary_for_dcc).on('click','#btnViewDocReviewDisposition' ,getDocReviewDisposition);
		
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

		$('#btnExportDocReviewMasterlistDcc').click(function (e) { 
			e.preventDefault();
			$('#modalAdvanceSearch').modal();
			date_time_picker('expiration_date_from_to');
		});

		$('a').click(function (e) { 
			( $(this).attr('href') === '#menu2' ) ? dt_permanent_masterlist.draw() : '';
			( $(this).attr('href') === '#menu3' ) ? dt_permanent_masterlist_for_dcc.draw() : '';
		});

		$('#link_file_memo').click(function (e) { 
			e.preventDefault();
			let doc_review_disposition_id = $formDocReviewDisposition.find('#docu_review_disposition_id').val();
			window.open("reports/pdf_download/memo.php?doc_review_disposition_id="+doc_review_disposition_id);
		});

		// $('#btnUserManual').click(function (e) { 
		// 	e.preventDefault();
		// 	window.open("reports/pdf_download/user_manual.php");
		// });
		
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
				if(response['is_success'] === 'true'){
					$('#modalDocReviewDisposition').modal('hide')
					notif_success('Save Successfully')
					dt_permanent_masterlist.draw()
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
			call_ajax_attachment(form_data, masterlist_temporary_handler, function(response){
				if(response['is_success'] === 'true'){
					$('#modalReviewDocu').modal('hide');
					notif_success('Save Successfully');
					dt_permanent_masterlist.draw();
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
				window.location.href = "reports/excel_auto_generate/temporary_document.php?query="+result['script'];
			$('#expiration_date_from_to').val('');
				$('#modalAdvanceSearch').modal('hide');
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
		$('#modalAdvanceSearch').on('hidden.bs.modal', function (e) {
			$('#expiration_date_from_to').val('');
		});
		$('#modal_dcc_review_again').on('hidden.bs.modal', function (e) {
			console.log(arr_dcc_review_again_masterlist_id);
			arr_dcc_review_again_masterlist_id = [];
			console.log('after',arr_dcc_review_again_masterlist_id);

		});
		$('.modal').on('hidden.bs.modal', function (e) {
			/* Allows the overlayed modal to be scrollable */
			if($('.modal').hasClass('in')) {
				$('body').addClass('modal-open');
			}    
		});

	});
</script>





