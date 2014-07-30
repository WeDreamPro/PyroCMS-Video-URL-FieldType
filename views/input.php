<?php echo form_input($input_options) ?>

<?php if($this->uri->segment(1) !== 'admin'): ?>
	<script type="text/javascript" src="<?=base_url('streams_core/field_asset/js/video_url/video_url.js');?>"></script>
<?php endif;?>

<div class="preview_video_url" style="display: none;">
    <div class="iframe-preview"></div>
    <?php echo form_hidden($input_hidden_options) ?>
</div>
