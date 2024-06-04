	<section class="main">
		
		<section class="tab-content" id="tab-content">
			
		   <section class="tab-pane active fade in content" id="configuration">
			   <div class="row">
				   <div class="col-xs-12 col-sm-8 col-md-9">
						<a href="#"><span aria-hidden="true" class="fa fa-cogs fa-2x"> Settings</span></a>
					</div>
			    </div><br />
				<div class="row">
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#originator_code" aria-controls="originator_code" role="tab" data-toggle="tab"> Originator Code</a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="originator_code">
						<?php include('originator_code.php'); ?>
					</div>
				  </div>
				</div>
		   </section>
		</section>
	</section>
</article>
<!-- 
	Script - Homapage Google Like Search 
-->
<script>
var username 				= $('#hd_username') .val();
var handler_configuration 	= 'handler/handler_configuration.php';
</script>

<script src="js/common_functions.js?n="+<?php echo date("YmdHis");?>></script>
