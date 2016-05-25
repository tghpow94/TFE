<div class="container top">

    <div class="page-header">
        <h2>
            Mise à jour de l'événement
        </h2>
    </div>

    <?php
    if (!isset($event['id'])) {
        redirect('admin/events');
    }
    //flash messages
    if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
            echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo 'Données de l\'événement mises à jour avec succès !';
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

    echo form_open('admin/events/update/'.$this->uri->segment(4).'', $attributes);
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
                <input placeholder="Français" style="width: 300px;" type="text" id="titleFRInput" name="titleFR" value="<?php echo $event['titleFR']; ?>" required>
                <input placeholder="Néerlandais" style="width: 300px; display: none" type="text" id="titleNLInput" name="titleNL" value="<?php echo $event['titleNL']; ?>">
                <input placeholder="Anglais" style="width: 300px; display: none" type="text" id="titleENInput" name="titleEN" value="<?php echo $event['titleEN']; ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Description : </label>
            <div class="controls" style="margin-left: 230px;">
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px;" class="description" placeholder="Français" id="descriptionFRInput" name="descriptionFR" required><?php echo  $event['descriptionFR']; ?></textarea>
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px; display: none" class="description" placeholder="Néerlandais" id="descriptionNLInput" name="descriptionNL" ><?php echo $event['descriptionNL']; ?></textarea>
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px; display: none" class="description" placeholder="Anglais" id="descriptionENInput" name="descriptionEN"  ><?php echo $event['descriptionEN']; ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Date : </label>
            <div id="datetimepicker" class="input-append date">
                <input style="width: 300px;" type="text" id="dateInput" name="date" value="<?php echo $event['startDate']; ?>" required></input>
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
                    firstDay: 1,
                    language: 'fr',
                    pickSeconds: false
                });
            </script>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Ville : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="cityInput" name="city" value="<?php echo $event['city']; ?>" required>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Code postal : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="cityCodeInput" name="cityCode" value="<?php echo $event['cityCode']; ?>" >
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label" >Adresse : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="addressInput" name="address" value="<?php echo $event['address']; ?>" required>
            </div>
        </div>

        <div class="control-group">
            <label for="inputError" class="control-label">Lieu / bâtiment : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="addressInfosInput" name="addressInfos" value="<?php echo $event['addressInfos']; ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Prix : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="priceInput" name="price" value="<?php echo $event['price']; ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Lien de réservation : </label>
            <div class="controls">
                <input style="width: 300px;" type="url" id="reservationInput" name="reservation" value="<?php echo $event['reservation']; ?>">
            </div>
        </div>
        <output style="margin-left: 250px;" id="list">
            <img id="eventImage" src="http://91.121.151.137/TFE/images/e<?php echo $event['id']; ?>.jpg">
        </output>

        <div class="control-group">
            <label for="inputError" class="control-label">Image : </label>
            <div class="controls">
                <input style="width: 300px;" type="file" accept="image/gif, image/png, image/jpg, image/jpeg" id="imageInput" name="image">
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Sauvegarder</button>
            <button class="btn" type="reset">Annuler</button>
        </div>
    </fieldset>
    <script>
        document.getElementById('imageInput').addEventListener('change', handleFileSelect, false);
        function handleFileSelect(evt) {
            var files = evt.target.files;
            var f = files[0];
            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {
                    document.getElementById('list').innerHTML = ['<img src="', e.target.result,'" title="', theFile.name, '" width="250" />'].join('');
                };
            })(f);

            reader.readAsDataURL(f);
        }

    </script>

    <?php echo form_close(); ?>

</div>
     