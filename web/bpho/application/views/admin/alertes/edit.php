<div class="container top">

    <div class="page-header">
        <h2>
            Alertes de l'événement
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
            echo 'Alerte de l\'événement ajoutée avec succès !';
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

    echo form_open('admin/alertes/update/'.$this->uri->segment(4).'', $attributes);
    ?>
    <fieldset>
        <div class="control-group">
            <label for="inputError" class="control-label">Message : </label>
            <div class="controls" style="margin-left: 230px;">
                <textarea style="max-width: 620px; max-height: 350px; width: 360px; height: 130px;" id="messageAlerte" name="messageAlerte"><?php echo set_value('messageAlerte'); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Ajouter</button>
        </div>
    </fieldset>
    <?php echo form_close(); ?>
    <?php

    if ($countAlertes > 0) {
        foreach ($alertes as $alerte) {
            echo "<div style='border-style: solid; border-width: 0px 0px 1px 0px;'>";
            echo "<b>" . $alerte['date'] . "</b>";
            echo "<br><br>";
            echo $alerte['text'];
            echo "<br>";
            echo "</div>";
            echo "<br>";
        }
    } else {
        echo "Aucune alerte n'a encore été envoyée pour cet événement.";
    }

    ?>
</div>
