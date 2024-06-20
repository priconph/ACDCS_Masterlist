
<section class="main">
		<section class="tab-content" id="tab-content">
		   <section class="tab-pane active fade in content" id="dashboard">
				<span class="fa fa-2x fa-dashboard"> Dashboard </span>
				<hr>
				<div>
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist" id="tab_dashboard_menu">
					<!-- <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><span class="fa fa-home"></span> Modules</a></li> -->
					<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><span class="fa fa-home"></span> Modules</a></li>
				  </ul>

				  <!-- Tab panes -->
				  <!---------------------------------
					------------- Home tab ----------
					---------------------------------->
				  <div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home">
						
						<div class="row"><!-- Modules DIV Start -->				
							<div class="col-xs-12">
								<div class="panel panel-primary">
								   <div class="panel-heading">
									  <i class="fa fa-tags"></i> <span class="fa"> Modules</span>
								   </div>
									<div class="panel-body" style="height:750px;overflow:auto;">
										<?php 
											/* Check if there is a read role on one IQC modules */
											// echo $query;
											use Pages\GetUserRoles\DocReviewDispositionApprovalDetails;
											use Pages\GetUserRoles\Rapid;
											$is_approver = DocReviewDispositionApprovalDetails::getIsApprover();
											$is_dcc_staff = Rapid::getIsDccStaff();
											$is_dcc_access = (
												$is_dcc_staff['department'][0] =="Quality Assurance Dept." 
												OR $is_dcc_staff['section'][0] == "EMO/QMS" 
												OR $is_dcc_staff['division'][0] =="Quality Assurance Di" 
												OR $is_dcc_staff['division'][0] =='QAS' 
												OR $is_dcc_staff['section'][0] == 'Information Systems Section' 
											) ? 'true': 'false';
											$is_username_exist = isset($_SESSION['username']) ? 'true' : 'false';

											if($is_username_exist == 'true'){
												echo '<div class="col-xs-3">
														<a href="#" id="router" link-id="temporaryDocu">
															<div class="panel panel-success" style="padding:0px;">
																<div class="panel-heading">
																	<div class="row">
																		<div class="col-xs-5">
																		<i class="fa fa-search fa-5x"></i>
																		</div>
																		<div class="col-xs-7 text-right">
																		<p class="announcement-heading"></p>
																		<p class="announcement-text fa-2x"> Temporary Documents</p>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-xs-12">
																			<p style="font-size: small;text-wrap: nowrap;">(Waiver, Change notice,Instruction ,Urgent Direction, GP)</p>
																		</div>
																	</div>
																</div>
																<div class="panel-footer announcement-bottom">
																	<div class="row">
																		<div class="col-xs-6">
																			Open
																		</div>
																		<div class="col-xs-6 text-right">
																			<i class="fa fa-arrow-circle-right"></i>
																		</div>
																	</div>
																</div>
															</div>
														</a>
													</div>';
												echo '<div class="col-xs-3">
														<a href="#" id="router" link-id="permanentDocu">
															<div class="panel panel-warning" style="padding:0px;">
																<div class="panel-heading">
																	<div class="row">
																		<div class="col-xs-5">
																		<i class="fa fa-search fa-5x"></i>
																		</div>
																		<div class="col-xs-7 text-right">
																		<p class="announcement-heading"></p>
																		<p class="announcement-text fa-2x"> Permanent Documents</p>
																		</div>
																	</div>
																	<br/>
																</div>
																<div class="panel-footer announcement-bottom">
																	<div class="row">
																		<div class="col-xs-6">
																			Open
																		</div>
																		<div class="col-xs-6 text-right">
																			<i class="fa fa-arrow-circle-right"></i>
																		</div>
																	</div>
																</div>
															</div>
														</a>
													</div>';
												if($is_dcc_access == 'true'){
													echo '<div class="col-xs-3">
															<a href="#" id="router" link-id="settings">
																<div class="panel panel-primary" style="padding:0px;">
																	<div class="panel-heading">
																		<div class="row">
																			<div class="col-xs-5">
																			<i class="fa fa-cogs fa-5x"></i>
																			</div>
																			<div class="col-xs-7 text-right">
																			<p class="announcement-heading"></p>
																			<p class="announcement-text fa-2x"> Settings</p>
																			</div>
																		</div>
																		<br/>
																	</div>
																	<div class="panel-footer announcement-bottom">
																		<div class="row">
																			<div class="col-xs-6">
																				Open
																			</div>
																			<div class="col-xs-6 text-right">
																				<i class="fa fa-arrow-circle-right"></i>
																			</div>
																		</div>
																	</div>
																</div>
															</a>
														</div>';
												}
												if( $is_approver > 0 ){
													echo '<div class="col-xs-3">
														<a href="#" id="router" link-id="forApproval">
															<div class="panel panel-info" style="padding:0px;">
																<div class="panel-heading">
																<div class="row">
																	<div class="col-xs-5">
																	<i class="fa fa-check fa-5x"></i>
																	</div>
																	<div class="col-xs-7 text-right">
																	<p class="announcement-heading"></p>
																	<p class="announcement-text fa-2x"> For Approval Documents</p>
																	</div>
																</div>
																<div class="row">
																		<div class="col-xs-12">
																			<p style="font-size: small;text-wrap: nowrap;">(Section Head / Dept Head)</p>
																		</div>
																</div>
																</div>
																<div class="panel-footer announcement-bottom">
																	<div class="row">
																		<div class="col-xs-6">
																			Open
																		</div>
																		<div class="col-xs-6 text-right">
																			<i class="fa fa-arrow-circle-right"></i>
																		</div>
																	</div>
																</div>
															</div>
														</a>
													</div>';
												}
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
			</section>
		</section>
	</section>
</article>
<script src="js/common_functions.js?12345"></script>
<script>
	$(document).ready(function () {
		$('a').click(function (e) { 
			e.preventDefault();
			let linkId = $(this).attr('link-id');
			if(linkId != undefined){
				fnGetPageLink(linkId)
			}
			// window.load.location('pages/masterlist_temporary/index.php');
			// window.location.href = "index.php?page="+result['page'];
		});
	});
</script>