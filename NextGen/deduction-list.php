<?php 
require_once "importance.php"; 

if(!User::loggedIn()){
	Config::redir("login.php"); 
}
?> 

<html>
<head>
	<title><?php echo CONFIG::SYSTEM_NAME; ?> : Deductions List</title>
	<?php require_once "inc/head.inc.php";  ?> 
</head>
<body>
	<?php require_once "inc/header.inc.php"; ?> 
	<div class='container-fluid'>
		<div class='row'>
			<div class='col-md-2'><?php require_once "inc/sidebar.inc.php"; ?></div> <!-- this should be a sidebar --> 
			<div class='col-md-10'>
				<div class='content-area'> 
				<div class='content-header'> 
					Deductions <small>Deductions Record</small>
				</div>
				<?php require_once "inc/alerts.inc.php";  ?> 
				<div class='content-body'> 
					<p align="right">
						<button class="btn btn-primary" onclick="location.href='add-deduction.php'" type="button" id="new_ded_btn"></span> Add Deduction</button> 
					</p>
					<?php Deduction::load();?>
				</div><!-- end of the content area --> 
				</div> 
				
			</div><!-- col-md-7 --> 

			
				
		</div> 
	</div> 
</body>
</html>
