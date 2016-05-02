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
        <div class="btn-group" style="margin-left: 230px; margin-bottom: 30px;">
            <button type="button" name="FR" class="btn btn-primary">Français</button>
            <button type="button" name="NL" class="btn btn-primary">Nederlands</button>
            <button type="button" name="EN" class="btn btn-primary">English</button>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Titre : </label>
            <div class="controls">
                <input placeholder="testfr" type="text" id="titleFRInput" name="titleFR" value="<?php echo set_value('titleFR'); ?>">
                <input placeholder="testnl" style="display: none" type="text" id="titleNLInput" name="titleNL" value="<?php echo set_value('titleNL'); ?>">
                <input placeholder="testen" style="display: none" type="text" id="titleENInput" name="titleEN" value="<?php echo set_value('titleEN'); ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Description : </label>
            <div class="controls">
                <textarea placeholder="testfr" id="descriptionFRInput" name="descriptionFR" content="<?php echo set_value('descriptionFR'); ?>" ></textarea>
                <textarea placeholder="testnl" style="display: none" id="descriptionNLInput" name="descriptionNL" content="<?php echo set_value('descriptionNL'); ?>" ></textarea>
                <textarea placeholder="testen" style="display: none" id="descriptionENInput" name="descriptionEN" content="<?php echo set_value('descriptionEN'); ?>" ></textarea>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Date : </label>
            <div id="datetimepicker" class="input-append date">
                <input type="text"></input>
                <span class="add-on">
                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                </span>
            </div>
            <script type="text/javascript"
                    src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js">
            </script>
            <script type="text/javascript"
                    src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js">
            </script>
            <script type="text/javascript"
                    src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js">
            </script>
            <script type="text/javascript"
                    src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.fr.js">
            </script>
            <script type="text/javascript">
                $('#datetimepicker').datetimepicker({
                    format: 'dd/MM/yyyy hh:mm',
                    language: 'fr'
                });
            </script>
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
     