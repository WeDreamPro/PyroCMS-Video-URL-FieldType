<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Video URL Field Type
 *
 * @package		PyroStreams
 * @author		Rigo B Castro
 * @copyright           Copyright (c) 2013, Rigo B Castro
 */
class Field_video_url {

    public $field_type_slug = 'video_url';
    public $db_col_type = 'text';
    public $version = '1.0.0';
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
     * Tag output variables
     *
     * Outputs 'latitude' & 'longitude' variables
     *
     * @access 	public
     * @param	string
     * @param	array
     * @return	array
     */
    public function pre_output_plugin($input)
    {
        if (!$input)
            return null;

        $location = json_decode($input);

        # Maintain backward compatability
        if (!is_object($location))
        {
            $pieces = explode(',', $input);
            if (count($pieces) != 2)
                return null;

            $array = array(
                'lat' => $pieces[0],
                'lng' => $pieces[1],
                'address' => null,
            );

            $location = (object) $array;
        }

        $data = array(
            'latitude' => $location->lat,
            'longitude' => $location->lng,
            'lat' => $location->lat,
            'lng' => $location->lng,
            'address' => $location->address,
        );

        return $data;
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
    public function event($field)
    {
        $this->CI->type->add_js('video_url', 'video_url.js');
    }
}
