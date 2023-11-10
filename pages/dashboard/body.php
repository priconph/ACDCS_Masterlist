
<section class="main">
		<section class="tab-content" id="tab-content">
		   <section class="tab-pane active fade in content" id="dashboard">
				<span class="fa fa-2x fa-dashboard"> Dashboard </span>
				<hr>
				<div>
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist" id="tab_dashboard_menu">
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
											
											// $user_iqc_read_access = false;
											// foreach($user_role['subsystem_code'] as $key => $subsystem_code ){
											// 	if($subsystem_code == "IQC" && $user_role['read'][$key] == 1){ 
											// 		$user_iqc_read_access = true;
											// 	}
											// }
											
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
											if( $data['approver_count'] >0 ){
												echo '<div class="col-xs-3">
												<a href="#" id="router" link-id="forApproval">
													<div class="panel panel-success" style="padding:0px;">
														<div class="panel-heading">
														<div class="row">
															<div class="col-xs-5">
															<i class="fa fa-search fa-5x"></i>
															</div>
															<div class="col-xs-7 text-right">
															<p class="announcement-heading"></p>
															<p class="announcement-text fa-2x"> For Approval Documents</p>
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
<script src="js/common_functions.js?1234"></script>
<script>
	$(document).ready(function () {
		$('a').click(function (e) { 
			e.preventDefault();
			let linkId = $(this).attr('link-id');
			fnGetPageLink(linkId)
			// window.load.location('pages/masterlist_temporary/index.php');
			// window.location.href = "index.php?page="+result['page'];
			
		});
	});
</script>




