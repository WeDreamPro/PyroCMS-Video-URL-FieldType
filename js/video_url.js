(function($) {
    var inputs = '[data-fieldtype="video_url"]',
        youtube_regex = /(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?.*v=|\/)([^\s&]+)/,
        vimeo_regex = /(?:https?:\/{2})?(?:w{3}\.)?vim(?:eo)?\.(?:com)([^\s&]+)/;

    function get_video_from_url() {
        var $this = $(this),
            url = $this.val(),
            service_url = null,
            match = url.match(youtube_regex),
            $preview_content = $(this).parent().find('.preview_video_url'),
            video_id_tosave = 0;
            
        if (match !== null) {
            video_id_tosave = match[1];
            // service_url = 'http://gdata.youtube.com/feeds/api/videos/' + match[1] + '?v=2&alt=jsonc';
            service_url = 'http://www.youtube.com/oembed?url=' + encodeURIComponent(url);
        } else {
            match = url.match(vimeo_regex);

            if (match !== null) {
                // service_url = 'http://vimeo.com/api/v2/video'+match[1]+'.json';
                service_url = 'http://vimeo.com/api/oembed.json?url=' + encodeURIComponent(url);
            }
        }

        if (service_url !== null) {
            $.ajax({
                url: 'http://query.yahooapis.com/v1/public/yql',
                data: {
                    q: "select * from json where url ='" + service_url + "'",
                    format: "json"
                },
                dataType: "jsonp",
                beforeSend: function() {
                    $preview_content.find('.iframe-preview').empty();
                },
                success: function(yql_result) {
                    var data = yql_result.query.results.json;
                    if(video_id_tosave != 0 ){
                        data.video_id = video_id_tosave;
                    }
                    if (data) {
                        render_preview.call($this, data);
                    }
                },
                error: function(result) {
                    alert("Sorry no data found.");
                }
            });
        }
    }

    function render_preview(data) {
        var $preview_content = $(this).parent().find('.preview_video_url'),
            $iframe_content = $preview_content.find('.iframe-preview'),
            $hidden = $preview_content.find('[type="hidden"]');



        if (data.html) {
            var $video = $(data.html),
                video_html = $video.clone().attr('width', 500).attr('height', 300);

            $iframe_content.html(video_html);

            $video.attr('width', $(this).data('video-width')).attr('height', $(this).data('video-height'));
            
            if($(this).data('video-autoplay')){
                
            }
        }

        data = $.extend(data, {
            url: $(this).val(),
            html: $video[0].outerHTML
        });

        $hidden.val(JSON.stringify(data));

        return $preview_content.show();
    }

    $(document).on('keyup', inputs, get_video_from_url);

    $(document).on('click', inputs, function() {
        $(this).select();
    });

    $(document).on('click', '.show_video_url', function(e) {
        $.colorbox({href: this.href, width: '600px', height: '400px', iframe: true});
        return e.preventDefault();
    });

    $(function() {
        $.each($(inputs), function() {
            get_video_from_url.call(this);
        });
    });
})(window.jQuery);
