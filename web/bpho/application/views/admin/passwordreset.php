<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>BPHO Administration</title>
    <meta charset="utf-8">
    <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
//form validation
echo validation_errors();
?>
<div class="container login">
    <?php
    $attributes = array('class' => 'form-signin');
    echo form_open('admin/passwordreset', $attributes);
    echo '<h2 class="form-signin-heading">Réinitialisez votre mot de passe</h2>';
    echo form_input('mail', set_value('mail'), 'placeholder="Adresse mail"');
    echo form_submit('submit', 'submit', 'class="btn btn-large btn-primary"');
    echo form_close();
    ?>
</div>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</body>
</html>
