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
            redirect('admin/events/add');
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

    echo form_open_multipart('admin/events/add', $attributes);
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
                <input placeholder="testfr" type="text" id="titleFRInput" name="titleFR" value="<?php echo set_value('titleFR'); ?>" required>
                <input placeholder="testnl" style="display: none" type="text" id="titleNLInput" name="titleNL" value="<?php echo set_value('titleNL'); ?>">
                <input placeholder="testen" style="display: none" type="text" id="titleENInput" name="titleEN" value="<?php echo set_value('titleEN'); ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Description : </label>
            <div class="controls">
                <textarea placeholder="testfr" id="descriptionFRInput" name="descriptionFR" content="<?php echo set_value('descriptionFR'); ?>" required><?php echo set_value('descriptionFR'); ?></textarea>
                <textarea placeholder="testnl" style="display: none" id="descriptionNLInput" name="descriptionNL" content="<?php echo set_value('descriptionNL'); ?>" ><?php echo set_value('descriptionNL'); ?></textarea>
                <textarea placeholder="testen" style="display: none" id="descriptionENInput" name="descriptionEN" content="<?php echo set_value('descriptionEN'); ?>" ><?php echo set_value('descriptionEN'); ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Date : </label>
            <div id="datetimepicker" class="input-append date">
                <input type="text" id="dateInput" name="date" value="<?php echo set_value('date'); ?>" required></input>
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
                    src="<?php echo base_url(); ?>assets/js/datetimepicker.fr.js">
            </script>
            <script type="text/javascript">
                $('#datetimepicker').datetimepicker({
                    format: 'dd/MM/yyyy hh:mm',
                    language: 'fr',
                    pickSeconds: false
                });
            </script>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Ville : </label>
            <div class="controls">
                <input type="text" id="cityInput" name="city" value="<?php echo set_value('city'); ?>" required>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Code postal : </label>
            <div class="controls">
                <input type="text" id="cityCodeInput" name="cityCode" value="<?php echo set_value('cityCode'); ?>" >
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label" >Adresse : </label>
            <div class="controls">
                <input type="text" id="addressInput" name="address" value="<?php echo set_value('address'); ?>" required>
            </div>
        </div>

        <div class="control-group">
            <label for="inputError" class="control-label">Lieu / bâtiment : </label>
            <div class="controls">
                <input type="text" id="addressInfosInput" name="addressInfos" value="<?php echo set_value('addressInfos'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Prix : </label>
            <div class="controls">
                <input type="text" id="priceInput" name="price" value="<?php echo set_value('price'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Lien de réservation : </label>
            <div class="controls">
                <input type="text" id="reservationInput" name="reservation" value="<?php echo set_value('reservation'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Image : </label>
            <div class="controls">
                <input type="file" accept="image/gif, image/png, image/jpg, image/jpeg" id="imageInput" name="image" value="<?php echo set_value('image'); ?>">
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" id="btnSubmit" type="submit">Sauvegarder</button>
</div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
     