<!-- Redirect to another page (for no-js support) (place it in your <head>) -->
<noscript><meta http-equiv="refresh" content="0;url=pages/nojs-version.php"></noscript>    
<html>
<title>ACDCS Document Review Process</title>
<head>
<?php 

require_once('libraries/includes.php'); 
// require_once('get_is_approver.php');
require_once('get_user_roles.php');
echo '<input type="hidden" id="txt_selected_menu" value="'.$selected_menu.'">';

?>
<?php 
/* used for standalone testing purposed only - remove on live or do not patch this page */
// $_SESSION['username'] = 'ronfern'; 
?>
<?php 
// $_SESSION['rapid_account_type'] = 2; 
/* used for standalone testing purposed only - remove on live or do not patch this page */
?>
<input type="text" id="hd_username" value="<?php echo $_SESSION['username']; ?>">
</head>
<body>
	<div id="loading_overlay">
		<div id="loading_overlay_center">
			<center>
				<img src="images/loader.gif"><br />	
				<h2>Loading Module...</h2>
			</center>
		</div>
	</div>
	<nav class="navbar navbar-default navbar-fixed-top topbar">
		<div class="">
			<div class="navbar-header">
				<a href="#" class="navbar-brand">
					<span class="hidden-xs">ACDCS</span>
				</a>
				<p class="navbar-text">
				</p>
			</div>
			<div class="navbar-collapse collapse" id="navbar-collapse-main">
				<ul class="nav navbar-nav navbar-left">
					<li>
						<a href="#" class="sidebar-toggle" id="toggle-menu">
							<i class="fa fa-bars"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	
	<article class="wrapper">
	
	<aside class="sidebar">
		<ul class="sidebar-nav">
			<li class="active" link-id="dashboard"><a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<?php 
				/*omodify
				Check if there is a read role on one IQC modules
				$user_iqc_read_access = false;
				foreach($user_role['subsystem_code'] as $key => $subsystem_code ){
					if($subsystem_code == "IQC" && $user_role['read'][$key] == 1){
						$user_iqc_read_access = true;
					}
				}
				if( $user_iqc_read_access ){
					echo '<li id="li_iqc"><a href="#" data-toggle="tab"><i class="fa fa-puzzle-piece"></i> <span>IQC</span></a></li>';
				}
				*/
			?>
		</ul>
	</aside>
	<!-- <script src="./js/common_functions.js?n=4324234"></script> -->

	<script>
		var script_url = '';
		var username = '<?php echo $_SESSION['username']?>';
		var handler = 'handler/handler.php';
		
		var selected_menu = $('#txt_selected_menu').val();
		$(function () { $('[data-toggle="popover"]').popover(); });

		$(document).on("click",".sidebar-toggle",function(){
			$(".wrapper").toggleClass("toggled");
		});
		
		$('.sidebar li').click( function (){
			let linkId = $(this).attr('link-id');
			fnGetPageLink(linkId);
		});
		
		display_loading_screent();
		function display_loading_screent(){
			document.getElementById("loading_overlay").style.display = "block";
			setTimeout(function(){
				document.getElementById("loading_overlay").style.display = "none";
			},300);
		}
		
		fn_set_active_nav(selected_menu);
		function fn_set_active_nav(selected_menu){
			if(selected_menu == ''){
				$('.sidebar .sidebar-nav li').attr('class','');
				$('.sidebar .sidebar-nav #li_dashboard').attr('class','active');
			}else{
				$('.sidebar .sidebar-nav li').attr('class','');
				$('.sidebar .sidebar-nav #li_'+selected_menu).attr('class','active');
			}
		}
	</script>
