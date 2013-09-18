<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Video URL Field Type
 *
 * @package		PyroStreams
 * @author		Rigo B Castro
 * @author		Jose Luis Fonseca
 * @team                WeDreamPro
 * @copyright           Copyright (c) 2013, WeDreamPro
 * @link                https://www.pyrocms.com/store/details/video_url_field_type
 */
class Field_video_url {

    public $field_type_slug = 'video_url';
    public $db_col_type = 'text';
    public $version = '1.1.0';
    public $custom_parameters = array('video_heigth', 'video_width', 'video_autoplay');
    public $author = array('name' => 'Rigo B Castro', 'url' => 'http://rigobcastro.com');

    // --------------------------------------------------------------------------

    /**
     * Output form input
     *
     * @access	public
     * @param   $params	array
     * @return	string
     */
    public function form_output($params)
    {
        $video_info = !empty($params['value']) ? json_decode($params['value']) : null;

        $input_options = array(
            'name' => $params['form_slug'] . '_url',
            'type' => 'text',
            'id' => $params['form_slug'],
            'data-fieldtype' => 'video_url',
            'data-video-width' => !empty($params['video_width']) ? $params['video_width'] : '100%',
            'data-video-width' => !empty($params['video_width']) ? $params['video_width'] : '100%',
            'data-video-autoplay' => !empty($params['video_autoplay']) ? $params['video_autoplay'] : 0,
            'value' => !empty($video_info->url) ? $video_info->url : null,
            'placeholder' => lang('streams:video_url.input_placeholder')
        );

        $input_hidden_options = array(
            $params['form_slug'] => $params['value']
        );

        return $this->CI->type->load_view('video_url', 'input', array(
                'input_options' => $input_options,
                'input_hidden_options' => $input_hidden_options
        ));
    }

    // --------------------------------------------------------------------------

    /**
     * Custom parameters
     * @author Jose Fonseca <jose@ditecnologia.com>
     */
    public function param_video_heigth($value = null)
    {
        return array(
            'input' => form_input('video_heigth', $value),
            'instructions' => $this->CI->lang->line('streams.video_heigth.instructions')
        );
    }

    public function param_video_width($value = null)
    {
        return array(
            'input' => form_input('video_width', $value),
            'instructions' => $this->CI->lang->line('streams.video_width.instructions')
        );
    }

    public function param_video_autoplay($value = null)
    {
        $options = array(
            '0' => $this->CI->lang->line('streams.video_width.no'),
            '1' => $this->CI->lang->line('streams.video_width.yes')
        );
        return array(
            'input' => form_dropdown('video_autoplay', $options, $value),
            'instructions' => $this->CI->lang->line('streams.video_autoplay.instructions')
        );
    }

    // --------------------------------------------------------------------------

    /**
     * Tag output variables
     *
     *
     * @access 	public
     * @param	string
     * @param	array
     * @return	array
     */
    public function pre_output($input, $params)
    {
        if (!$input)
            return null;
        
        $data = json_decode($input);
        
        return $data->html;
    }

    // ----------------------------------------------------------------------

    public function pre_output_plugin($input)
    {
        $data = json_decode($input);
        
        if(!empty($data->thumbnail_url)){
            $data->thumbnail_img = img($data->thumbnail_url);
        }

        return (array) $data;
    }

    // ----------------------------------------------------------------------

    /**
     * Event
     *
     * Load assets
     *
     * @access public
     * @param $field object
     * @return void
     */
    public function event()
    {
        $this->CI->type->add_js('video_url', 'video_url.js');
    }

}
