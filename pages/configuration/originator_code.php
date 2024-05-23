<div class="col-xs-12 col-sm-8 col-md-9" id="container_user_crud">
	<div class="panel panel-default">
		<div class="panel-heading">
			<i class="fa fa-users fa-lg"></i> Users CRUD
		</div>
		<div class="panel-body">
			<table class="table table-hover table-striped table-bordered table-condensed" id="tbl_dept_originator_code">
				<thead>
					<tr>
						<th><i class="fa fa-cogs"></i></th>
						<th>Department</th>
						<th>Originator Code</th>
						<th>Updated By</th>
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
<div class="modal fade" tabindex="-1" role="dialog" id="modal_edit_user_roles">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <i class="fa fa-edit"></i> Edit User Roles</h4>
      </div>
      <div class="modal-body">
        <form id="frm_edit_user_roles">
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-condensed table-bordered" id="tbl_user_role">
						<thead>
							<tr>
								<th>Action</th>
								<th>Department</th>
								<th>Originator Code</th>
								<th>Updated By</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
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
		var dt_dept_originator_code = $('#'+tbl_dept_originator_code).DataTable({
			"aaSorting"	: [],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_side_scripts/configuration/dt_dept_originator_code.php?username="+"<?php echo $_SESSION['username'];?>",
			"columnDefs":[
				{"orderable":false,"targets":[0,1]}
			],
			"ordering":true,
			"drawCallback": function( settings ) {
				$('#'+tbl_dept_originator_code).attr('style','width:100%;')
			}
		});

		$('#'+tbl_dept_originator_code).on('click','.fa-cogs', function () {
			let tbl_doc_review_originator_code_originator_code = $(this).attr('tbl_doc_review_originator_code_originator_code');
			alert(tbl_doc_review_originator_code_originator_code);
			let data ={
				'tbl_doc_review_originator_code_originator_code' : tbl_doc_review_originator_code_originator_code,
				action : 'get_dept_originator_code_by_originator_code',
			}
			call_ajax(data, masterlist_temporary_handler, function (response){
				console.log(response);
			});
			
		});
	});
</script>