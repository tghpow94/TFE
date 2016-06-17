<div class="container top">

    <div class="page-header">
        <h2>
            Mise à jour de l'utilisateur
        </h2>
    </div>

    <?php
    if (!isset($user['id'])) {
        redirect('admin/users');
    }
    //flash messages
    if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
            echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo 'Données de l\'utilisateur mises à jour avec <strong>succès</strong> !';
            echo '</div>';
        } else{
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
            <label for="inputError" class="control-label">Nom * : </label>
            <div class="controls">
                <input type="text" id="" name="name" value="<?php echo $user['name']; ?>" required>
                <!--<span class="help-inline">Woohoo!</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Prénom * : </label>
            <div class="controls">
                <input type="text" id="" name="firstName" value="<?php echo $user['firstName']; ?>" required>
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Droit * : </label>
            <div class="controls">
                <select id="rights" name="right">
                    <?php
                    foreach($rights as $right) {
                        echo '<option value="'.$right["id"].'" ';
                        if ($right['id'] == $user['idRight']) {
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
                <input type="text" id="instrumentInput_1" list="instruments" name="instrument_1" value="<?php
                    if ($hasInstrument) {
                        echo $user['instruments'][0]['name'];
                    }
                ?>">
                <?php
                $idInput = "instrumentInput_";
                $nameInput = "instrument_";
                $i = 2;
                for($i; $i < count($user['instruments'])+1; $i++) {
                    echo '<br>';
                    echo '<input style="margin-left: 73px; margin-top: 10px;" type="text" list="instruments" id="'.$idInput.strval($i).'" name="'.$nameInput.strval($i).'" value="'.$user['instruments'][$i-1]['name'].'">';
                }
                ?>
                <button style="margin-left: 10px;" class="btn btn-primary" id="btnAddInstru" type="button">+</button>
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
                <input type="text" id="" name="phone" value="<?php echo $user['phone']; ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Sauvegarder</button>
            <button class="btn" type="reset">Annuler</button>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
     