<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>BPHO Administration</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<?php
		if (file_exists("../../assets/css/admin/bootstrap-datetimepicker.min.css")) {
			echo '<link rel="stylesheet" href="../../assets/css/admin/bootstrap-datetimepicker.min.css">';
		} else {
			echo '<link rel="stylesheet" href="../../../assets/css/admin/bootstrap-datetimepicker.min.css">';
		}
	?>


	<style>
	</style>
	<link rel="stylesheet" href="../../assets/css/admin/bootstrap-datetimepicker.min.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<!--C:\Users\Thomas\Documents\GitHub\TFE\web\bpho\assets\js\bootstrap.js
	C:\Users\Thomas\Documents\GitHub\TFE\web\bpho\application\views\includes\header.php-->
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src='//cdn.tinymce.com/4/tinymce.min.js'></script>
	<script>
		tinymce.init({
			selector: '#descriptionFRInput',
			toolbar: 'styleselect bold italic underline ',
			menubar: false,
			statusbar: false,
			width: 500,
			height: 150,
			max_height: 500,
			max_width: 700,
			min_height: 100,
			min_width: 300,
			resize: 'both',
			style_formats: [
				{title: 'Titre 1', block: 'h1'},
				{title: 'Titre 2', block: 'h2'}
			]
		});
		tinymce.init({
			selector: '#descriptionNLInput',
			toolbar: 'styleselect bold italic underline ',
			menubar: false,
			statusbar: false,
			width: 500,
			height: 150,
			max_height: 500,
			max_width: 700,
			min_height: 100,
			min_width: 300,
			resize: 'both',
			style_formats: [
				{title: 'Titre 1', block: 'h1'},
				{title: 'Titre 2', block: 'h2'}
			]
		});
		tinymce.init({
			selector: '#descriptionENInput',
			toolbar: 'styleselect bold italic underline ',
			menubar: false,
			statusbar: false,
			width: 500,
			height: 150,
			max_height: 500,
			max_width: 700,
			min_height: 100,
			min_width: 300,
			resize: 'both',
			style_formats: [
				{title: 'Titre 1', block: 'h1'},
				{title: 'Titre 2', block: 'h2'}
			]
		});
		tinymce.init({
			selector: '#messageAlerte',
			toolbar: 'styleselect bold italic underline ',
			menubar: false,
			statusbar: false,
			width: 500,
			height: 150,
			max_height: 500,
			max_width: 700,
			min_height: 100,
			min_width: 300,
			resize: 'both',
			style_formats: [
				{title: 'Titre 1', block: 'h1'},
				{title: 'Titre 2', block: 'h2'}
			]
		});
	</script>
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
					$("#mceu_4").show();
					$("#mceu_22").hide();
					$("#mceu_13").hide();
				} else if (name == "NL") {
					$("#titleFRInput").hide();
					$("#titleNLInput").show();
					$("#titleENInput").hide();
					$("#mceu_4").hide();
					$("#mceu_22").hide();
					$("#mceu_13").show();
				} else if (name == "EN") {
					$("#titleFRInput").hide();
					$("#titleNLInput").hide();
					$("#titleENInput").show();
					$("#mceu_4").hide();
					$("#mceu_22").show();
					$("#mceu_13").hide();
				}
			});
		});

		var idTemp = "instrumentInput_";
		var nameTemp = "instrument_";
		var instruNB = 2;
		$(function() {
			$("#btnAddInstru").click(function() {

				while (document.getElementById(idTemp + instruNB.toString()) !== null) {
					instruNB = instruNB + 1;
				}

				var $input = $("<br><input style='margin-left: 73px; margin-top: 10px;' name='" + nameTemp + instruNB.toString() + "' type='text' id='" + idTemp + instruNB.toString() + "' list='instruments'>");
				var idAppend = "#" + idTemp + (instruNB-1).toString();
				$('input' + idAppend).after($input);
				instruNB = instruNB + 1;
			});
		});
	</script>
</head>
<body>
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
	    <div class="container">
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
