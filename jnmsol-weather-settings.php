<?php

/**
 * Name: Weather-JNM
 * Plugin URI:  https://jnmsolutions.co.za/weather-jnm
 * Description: A weather app & widget by JNM Solutions.
 * Version:     1.0
 * Author:      Jethro McNamee
 * Author URI:  https://jnmsolutions.co.za/
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html/
 * License:     GPL3
 * Weather-JNM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *  
 * Weather-JNM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with Weather-JNM. If not, see https://www.gnu.org/licenses/.
 */

class jnmweatherSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_wjnm_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'wjnm_page_init' ) );
    }    

    /**
     * Add options page
     */
    public function add_wjnm_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Weather-JNM', 
            'manage_options', 
            'jnmweather-admin-settings', 
            array( $this, 'create_wjnm_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_wjnm_admin_page()
    {
        // Set class property
        $this->options = get_option( 'widget_options' );
        ?>
        <div class="wrap">
            <h1>Weather JNM</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'location_group' );
                do_settings_sections( 'jnmweather-admin-settings' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function wjnm_page_init()
    {        
        register_setting(
            'location_group', // Option group
            'widget_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Location', // Title
            array( $this, 'print_section_info' ), // Callback
            'jnmweather-admin-settings' // Page
        );  

        add_settings_field(
            'API Key', // ID
            'API Key', // Title 
            array( $this, 'wjnm_apikey_callback' ), // Callback
            'jnmweather-admin-settings', // Page
            'setting_section_id' // Section           
        );
        
        add_settings_field(
            'City', // ID
            'City', // Title 
            array( $this, 'wjnm_city_callback' ), // Callback
            'jnmweather-admin-settings', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'Country', //ID
            'Country', //Title
            array( $this, 'wjnm_country_callback' ), // Callback
            'jnmweather-admin-settings', // Page
            'setting_section_id' //Section
        );      

        add_settings_field(
            'Unit', //ID
            'Unit', //Title
            array( $this, 'wjnm_unit_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );
        
        add_settings_field(
            'Show Weather Description', //ID
            'Show Weather Description', //Title
            array( $this, 'wjnm_weatherDesc_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );

        add_settings_field(
            'Show Real Feel Temp', //ID
            'Show Real Feel Temp', //Title
            array( $this, 'wjnm_realFeel_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );

        add_settings_field(
            'Show Wind Speed', //ID
            'Show Wind Speed', //Title
            array( $this, 'wjnm_windSpeed_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );

        add_settings_field(
            'Show Humidity', //ID
            'Show Humidity', //Title
            array( $this, 'wjnm_humidity_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );

        add_settings_field(
            'Location Text Color', //ID
            'Location Text Color', //Title
            array( $this, 'wjnm_locText_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );

        add_settings_field(
            'Temperature Text Color', //ID
            'Temperature Text Color', //Title
            array( $this, 'wjnm_tempText_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );

        add_settings_field(
            'Info Text Color', //ID
            'Info Text Color', //Title
            array( $this, 'wjnm_infoText_callback' ), // Callback
            'jnmweather-admin-settings', //Page
            'setting_section_id' //Section
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize_wjnm( $input )
    {
        $new_input = array();
        if( isset( $input['City'] ) )
            $new_input['City'] = sanitize_text_field( $input['City'] );

        if( isset( $input['Country'] ) )
            $new_input['Country'] = sanitize_text_field( $input['Country'] );

        if( isset( $input['Unit'] ) )
            $new_input['Unit'] = sanitize_text_field( $input['Unit'] );

        if( isset( $input['WeatherDesc'] ) )
            $new_input['WeatherDesc'] = $input['WeatherDesc'];
        
        if( isset( $input['RealFeel'] ) )
            $new_input['RealFeel'] = sanitize_text_field( $input['RealFeel'] );

        if( isset( $input['WindSpeed'] ) )
            $new_input['WindSpeed'] = sanitize_text_field( $input['WindSpeed'] );

        if( isset( $input['Humidity'] ) )
            $new_input['Humidity'] = sanitize_text_field( $input['Humidity'] );

        if( isset( $input['LocText'] ) )
            $new_input['LocText'] = sanitize_text_field( $input['LocText'] );

        if( isset( $input['TempText'] ) )
            $new_input['TempText'] = sanitize_text_field( $input['TempText'] );

        if( isset( $input['InfoText'] ) )
            $new_input['InfoText'] = sanitize_text_field( $input['InfoText'] );

        if( isset( $input['API Key'] ) )
            $new_input['API Key'] = sanitize_text_field( $input['API Key'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_apikey_callback()
    {
        printf(
            '<input type="text" id="apiKey" name="widget_options[wjnm_APIKey]" value="%s"/>
            <br/><br/>
            Don\'t have this yet? Get your free API key <a href="https://home.openweathermap.org/api_keys" target="_blank">here</a>.
            <br/>
            IMPORTANT! You must verify your email address with Open Weather to enable your API key.
            ',
            isset( $this->options['wjnm_APIKey'] ) ? esc_attr( $this->options['wjnm_APIKey']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_city_callback()
    {
        printf(
            '<input type="text" id="city" name="widget_options[City]" value="%s" />',
            isset( $this->options['City'] ) ? esc_attr( $this->options['City']) : 'Durban'
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_country_callback()
    { 
        printf(
            '<input type="text" id="Country" name="widget_options[Country]" value="%s" />',
            isset( $this->options['Country'] ) ? esc_attr( $this->options['Country']) : 'South Africa'
        );      
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_unit_callback()
    {
        $selectedOption = !empty($this->options['Unit']) ? $this->options['Unit'] : 'metric';
        if($selectedOption == 'metric') {$selectMetric = 'selected'; $selectImperial = '';}
        else {$selectMetric = ''; $selectImperial = 'selected';}

        printf(
            '<select id="unit" name="widget_options[Unit]" value="" >
                <option value="metric" ' . $selectMetric . '>Celcius</option>
                <option value="imperial" ' . $selectImperial . '>Fahrenheit</option>
            </select>'
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_weatherDesc_callback()
    {    
        /*
        $data = file_get_contents(plugin_dir_url( __FILE__ ) . 'city.list.min.json');
        $json = json_decode($data);
        */   
        $checked = !empty($this->options['WeatherDesc']) ? 'checked="checked"' : '';
        printf(
            '<input type="checkbox" id="weatherDesc" name="widget_options[WeatherDesc]" value="1"' . $checked . '/>'
        );      
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_realFeel_callback()
    {    
        /*
        $data = file_get_contents(plugin_dir_url( __FILE__ ) . 'city.list.min.json');
        $json = json_decode($data);
        */
        $checked = !empty($this->options['RealFeel']) ? 'checked="checked"' : '';
        printf(
            '<input type="checkbox" id="realFeel" name="widget_options[RealFeel]" value="1"' . $checked . ' />'
        );      
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_windSpeed_callback()
    {    
        /*
        $data = file_get_contents(plugin_dir_url( __FILE__ ) . 'city.list.min.json');
        $json = json_decode($data);
        */
        $checked = !empty($this->options['WindSpeed']) ? 'checked="checked"' : '';
        printf(
            '<input type="checkbox" id="windSpeed" name="widget_options[WindSpeed]" value="1"' . $checked . ' />'
        );      
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_humidity_callback()
    {    
        /*
        $data = file_get_contents(plugin_dir_url( __FILE__ ) . 'city.list.min.json');
        $json = json_decode($data);
        */
        $checked = !empty($this->options['Humidity']) ? 'checked="checked"' : '';
        printf(
            '<input type="checkbox" id="humidity" name="widget_options[Humidity]" value="1"' . $checked . ' />'
        );      
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_locText_callback()
    {
        $selectedOption = !empty($this->options['LocText']) ? $this->options['LocText'] : 'default';
        if($selectedOption == 'light') {$selectLight = 'selected'; $selectDark = ''; $selectDefault = '';};
        if($selectedOption == 'dark') {$selectLight = ''; $selectDark = 'selected'; $selectDefault = '';};
        if($selectedOption == 'default') {$selectLight = ''; $selectDark = ''; $selectDefault = 'selected';};

        printf(
            '<select id="locText" name="widget_options[LocText]">
                <option value="light"' . $selectLight . '>Light</option>
                <option value="dark"' . $selectDark . '>Dark</option>
                <option value=""' . $selectDefault . '>Default</option>
            </select>'
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function  wjnm_tempText_callback()
    {
        $selectedOption = !empty($this->options['TempText']) ? $this->options['TempText'] : 'default';
        if($selectedOption == 'light') {$selectLight = 'selected'; $selectDark = ''; $selectDefault = '';};
        if($selectedOption == 'dark') {$selectLight = ''; $selectDark = 'selected'; $selectDefault = '';};
        if($selectedOption == 'default') {$selectLight = ''; $selectDark = ''; $selectDefault = 'selected';};

        printf(
            '<select id="tempText" name="widget_options[TempText] value="">
                <option value="light"' . $selectLight . '>Light</option>
                <option value="dark"' . $selectDark . '>Dark</option>
                <option value=""' . $selectDefault . '>Default</option>
            </select>'
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wjnm_infoText_callback()
    {
        $selectedOption = !empty($this->options['InfoText']) ? $this->options['InfoText'] : 'default';
        if($selectedOption == 'light') {$selectLight = 'selected'; $selectDark = ''; $selectDefault = '';};
        if($selectedOption == 'dark') {$selectLight = ''; $selectDark = 'selected'; $selectDefault = '';};
        if($selectedOption == 'default') {$selectLight = ''; $selectDark = ''; $selectDefault = 'selected';};

        printf(
            '<select id="infoText" name="widget_options[InfoText]">
                <option value="light"' . $selectLight . '>Light</option>
                <option value="dark"' . $selectDark . '>Dark</option>
                <option value=""' . $selectDefault . '>Default</option>
            </select>',
            isset( $this->options['InfoText'] ) ? esc_attr( $this->options['InfoText']) : 'light'
        );
    }

}

if( is_admin() )
    $wjnm_settings_page = new jnmweatherSettings();