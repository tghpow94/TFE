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

    //password.onchange = validatePassword;
    //confirm_password.onkeyup = validatePassword;

    function myFunction() {
        var pass1 = document.getElementById("password").value;
        var pass2 = document.getElementById("passwordConfirm").value;
        if (pass1.length < 8) {
            document.getElementById("password").style.borderColor = "#E34234";
            document.getElementById("passwordLength").style.visibility = "visible";
            document.getElementById("btnSubmit").disabled = true;
        } else {
            document.getElementById("btnSubmit").disabled = false;
            document.getElementById("password").style.borderColor = "#ccc";
            document.getElementById("passwordLength").style.visibility = "collapse"
        }
        if (pass1 != pass2) {
            document.getElementById("btnSubmit").disabled = true;
            document.getElementById("passwordConfirm").style.borderColor = "#E34234";
            document.getElementById("passwordMatch").style.visibility = "visible";
        } else {
            document.getElementById("btnSubmit").disabled = false;
            document.getElementById("passwordConfirm").style.borderColor = "#ccc";
            document.getElementById("passwordMatch").style.visibility = "collapse";
        }
        return ok;
    }
</script>
<div class="container login">
    <?php
    $attributes = array('class' => 'form-signin', 'style' => 'padding-left: 100px; padding-right: 100px;');
    echo form_open('admin/passwordreset_final', $attributes);
    echo '<h2 class="form-signin-heading">Dernière étape</h2>';
    echo form_password('password', set_value(''), 'placeholder="Mot de passe" id="password" onkeyup="myFunction()"  required minlength=8');
    echo form_password('passwordConfirm', set_value(''), 'placeholder="Confirmation mot de passe" id="passwordConfirm" onkeyup="myFunction()"  required minlength=8');
    echo form_submit('submit', 'submit', 'class="btn btn-large btn-primary" id="btnSubmit"');
    echo form_close();
    ?>
    <span id="passwordLength" style="color:red; visibility: collapse; margin-left: 250px;">Minimum 8 caractères.</span><br>
    <span id="passwordMatch" style="color:red; visibility: collapse; margin-left: 250px;">Les deux mots de passe saisis ne sont pas identiques.</span>
</div>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</body>
</html>