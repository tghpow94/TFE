<!DOCTYPE html> 
<html lang="en-US">
  <head>
    <title>CodeIgniter Admin Sample Project</title>
    <meta charset="utf-8">
    <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container login">
      <?php 
      $attributes = array('class' => 'form-signin');
      echo form_open('admin/login/validate_credentials', $attributes);
      echo '<h2 class="form-signin-heading">Login</h2>';
      echo form_input('user_name', '', 'placeholder="Adresse e-mail"');
      echo form_password('password', '', 'placeholder="Mot de passe"');
      if(isset($message_error) && $message_error){
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo 'Identifiants incorrects, ré-essayez.';
          echo '</div>';
      }
      if(isset($droit_error) && $droit_error){
        echo '<div class="alert alert-error">';
        echo '<a class="close" data-dismiss="alert">×</a>';
        echo 'Permission insuffisante, veuillez contacter un administrateur.';
        echo '</div>';
      }
      echo "<br />";
      echo '<a href="">Mot de passe oublié ?</a>';
      echo "<br />";
      echo "<br />";
      echo form_submit('submit', 'Login', 'class="btn btn-large btn-primary"');
      echo form_close();
      ?>



    </div><!--container-->
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  </body>
</html>    
    