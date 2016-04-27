<div class="container top">

    <div class="page-header">
        <h2>
            Adding <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
    </div>

    <?php
    //flash messages
    if(isset($flash_message)){
        if($flash_message == TRUE)
        {
            echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> new product created with success.';
            echo '</div>';
        }else{
            echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
            echo '</div>';
        }
    }
    ?>

    <?php
    //form data
    $attributes = array('class' => 'form-horizontal', 'id' => 'formAddUser');

    //form validation
    echo validation_errors();

    echo form_open('admin/users/add', $attributes);
    ?>
    <fieldset>
        <script>
            function myFunction() {
                var pass1 = document.getElementById("password").value;
                var pass2 = document.getElementById("password2").value;
                if (pass1 != pass2 && pass2 != "") {
                    document.getElementById("password").style.borderColor = "#E34234";
                    document.getElementById("btnSubmit").disabled = true;
                    document.getElementById("password2").style.borderColor = "#E34234";
                    document.getElementById("passwordMatch").style.visibility = "visible";
                } else {
                    document.getElementById("password").style.borderColor = "#ccc";
                    document.getElementById("btnSubmit").disabled = false;
                    document.getElementById("password2").style.borderColor = "#ccc";
                    document.getElementById("passwordMatch").style.visibility = "collapse";
                }
                return ok;
            }
        </script>
        <div class="control-group">
            <label for="inputError" class="control-label">Adresse e-mail* : </label>
            <div class="controls">
                <input type="email" id="" name="email" value="<?php echo set_value('email'); ?>" required>
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Mot de passe* : </label>
            <div class="controls">
                <input type="password" onkeyup="myFunction()" id="password" name="password" value="<?php echo set_value('password'); ?>" required minlength=8>
                <span id="passwordMatch" style="color:red; visibility: collapse">Les deux mots de passe saisis ne sont pas identiques.</span>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Confirmation mot de passe* : </label>
            <div class="controls">
                <input type="password" onkeyup="myFunction()" id="password2" name="password2" value="<?php echo set_value('password2'); ?>" required minlength=8>
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Nom* : </label>
            <div class="controls">
                <input type="text" id="" name="name" value="<?php echo set_value('name'); ?>" required>
                <!--<span class="help-inline">Woohoo!</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Prénom* : </label>
            <div class="controls">
                <input type="text" id="" name="firstName" value="<?php echo set_value('firstName'); ?>" required>
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Téléphone : </label>
            <div class="controls">
                <input type="tel" name="phone" value="<?php echo set_value('phone'); ?>">
                <!--<span class="help-inline">OOps</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Instrument : </label>
            <div class="controls">
                <select name="instrument" >
                    <?php
                    $instrument1 = array("id" => 0, "name" => "violon");
                    $instrument2 = array("id" => 1, "name" => "piano");
                    $instrument3 = array("id" => 2, "name" => "guitare");

                    $instruments = array($instrument1, $instrument2, $instrument3);
                    foreach($instruments as $instrument) {
                        echo '<option value=$instrument["id"]>'.$instrument["name"].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" id="btnSubmit" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
     