<div id="happyforms-modal">
    <div>
        <select class="happyforms-dialog__select" id="happyforms-dialog-select">
            <?php $forms = $this->get_form_data_array(); ?>
            <option value=""><?php _e( 'Choose a form', 'happyforms' ); ?></option>
            <?php foreach ( $forms as $form ) : ?>
            <option value="<?php echo $form['id']; ?>"><?php echo $form['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="button-primary happyforms-dialog__button"><?php _e( 'Insert', 'happyforms' ); ?></button>
</div>
