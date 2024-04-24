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
				<div class="row" style="display:none;">
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
				<div class="row" style="display:block;">
					<div class="col-sm-12">		
						<label>Reviewed by:</label>
					</div>
					<div class="col-sm-12">		
						<span type="text" name="reviewed_by" id="reviewed_by" class="form-control" style="width:100%;" readonly>
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
						<label>Memo File: (if the document is greater than 9 months based on effectivity date)</label>
					</div>
					<div class="col-sm-12">				
						<input type="file" class="form-control" id="file_memo" name="file_memo" style="width:100%;" accept=".pdf">
					</div>
					<div class="col-sm-12" style="display:block;">				
						<a class="fa fa-file-pdf-o" id="link_file_memo" name="link_file_memo" style="width:100%;display:none;"> View Attachment</a>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">				
						<label>Process Owner Remarks:</label>
					</div>
					<div class="col-sm-12">	
						<input type="text" name="remarks" id="remarks" class="form-control">
					</div>
				</div>
				<div class="row" id="is_approver_remarks"  style="display:none;">
					<div class="col-sm-3">				
						<label>Approver Remarks:</label>
					</div>
					<div class="col-sm-9">	
						<i><p id="approver_remarks"></p></i>
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


<!-- Modal for DCC -->
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
					<label>DCC Email Date:</label>
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

<!-- Modal for Approver Decision -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_dcc_review_again">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-question-circle-o"></i> System Confirmation</h4>
      </div>
		  <div class="modal-body">
			<div class="row" id="">
				<div class="col-sm-12" id="">				
					<label>Are you sure you want to Review Again?</label>
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-primary fa fa-check" id="yes_dcc_review_again"> Yes</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> No</button>
		  </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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

