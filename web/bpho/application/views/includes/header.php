<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>CodeIgniter Admin Sample Project</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="../../assets/css/admin/bootstrap-datetimepicker.min.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<!--C:\Users\Thomas\Documents\GitHub\TFE\web\bpho\assets\js\bootstrap.js
	C:\Users\Thomas\Documents\GitHub\TFE\web\bpho\application\views\includes\header.php-->
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"></script>
	<script>
		function myFunction() {
			var pass1 = document.getElementById("password").value;
			var pass2 = document.getElementById("password2").value;
			if (pass1.length < 8) {
				document.getElementById("password").style.borderColor = "#E34234";
				document.getElementById("passwordLength").style.visibility = "visible"
			} else {
				document.getElementById("password").style.borderColor = "#ccc";
				document.getElementById("passwordLength").style.visibility = "collapse"
			}
			if (pass1 != pass2 && pass2 != "") {
				document.getElementById("btnSubmit").disabled = true;
				document.getElementById("password2").style.borderColor = "#E34234";
				document.getElementById("passwordMatch").style.visibility = "visible";
			} else {
				document.getElementById("btnSubmit").disabled = false;
				document.getElementById("password2").style.borderColor = "#ccc";
				document.getElementById("passwordMatch").style.visibility = "collapse";
			}
			return ok;
		}

		$(function() {
			$(".btn-group button").click(function() {
				var name = $(this).attr("name");
				if (name == "FR") {
					$("#titleFRInput").show();
					$("#titleNLInput").hide();
					$("#titleENInput").hide();
					$("#descriptionFRInput").show();
					$("#descriptionENInput").hide();
					$("#descriptionNLInput").hide();
				} else if (name == "NL") {
					$("#titleFRInput").hide();
					$("#titleNLInput").show();
					$("#titleENInput").hide();
					$("#descriptionFRInput").hide();
					$("#descriptionENInput").hide();
					$("#descriptionNLInput").show();
				} else if (name == "EN") {
					$("#titleFRInput").hide();
					$("#titleNLInput").hide();
					$("#titleENInput").show();
					$("#descriptionFRInput").hide();
					$("#descriptionENInput").show();
					$("#descriptionNLInput").hide();
				}
			});
		});
	</script>
</head>
<body>
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
	    <div class="container">
	      <a class="brand">Project Name</a>
	      <ul class="nav">
	        <li <?php if($this->uri->segment(2) == 'users'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/users">Utilisateurs</a>
	        </li>
	        <li <?php if($this->uri->segment(2) == 'events'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/events">Evenements</a>
			</li>
			<li <?php if($this->uri->segment(2) == 'deconnexion'){echo 'class="active"';}?>>
				<a href="<?php echo base_url(); ?>admin/logout">Déconnexion</a>
			</li>
	      </ul>
	    </div>
	  </div>
	</div>	
