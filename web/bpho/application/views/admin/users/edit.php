<div class="container top">

    <div class="page-header">
        <h2>
            Updating <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
    </div>

    <?php
    //flash messages
    if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
            echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> User updated with success.';
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

    echo form_open('admin/users/update/'.$this->uri->segment(4).'', $attributes);
    ?>
    <fieldset>
        <div class="control-group">
            <label for="inputError" class="control-label">Description</label>
            <div class="controls">
                <input type="text" id="" name="description" value="<?php echo $user[0]['description']; ?>" >
                <!--<span class="help-inline">Woohoo!</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Stock</label>
            <div class="controls">
                <input type="text" id="" name="stock" value="<?php echo $user[0]['stock']; ?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Cost Price</label>
            <div class="controls">
                <input type="text" id="" name="cost_price" value="<?php echo $product[0]['cost_price'];?>">
                <!--<span class="help-inline">Cost Price</span>-->
            </div>
        </div>
        <div class="control-group">
            <label for="inputError" class="control-label">Sell Price</label>
            <div class="controls">
                <input type="text" name="sell_price" value="<?php echo $product[0]['sell_price']; ?>">
                <!--<span class="help-inline">OOps</span>-->
            </div>
        </div>
        <?php
        echo '<div class="control-group">';
        echo '<label for="manufacture_id" class="control-label">Manufacture</label>';
        echo '<div class="controls">';
        //echo form_dropdown('manufacture_id', $options_manufacture, '', 'class="span2"');

        echo form_dropdown('manufacture_id', $options_manufacture, $product[0]['manufacture_id'], 'class="span2"');

        echo '</div>';
        echo '</div">';
        ?>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
        </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
     