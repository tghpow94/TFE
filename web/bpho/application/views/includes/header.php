<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>CodeIgniter Admin Sample Project</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
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
	        <li <?php if($this->uri->segment(2) == 'manufacturers'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/manufacturers">Evenements</a>
			</li>
			<li <?php if($this->uri->segment(2) == 'deconnexion'){echo 'class="active"';}?>>
				<a href="<?php echo base_url(); ?>admin/logout">Déconnexion</a>
			</li>
	      </ul>
	    </div>
	  </div>
	</div>	
