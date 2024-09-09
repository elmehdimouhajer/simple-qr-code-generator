jQuery(document).ready(function($) {
    $('#upload_logo_button').click(function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Logo',
            multiple: false
        }).open()
            .on('select', function() {
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#logo_url').val(image_url);
                $('#logo_preview').html('<img src="' + image_url + '" style="max-width:100px;" />');
            });
    });

    $('#generate_qr_code_button').on('click', function () {
        var postId = $('#post_ID').val();
        var nonce = SimpleQrCodeGenerator.nonce;

        $.ajax({
            url: SimpleQrCodeGenerator.ajax_url,
            type: 'POST',
            data: {
                action: 'generate_qr_code',
                post_id: postId,
                nonce: nonce
            },
            success: function (response) {
                if (response.success) {
                    $('#qr_code_preview').html('<img src="' + response.data.imageUrl + '" alt="QR Code" style="max-width:100%;" />');
                    $('#generate_qr_code_button').text(SimpleQrCodeGenerator.regenerate_text);
                } else {
                    alert('QR code generation failed.');
                }
            }
        });
    });
});