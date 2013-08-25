(function($){
	var inputs = '[data-fieldtype="video_url"]';
	var provider = {'name': 'youtube'};
	function get_youtube_from_url(){
		var $this = $(this),
		match = $(this).val().match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?.*v=|\/)([^\s&]+)/),
		$preview_content = $(this).parent().find('.preview_video_url');
		if(match !== null){
			$.getJSON('http://gdata.youtube.com/feeds/api/videos/'+match[1]+'?v=2&alt=jsonc',function(data){
				provider.name = "youtube";
				return render_preview.call($this, data.data.id);
			});
		} else {
			$preview_content.hide();
		}
	}

	function get_vimeo_from_url(){
		var $this = $(this),
		match = $(this).val().match(/(?:https?:\/{2})?(?:w{3}\.)?vim(?:eo)?\.(?:com)([^\s&]+)/),
		$preview_content = $(this).parent().find('.preview_video_url');
		if(match !== null){
			provider.name = "vimeo";
			return render_preview.call($this, match[1]);
		} else {
			$preview_content.hide();
		}
	}

	function get_video_from_url(){
		var $this = $(this);
		service = $this.val().match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?.*v=|\/)([^\s&]+)/);
		if(service === null){
			get_vimeo_from_url.call($this);
		}else{
			get_youtube_from_url.call($this);
		}
	}

	function render_preview(id){
		var $preview_content = $(this).parent().find('.preview_video_url'),
		$iframe = $preview_content.find('iframe'),
		$hidden = $preview_content.find('[type="hidden"]');
		if(provider.name === "youtube"){
			var src = $iframe.data('youtube-src') +  id;
		}else{
			var src = $iframe.data('vimeo-src') +  id;
		}

		$iframe.attr('src', src);

		$hidden.val(JSON.stringify({
			url : $(this).val(),
			src : src
		}));

		return $preview_content.show();
	}

	$(document).on('keyup', inputs, get_video_from_url);

	$(document).on('click', inputs, function(){
		$(this).select();
	});

	$(document).on('click', '.show_video_url', function(e){
		$.colorbox({ href: this.href, width: '600px', height: '400px', iframe: true });
		return e.preventDefault();
	});

	$(function(){
		$.each($(inputs), function(){
			get_video_from_url.call(this);
		});
	});
})(window.jQuery);
