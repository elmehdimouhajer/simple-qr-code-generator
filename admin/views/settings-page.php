<div class="wrap">
    <h1><?php esc_html_e('Simple QR Code Generator Settings', 'simple-qr-code-generator'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'simple_qr_code_generator_options_group' );
        do_settings_sections( 'simple-qr-code-generator' );
        submit_button();
        ?>
    </form>
</div>