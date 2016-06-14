<div class="container top">

    <div class="page-header">
        <h2>
            Ajouter une alerte
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
            redirect('admin/alertes/add');
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

    echo form_open_multipart('admin/alertes/add', $attributes);
    ?>
    <fieldset>
        <div class="control-group">
            <label for="inputError" class="control-label" >Adresse : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="addressInput" name="address" value="<?php echo set_value('address'); ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" id="btnSubmit" type="submit">Sauvegarder</button>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
