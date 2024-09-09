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
});