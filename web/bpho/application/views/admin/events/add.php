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
            echo 'Evénement ajouté avec <strong>succès</strong> !';
            echo '</div>';
        }else{
            echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Erreur !</strong> Vérifiez les informations de l\'événement.';
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
    <script>
        $(document).ready(function() {
            $('option').mousedown(function(e) {
                e.preventDefault();
                $(this).prop('selected', !$(this).prop('selected'));
                return false;
            });
        });
        var users = <?php echo json_encode($users); ?>;
        var liste = new Array(users.length);
        var i = 0;
        users.forEach(function(user) {
            liste[i] = new Array(4);
            liste[i][0] = user['id'];
            liste[i][1] = user['firstName'];
            liste[i][2] = user['name'];
            liste[i][3] = user['instrument'];
            i = i + 1;
        });
    </script>
    <fieldset>
        <div class="btn-group" style="margin-left: 230px; margin-bottom: 30px;">
            <button type="button" name="FR" class="btn btn-primary">Français</button>
            <button type="button" name="NL" class="btn btn-primary">Nederlands</button>
            <button type="button" name="EN" class="btn btn-primary">English</button>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Titre : </label>
            <div class="controls">
                <input placeholder="Français" style="width: 300px;" type="text" id="titleFRInput" name="titleFR" value="<?php echo set_value('titleFR'); ?>" required>
                <input placeholder="Néerlandais" style="width: 300px; display: none" type="text" id="titleNLInput" name="titleNL" value="<?php echo set_value('titleNL'); ?>">
                <input placeholder="Anglais" style="width: 300px; display: none" type="text" id="titleENInput" name="titleEN" value="<?php echo set_value('titleEN'); ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Description : </label>
            <div class="controls" style="margin-left: 230px;">
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px;" placeholder="Français" id="descriptionFRInput" name="descriptionFR"><?php echo set_value('descriptionFR'); ?></textarea>
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px; display: none" placeholder="Néerlandais" id="descriptionNLInput" name="descriptionNL"  ><?php echo set_value('descriptionNL'); ?></textarea>
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px; display: none" placeholder="Anglais" id="descriptionENInput" name="descriptionEN"  ><?php echo set_value('descriptionEN'); ?></textarea>
            </div>
        </div>
        <script>
            function changeCat() {
                if (document.getElementById("categorie").value == 1) {
                    document.getElementById("blocConcert").style.display = "block";
                } else {
                    document.getElementById("blocConcert").style.display = "none";
                }
            }
        </script>
        <div class="control-group">
            <label for="inputError" class="control-label">Catégorie : </label>
            <div class="controls">
                <select style="width: 310px;" onchange="changeCat()" id="categorie" name="categorie" value="<?php echo set_value('categorie'); ?>">
                    <?php
                    foreach($categories as $categorie) {
                        echo '<option value="'.$categorie["id"].'">'.$categorie["name"].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="control-group" id="blocConcert" >
            <label for="inputError" class="control-label">Concert associé : </label>
            <div class="controls">
                <select style="width: 310px;" id="concert" name="concert" value="<?php echo set_value('concert'); ?>">
                    <option value="0"></option>
                    <?php
                    foreach($concerts as $concert) {
                        echo '<option value="'.$concert["id"].'">'.$concert["title"].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label for="inputError" class="control-label">Date : </label>
            <div id="datetimepicker" class="input-append date">
                <input style="width: 300px;" type="datetime" onkeyup="myFunction()"  id="dateInput" name="date" value="<?php echo date("d/m/Y H:i"); ?>" min="<?php echo date("d/m/Y H:i"); ?>" required>
                <span class="add-on">
                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                </span>
                <span style="color: red; visibility: collapse" id="dateError">La date de l'événement ne peut pas être dépassée.</span>
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

                $( document ).ready(function() {
                    $(".day").attr("onclick","myFunction()");
                    $(".btn").attr("onclick","myFunction()");
                });

                function myFunction() {
                    setTimeout(function(){
                        var date = document.getElementById("dateInput").value;
                        var check;
                        if (date != "") {
                            date = date.split(" ");
                            date = date[0].split("/");
                            var day = date[0];
                            var month = date[1];
                            var year = date[2];
                            date = new Date(year, month - 1, day, 0, 0, 0, 0);
                            var today = new Date();
                            var dd = today.getDate();
                            var mm = today.getMonth();
                            var yyyy = today.getFullYear();

                            today = new Date(yyyy, mm, dd, 0, 0, 0, 0);

                            if (date.getTime() < today.getTime()) {
                                document.getElementById("dateError").style.visibility = "visible";
                                document.getElementById("btnSubmit").disabled = true;
                                check = false;
                            } else {
                                document.getElementById("dateError").style.visibility = "collapse";
                                document.getElementById("btnSubmit").disabled = false;
                                check = true;
                            }
                        }
                    }, 100);
                    setTimeout(function(){
                        $(".day").attr("onclick","myFunction()");
                        $(".btn").attr("onclick","myFunction()");
                    }, 100);
                    return check;
                }

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
                <input style="width: 300px;" type="text" id="cityInput" name="city" value="<?php echo set_value('city'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Code postal : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="cityCodeInput" name="cityCode" value="<?php echo set_value('cityCode'); ?>" >
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label" >Adresse : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="addressInput" name="address" value="<?php echo set_value('address'); ?>">
            </div>
        </div>

        <div class="control-group">
            <label for="inputError" class="control-label">Lieu / bâtiment : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="addressInfosInput" name="addressInfos" value="<?php echo set_value('addressInfos'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Prix : </label>
            <div class="controls">
                <input style="width: 300px;" type="text" id="priceInput" name="price" value="<?php echo set_value('price'); ?>">
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Lien de réservation : </label>
            <div class="controls">
                <input style="width: 300px;" type="url" id="reservationInput" name="reservation" value="<?php echo set_value('reservation'); ?>">
            </div>
        </div>
        <output style="margin-left: 250px;" id="list">
        </output>
        <div class="control-group">
            <label for="inputError" class="control-label">Image : </label>
            <div class="controls">
                <input style="width: 300px;" type="file" accept="image/gif, image/png, image/jpg, image/jpeg" id="imageInput" name="image" value="<?php echo set_value('image'); ?>">
            </div>
        </div>

        <div class="control-group">
            <label for="inputError" class="control-label">Musiciens associés : </label>
            <div class="controls">

                <select name="users[]" form="formAddEvent" multiple="multiple" size="10" style="width: 520px;">
                    <?php
                    $lettre = "A";
                    echo '<option style="font-weight: bold; font-size: 150%;" disabled>' . $lettre;
                    foreach($users as $user) {
                        if ($lettre != $user['firstName'][0]) {
                            $lettre = $user['firstName'][0];
                            echo '<option style="font-weight: bold; font-size: 150%;" disabled>' . $lettre;
                        }
                        echo '<option value="'.$user['id'].'">'.$user['firstName'].' '.$user['name'].' - ';
                        if(is_array($user['instruments'])) {
                            $i = 0;
                            foreach ($user['instruments'] as $instrument) {
                                if($i > 0 )
                                    echo ", ";
                                echo $instrument['name'];
                                $i++;
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" id="btnSubmit" type="submit">Sauvegarder</button>
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
                    document.getElementById('list').innerHTML = ['<img src="', e.target.result,'" title="', theFile.name, '" width="150" />'].join('');
                };
            })(f);

            reader.readAsDataURL(f);
        }

    </script>

    <?php echo form_close(); ?>

</div>
     