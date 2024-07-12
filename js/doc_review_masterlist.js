// $(document).ready(function () {
    var tbl_masterlist_temporary_permanent_history = 'tbl_masterlist_temporary_permanent_history';

    var dt_masterlist_temporary_permanent_history = $('#'+tbl_masterlist_temporary_permanent_history).DataTable({ //APPLY TO PERMANENT
        "aaSorting"	: [],
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "server_side_scripts/dt_masterlist_history_temporary_permanent.php?doc_review_masterlist_id=0",
        "columnDefs":[
            { "orderable":false,"targets":[0] }
        ],
        "ordering":false,
        "drawCallback": function( settings ) {
            $('#'+tbl_masterlist_temporary_permanent_history).attr('style','width:100%;')
        }
    });

    /* Global Variable */
    $(document).ready(function () {
        $('#btnUserManual').click(function (e) { 
            e.preventDefault();
            window.open("reports/pdf_download/user_manual.php");
        });
    });
    var masterlist_temporary_handler = "handler/masterlist_temporary_handler.php";
    var $formDocReviewDisposition = $('#formDocReviewDisposition');

    function getDocReviewDisposition () {
        let doc_review_masterlist_id = $(this).attr('data-id');
        
        ( this.id === 'btnViewDocReviewDisposition' ) ? $('#btnEditDispositionSave').hide() : $('#btnEditDispositionSave').show()
        if($(this).attr('btn-type') === 'view' ){
            $formDocReviewDisposition.find('#file_memo').hide();

        }else{
            $formDocReviewDisposition.find('#file_memo').show();
        }

        let data = {
            'action' : 'read_doc_review_masterlist',
            'doc_review_masterlist_id' : doc_review_masterlist_id,
        }
        
        call_ajax(data, masterlist_temporary_handler,function(result){
            let approver_remarks = result['remarks'];
            let process_owner_remarks = result['process_owner_remarks'];
            let disposition = result['disposition'];
            let doc_review_disposition_id = result['id'];
            $('#modalDocReviewDisposition').modal();
            (approver_remarks != null) ? $formDocReviewDisposition.find('#is_approver_remarks').show() : $formDocReviewDisposition.find('#is_approver_remarks').hide()
            console.log(approver_remarks);
            $formDocReviewDisposition.find('#approver_remarks').html(approver_remarks);
            $formDocReviewDisposition.find('#doc_review_masterlist_id').val(doc_review_masterlist_id);
            $formDocReviewDisposition.find('#docu_review_disposition_id').val(doc_review_disposition_id);
            $formDocReviewDisposition.find('#disposition').val(disposition);
            $formDocReviewDisposition.find('#remarks').val(process_owner_remarks);
            $formDocReviewDisposition.find('#reviewed_by').html(result['reviewed_by'][0].text);
            assign_value_select2('#formDocReviewDisposition #sec_heads_username',result['sec_heads_username']);
            if(disposition === '1'){
                $formDocReviewDisposition.find('#due_date').val(result['due_date']);
                assign_value_select2('#formDocReviewDisposition #person_in_charge',result['person_in_charge']);
            }else if(disposition === '4'){
                $formDocReviewDisposition.find('#extension_date').val(result['extension_date']);
            }
            // assign_value_select2('#formDocReviewDisposition #reviewed_by',result['reviewed_by']);

            re_initialize_select2_server_side('#modalDocReviewDisposition #person_in_charge','#modalDocReviewDisposition #formDocReviewDisposition',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");
            re_initialize_select2_server_side('#modalDocReviewDisposition #sec_heads_username','#modalDocReviewDisposition #formDocReviewDisposition',[],"server_side_scripts/dropdown/common/dd_rapid_hris_and_subcon_list.php");
            dispositionStatus($formDocReviewDisposition,disposition);
        });
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

    const getViewDispositionHistoryById = function (doc_review_masterlist_id){
        dt_masterlist_temporary_permanent_history.ajax.url("server_side_scripts/dt_masterlist_history_temporary_permanent.php?doc_review_masterlist_id="+doc_review_masterlist_id).draw();
        $('#modal_masterlist_temporary_permanent_history').modal();
    }

// });
        

