<div class="container top">

    <div class="page-header users-header">
        <h2>
            Alertes
            <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success">Ajouter un nouveau</a>
        </h2>
    </div>

    <div class="row">
        <div class="span12 columns">
            <div class="well">

                <?php

                $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');

                echo form_open('admin/alertes', $attributes);

                echo form_label('Search:', 'search_string');
                echo form_input('search_string', $search_string_selected, 'style="width: 170px;
height: 26px; margin-right: 20px;"');

                $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

                echo form_submit($data_submit);

                echo form_close();
                ?>

            </div>

            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <!--<th class="header">#</th>-->
                    <th class="yellow header headerSortDown">Id</th>
                    <th class="red header">idEvent</th>
                    <th class="red header">Text</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($alertes as $row) {

                    echo '<tr>';
                    echo '<td style="display:none;">'.$row['id'].'</td>';
                    echo '<td class="testTD">'.$row['id'].'</td>';
                    echo '<td class="testTD">'.$row['idEvent'].'</td>';
                    echo '<td class="testTD">'.$row['text'].'</td>';
                    echo '<td style="width: 210px;" class="crud-actions">
                  <a href="'.site_url("admin").'/alertes/update/'.$row['id'].'" class="btn btn-info">Editer</a>
                  <a href="'.site_url("admin").'/alertes/delete/'.$row['id'].'" class="btn btn-danger">Supprimer</a>
                </td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>

            <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

        </div>
    </div>