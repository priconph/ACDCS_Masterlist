<div class="col-xs-12 col-sm-8 col-md-9" id="container_user_crud">
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-users fa-lg"></i> Originator Code Table
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-12">
					<button class="btn btn-success fa fa-plus pull-right" id="btnAddOriginatorCode" modal-target> Add New </button>
				</div>
			</div>
			<br>
			<table class="table table-hover table-striped table-bordered table-condensed" id="tbl_dept_originator_code">
				<thead>
					<tr>
						<th><i class="fa fa-cogs"></i></th>
						<th>Department</th>
						<th>Originator Code</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>									
		</div>
	</div>
</div>
<div class="hidden-xs col-sm-4 col-md-3">
	<div class="panel panel-info">
		<div class="panel-heading">
			<i class="fa fa-book fa-lg"></i> Didn't Find Employee from list?
		</div>
		<div class="panel-body">
			<p>To add employee to the CRUD list, please assign the system to this employee using Rapid.</p>
		</div>
	</div>
</div>
<!---------------------
		Modals
---------------------->
<div class="modal fade" tabindex="-1" role="dialog" id="modalEditOriginatorCode">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitle"> </h4>
      </div>
      <div class="modal-body">
        <form id="frmEditOriginatorCode">
			<div class="row">
				<div class="col-sm-12">
					<div class="row" style="display:none;">
						<div class="col-sm-12">				
								<label>Originator Code ID</label>
							</div>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="text_originator_code_id" name="originator_code_id" style="width:100%;">
							</div>
						</div>
					<div class="row">
						<div class="col-sm-12">				
							<label>Department</label>
						</div>
						<div class="col-sm-12">
							<select class="form-control" id="text_department" name="department[]" style="width:100%;" multiple required></select>
						</div>
						<div class="col-sm-12">				
							<label>Originator Code</label>
							<input type="text" class="form-control" id="text_originator_code" name="originator_code" style="width:100%;" required>
						</div>
						<div class="col-sm-12">				
						</div>
					</div>
				</div>
			</div>
			<br><br><br>
      </div>
      <div class="modal-footer">
		 <button type="submit" class="btn btn-primary">Save</button> 
		 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$(document).ready(function () {
		// tbl_dept_originator_code
		var tbl_dept_originator_code = 'tbl_dept_originator_code';
		var masterlist_temporary_handler = 'handler/masterlist_temporary_handler.php'
		var $frmEditOriginatorCode = $('#frmEditOriginatorCode');
		var dt_dept_originator_code = $('#'+tbl_dept_originator_code).DataTable({
			"aaSorting"	: [],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_side_scripts/configuration/dt_dept_originator_code.php?username="+"<?php echo $_SESSION['username'];?>",
			"columnDefs":[
				{"orderable":false,"targets":[0]}
			],
			"ordering":true,
			"drawCallback": function( settings ) {
				$('#'+tbl_dept_originator_code).attr('style','width:100%;')
			}
		});

		$('#'+tbl_dept_originator_code).on('click','.fa-cogs', function () {
			let tbl_doc_review_originator_code_originator_code = $(this).attr('tbl_doc_review_originator_code_originator_code');
			let data ={
				'tbl_doc_review_originator_code_originator_code' : tbl_doc_review_originator_code_originator_code,
				action : 'get_dept_originator_code_by_originator_code',
			}
			call_ajax(data, masterlist_temporary_handler, function (response){ //zx
				$('#modalEditOriginatorCode').modal();
				re_initialize_select2_server_side('#modalEditOriginatorCode #text_department','#modalEditOriginatorCode #frmEditOriginatorCode',[],"server_side_scripts/dropdown/settings/dd_systemone_department_list.php");
				$frmEditOriginatorCode.find('[name=originator_code_id]').val(response['id']);
				$frmEditOriginatorCode.find('[name=originator_code]').val(response['originator_code']);
				if(	response['is_success'] === 'true'	){
					$('#modalTitle').html('<i class="fa fa-edit"></i> Edit Originator Code');
					assign_value_select2('#frmEditOriginatorCode #text_department',response['arr_department_id']);
				}
			});
		});
		$('#btnAddOriginatorCode').click(function (e) { 
			e.preventDefault();
			$('#modalEditOriginatorCode').modal();
			$('#modalTitle').html('<i class="fa fa-edit"></i> Add Originator Code');
			re_initialize_select2_server_side('#modalEditOriginatorCode #text_department','#modalEditOriginatorCode #frmEditOriginatorCode',[],"server_side_scripts/dropdown/settings/dd_systemone_department_list.php");
		});

		$frmEditOriginatorCode.submit(function (e) { //zx
			e.preventDefault();
			let data ={
				action : 'save_originator_code',
			}
			let serialized_data = $(this).serialize();
			call_ajax_serialize(data, serialized_data, masterlist_temporary_handler, function (response){
				if( response['is_success'] === 'true' ){
					$('#modalEditOriginatorCode').modal('hide');
					dt_dept_originator_code.draw();
					notif_success(response['system_msg']);
				}else{
					notif_err(response['system_msg']);
				}
			});
		});
		
		$('#modalEditOriginatorCode').on('hidden.bs.modal', function (e) {
			$frmEditOriginatorCode[0].reset()
			$frmEditOriginatorCode.find('#text_department').empty()
		});
		
	});
</script>