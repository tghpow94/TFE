<div class="container top">

    <div class="page-header">
        <h2>
            Ajouter un événement
        </h2>
    </div>

    <?php
    //flash messages
    if(isset($flash_message)){
        if($flash_message == TRUE)
        {
            echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo 'Utilisateur ajouté avec <strong>succès</strong> !';
            echo '</div>';
        }else{
            echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Adresse email déjà utilisée !</strong> Veuillez en choisir une autre.';
            echo '</div>';
        }
    }
    ?>

    <?php
    //form data
    $attributes = array('class' => 'form-horizontal', 'id' => 'formAddEvent');

    //form validation
    echo validation_errors();

    echo form_open('admin/events/add', $attributes);
    ?>
    <fieldset>
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
        </script>
        <div class="control-group">
            <label for="inputError" class="control-label">Titre : </label>
            <div class="controls">
                <input type="text" id="titleInput" name="title" value="<?php echo set_value('title'); ?>" required>
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Mot de passe* : </label>
            <div class="controls">
                <input type="password" onkeyup="myFunction()" id="password" name="password" value="<?php echo set_value('password'); ?>" required minlength=8>
                <span id="passwordLength" style="color:red; visibility: collapse">Minimum 8 caractères.</span>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Confirmation mot de passe* : </label>
            <div class="controls">
                <input type="password" onkeyup="myFunction()" id="password2" name="password2" value="<?php echo set_value('password2'); ?>" required minlength=8>
                <span id="passwordMatch" style="color:red; visibility: collapse">Les deux mots de passe saisis ne sont pas identiques.</span>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Nom* : </label>
            <div class="controls">
                <input type="text" id="nameInput" name="name" value="<?php echo set_value('name'); ?>" required>
                <!--<span class="help-inline">Woohoo!</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Prénom* : </label>
            <div class="controls">
                <input type="text" id="firstNameInput" name="firstName" value="<?php echo set_value('firstName'); ?>" required>
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Droit* : </label>
            <div class="controls">
                <select id="rights" name="right" value="<?php echo set_value('right'); ?>">
                    <?php
                    foreach($rights as $right) {
                        echo '<option value="'.$right["id"].'">'.$right["name"].'</option>';
                    }
                    ?>
                </select>
                <!--<span class="help-inline">OOps</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Instrument : </label>
            <div class="controls">
                <input type="text" id="instrumentInput" list="instruments" name="instrument" value="<?php echo set_value('instrument'); ?>">
                <datalist id="instruments" >
                    <?php
                    foreach($instruments as $instrument) {
                        echo '<option value="'.$instrument["name"].'">';
                    }
                    ?>
                </datalist>
            </div>
        </div>

        <div class="control-group">
            <label for="inputError" class="control-label">Téléphone : </label>
            <div class="controls">
                <input type="tel" id="phoneInput" name="phone" value="<?php echo set_value('phone'); ?>">
                <!--<span class="help-inline">OOps</span>-->
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" id="btnSubmit" type="submit">Sauvegarder</button>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
     