(function($){
    $('.sb-clean-post-revision').on('click', function(e){
        e.preventDefault();
        sb_core.sb_ajax_loader(true);
        var that = $(this),
            data = null;
        data = {
            action: 'sb_clean_post_revision'
        };
        $.post(sb_core_admin_ajax.url, data, function(resp){
            sb_core.sb_ajax_loader(false);
        });
    });
})(jQuery);