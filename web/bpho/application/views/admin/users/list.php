<div class="container top">

    <div class="page-header users-header">
        <h2>
            Utilisateurs
            <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success">Ajouter un nouveau</a>
        </h2>
    </div>

    <div class="row">
        <div class="span12 columns">
            <div class="well">

                <?php

                $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');

                echo form_open('admin/users', $attributes);

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
                    <th class="yellow header headerSortDown">Prénom</th>
                    <th class="green header">Nom</th>
                    <th class="red header">E-mail</th>
                    <th class="red header">Date d'inscription</th>
                    <th class="red header">Dernière connexion</th>
                    <th class="red header">Téléphone</th>
                    <th class="red header">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $test = "test";
                foreach($users as $row)
                {
                    echo '<tr>';
                    echo '<td style="display:none;">'.$row['id'].'</td>';
                    echo '<td>'.$row['firstName'].'</td>';
                    echo '<td>'.$row['name'].'</td>';
                    echo '<td>'.$row['email'].'</td>';
                    echo '<td>'.$row['dateRegister'].'</td>';
                    echo '<td>'.$row['dateLastConnect'].'</td>';
                    echo '<td>'.$row['phone'].'</td>';
                    echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/users/update/'.$row['id'].'" class="btn btn-info">Editer</a>  
                  <a href="'.site_url("admin").'/users/delete/'.$row['id'].'" class="btn btn-danger" >Supprimer</a>
                </td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>


            <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

        </div>
    </div>