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
            echo 'Données de l\'événement mises à jour avec <strong>succès</strong> !';
            echo '</div>';
        }else{
            echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
            echo '</div>';
        }
    }

    //form data
    $attributes = array('class' => 'form-horizontal', 'id' => 'formEditEvent');

    //form validation
    echo validation_errors();

    echo form_open_multipart('admin/events/update/'.$this->uri->segment(4).'', $attributes);
    ?>
    <fieldset style="width: 105%;">
        <script>
            $(document).ready(function() {
                $('option').mousedown(function(e) {
                    e.preventDefault();
                    $(this).prop('selected', !$(this).prop('selected'));
                    return false;
                });
            });
        </script>
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
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px;" class="description" placeholder="Français" id="descriptionFRInput" name="descriptionFR"><?php echo  $event['descriptionFR']; ?></textarea>
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px; display: none" class="description" placeholder="Néerlandais" id="descriptionNLInput" name="descriptionNL" ><?php echo $event['descriptionNL']; ?></textarea>
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px; display: none" class="description" placeholder="Anglais" id="descriptionENInput" name="descriptionEN"  ><?php echo $event['descriptionEN']; ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Catégorie : </label>
            <div class="controls">
                <select style="width: 310px;" onchange="changeCat()" id="categorie" name="categorie">
                    <?php
                    foreach($categories as $categorie) {
                        echo '<option value="'.$categorie["id"].'" ';
                        if ($categorie['id'] == $event['categorie']) {
                            echo 'selected="selected"';
                        }
                        echo ' >'.$categorie["name"].'</option>';
                    }
                    ?>
                </select>
                <span id="warningChangeCat" style="color: orange; margin-left: 10px; visibility: collapse;">Les associations entre ce concert et ses répétitions seront supprimées</span>
            </div>
        </div>

        <div class="control-group" id="blocConcert"

            <?php
            if($event['categorie'] == 2) {
                echo ' style="display: none;" ';
            }
            ?>

        >
            <label for="inputError" class="control-label">Concert associé : </label>
            <div class="controls">
                <select style="width: 310px;" id="concert" name="concert" value="<?php echo set_value('concert'); ?>">
                    <option value="0"></option>
                    <?php
                    foreach($concerts as $concert) {
                        if($event['concert'] == $concert['id']) {
                            echo '<option value="' . $concert["id"] . '" selected>' . $concert["title"] . '</option>';
                        } else {
                            echo '<option value="' . $concert["id"] . '" >' . $concert["title"] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <input type="hidden" id="oldCategory" name="oldCategory" value="<?php echo $event['categorie']; ?>">

        <div class="control-group">
            <label for="inputError" class="control-label">Date : </label>
            <div id="datetimepicker" class="input-append date">
                <input style="width: 300px;" type="text" id="dateInput" name="date" value="<?php echo $event['startDate']; ?>" required>
                <span class="add-on">
                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                </span>
                <span style="color: orange; visibility: collapse; margin-left: 13px;" id="dateError">  La date de l'événement est dépassée.</span>
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

                function changeCat() {
                    if (document.getElementById("categorie").value == 1) {
                        document.getElementById("blocConcert").style.display = "block";
                    } else {
                        document.getElementById("blocConcert").style.display = "none";
                    }

                    if(document.getElementById("categorie").value == 1 && document.getElementById("oldCategory").value == 2) {
                        document.getElementById("warningChangeCat").style.visibility = "visible";
                    } else {
                        document.getElementById("warningChangeCat").style.visibility = "collapse";
                    }
                }

                $( document ).ready(function() {
                    myFunction();
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
                                check = false;
                            } else {
                                document.getElementById("dateError").style.visibility = "collapse";
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

        <div class="control-group">
            <label for="inputError" class="control-label">Musiciens associés : </label>
            <div class="controls">

                <select name="users[]" form="formEditEvent" multiple="multiple" size="10" style="width: 320px;">
                    <?php
                    $lettre = "A";
                    echo '<option style="font-weight: bold; font-size: 150%;" disabled>' . $lettre;
                    foreach($users as $user) {
                        if ($lettre != $user['firstName'][0]) {
                            $lettre = $user['firstName'][0];
                            echo '<option style="font-weight: bold; font-size: 150%;" disabled>' . $lettre;
                        }
                        $exist = false;
                        foreach($event['users'] as $user2) {
                            if ($user['id']  == $user2['idUser']) {
                                $exist = true;
                            }
                        }
                        if ($exist) {
                            echo '<option value="' . $user['id'] . '" selected>' . $user['firstName'] . ' ' . $user['name'] . ' - ';
                        } else {
                            echo '<option value="' . $user['id'] . '">' . $user['firstName'] . ' ' . $user['name'] . ' - ';
                        }
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
            <button class="btn btn-primary" type="submit">Sauvegarder</button>
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
                    document.getElementById('list').innerHTML = ['<img src="', e.target.result,'" title="', theFile.name, '" width="300" />'].join('');
                };
            })(f);

            reader.readAsDataURL(f);
        }

    </script>

    <?php echo form_close(); ?>

</div>
     