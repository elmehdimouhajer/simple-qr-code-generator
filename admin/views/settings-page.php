<div class="wrap">
    <h1><?php _e( 'Simple QR Code Generator Settings', 'simple-qr-code-generator' ); ?></h1>
    <form method="post" action="">
        <?php
        settings_fields( 'simple_qr_code_generator_options_group' );
        do_settings_sections( 'simple-qr-code-generator' );
        submit_button();
        ?>
    </form>
</div>