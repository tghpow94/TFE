<div class="container top">

    <div class="page-header">
        <h2>
            Updating <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
    </div>

    <?php
    //flash messages
    if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
            echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo 'Données de l\'utilisateur mises à jour avec succès !';
            echo '</div>';
        }else{
            echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
            echo '</div>';
        }
    }

    //form data
    $attributes = array('class' => 'form-horizontal', 'id' => '');

    //form validation
    echo validation_errors();

    echo form_open('admin/users/update/'.$this->uri->segment(4).'', $attributes);
    ?>
    <fieldset>
        <div class="control-group">
            <label for="inputError" class="control-label">Nom : </label>
            <div class="controls">
                <input type="text" id="" name="name" value="<?php echo $user[0]['name']; ?>" >
                <!--<span class="help-inline">Woohoo!</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Prénom : </label>
            <div class="controls">
                <input type="text" id="" name="firstName" value="<?php echo $user[0]['firstName']; ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Droit : </label>
            <div class="controls">
                <select id="rights" name="right">
                    <?php
                    foreach($rights as $right) {
                        echo '<option value="'.$right["id"].'" ';
                        if ($right['id'] == $user[0]['idRight']) {
                            echo 'selected="selected"';
                        }
                        echo ' >'.$right["name"].'</option>';
                    }
                    ?>
                </select>
                <!--<span class="help-inline">OOps</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Instrument : </label>
            <div class="controls">
                <input type="text" list="instruments" name="instrument" value="<?php echo set_value('instrument'); ?>">
                <datalist id="instruments" >
                    <?php
                    foreach($instruments as $instrument) {
                        echo '<option value="'.$instrument["name"].'" ';
                        if ($instrument['id'] == $user[0]['idInstrument']) {
                            echo 'selected="selected"';
                        }
                        echo ' >';
                    }
                    ?>
                </datalist>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Téléphone : </label>
            <div class="controls">
                <input type="text" id="" name="phone" value="<?php echo $user[0]['phone']; ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Sauvegarder</button>
            <button class="btn" type="reset">Remise à zéro</button>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
     