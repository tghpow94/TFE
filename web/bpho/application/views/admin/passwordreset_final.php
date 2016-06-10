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
<script>
    var password = document.getElementById("password")
        , confirm_password = document.getElementById("passwordConfirm");
    document.getElementById("btnSubmit").disabled = true;

    function validatePassword(){
        if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>
<div class="container login">
    <?php
    $attributes = array('class' => 'form-signin');
    echo form_open('admin/passwordreset_final', $attributes);
    echo '<h2 class="form-signin-heading">Dernière étape</h2>';
    echo form_password('password', set_value(''), 'placeholder="Mot de passe" id="password" onchange="validatePassword()"  required minlength=8');
    echo form_password('passwordConfirm', set_value(''), 'placeholder="Confirmation mot de passe" id="passwordConfirm" onkeyup="validatePassword()"  required minlength=8');
    echo form_submit('submit', 'submit', 'class="btn btn-large btn-primary" id="btnSubmit"');
    echo form_close();
    ?>
</div>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</body>
</html>