<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include 'jnmsol-weather-settings.php';

/**
 * Plugin Name: Weather-JNM
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
 * along with Weather-JNM. If not, see https://www.gnu.org/licenses/
 */

/**
 * Include CSS file for Plugin.
 */
function wjnm_callback_css() {
    wp_enqueue_style( 'weatherjnmcss', plugin_dir_url( __FILE__ ) . 'weather-jnm.css' );    
}
add_action('wp_enqueue_scripts', 'wjnm_callback_css');

 // Register and load the widget
function wjnm_load_widget() {
    register_widget( 'wjnm_widget' );    
}
add_action( 'widgets_init', 'wjnm_load_widget' );
 
// Creating the widget 
class wjnm_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID of your widget
'wjnm_widget', 
 
// Widget name will appear in UI
__('Weather-JNM', 'jnmsolutions.co.za/weather'),
 
// Widget description
array( 'description' => __( 'Weather Widget', 'jnmsolutions.co.za/weather' ), ) 
);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {

$options = get_option('widget_options');

$apiKey = !empty($options['wjnm_APIKey']) ? $options['wjnm_APIKey'] : '';
$City = !empty($options['City']) ? $options['City'] : 'Durban';
$Country = !empty($options['Country']) ? $options['Country'] : 'South Africa';
$Unit = !empty($options['Unit']) ? $options['Unit'] : 'metric';
$WeatherDesc = !empty($options['WeatherDesc']) ? 'block' : 'none';
$RealFeel = !empty($options['RealFeel']) ? 'block' : 'none';
$WindSpeed = !empty($options['WindSpeed']) ? 'block' : 'none';
$Humidity = !empty($options['Humidity']) ? 'block' : 'none';
$LocTextColor = !empty($options['LocText']) ? $options['LocText'] : '';
$TempTextColor = !empty($options['TempText']) ? $options['TempText'] : '';
$InfoTextColor = !empty($options['InfoText']) ? $options['InfoText'] : '';

$city = strtolower(sanitize_text_field($City)) . ",";
$country = strtolower(sanitize_text_field($Country));
$unit = strtolower(sanitize_text_field($Unit));

$symbol;
if($unit == 'metric') {
    $symbol = '°C';
}
else{
    $symbol = '°F';
};

$OpenWeatherApiUrl = "https://api.openweathermap.org/data/2.5/weather?q=" . $city . $country . "&lang=en&units=" . $unit . "&APPID=" . $apiKey;



$result = wp_remote_retrieve_body(wp_remote_get($OpenWeatherApiUrl, array(
    'sslverify' => false,
     'headers' => array(
         'Authorization: '. $apiKey,'Accept: application/vnd.api+json'
        )
    )
)
);


$data = json_decode($result);
$continue = $result[2] . $result[3] . $result[4];
if($continue == 'coo') {

$place = $City . ', ' . $Country;
$temp = round($data->main->temp);
$cloud = $data->weather[0]->description;
$icon = $data->weather[0]->icon;
$feel = round($data->main->feels_like, 1) . $symbol;
$wind = round($data->wind->speed, 1) . 'm/s';
$humidity = round($data->main->humidity) . '%';

$instanceTitle = !empty($instance['title']) ? $instance['title'] : '';
$title = apply_filters( 'widget_title', $instanceTitle );
 
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
// This is where you run the code and display the output
?>
<div class="weatherJNM-wrap">
<div id="jnmTest" class="jnmTest"><?php ?></div>

<div id="jnmPlace" class="jnmPlace <?php echo $LocTextColor; ?>"><?php echo __($place);?></div>
<div id="jnmTemp" class="jnmTemp <?php echo $TempTextColor; ?>"><?php echo __($temp . $symbol);?></div>
<div id="jnmWeatherImgWrap" class="jnmWeatherImgWrap"><img id="jnmWeatherImg" class="jnmWeatherImg" alt="<?php $cloud ?>" src="<?php echo plugin_dir_url( __FILE__ ) . 'JNMweatherIcons/' . $icon . '.png'; ?>" width="20"></div>


<div id="jnmWeatherExtra" class=jnmWeatherExtra>
<div id="jnmCloudLabel" class="jnmCloudLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $WeatherDesc ?>">Description</div>
<div id="jnmCloud" class="jnmCloud <?php echo $InfoTextColor; ?>" style="display:<?php echo $WeatherDesc ?>"><?php echo __(ucwords($cloud, " "));?></div>

<div id="jnmFeelLabel" class="jnmFeelLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $RealFeel ?>">Real Feel</div>
<div id="jnmFeel" class="jnmFeel <?php echo $InfoTextColor; ?>" style="display:<?php echo $RealFeel ?>"><?php echo __($feel);?></div>

<div id="jnmWindLabel" class="jnmWindLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $WindSpeed ?>">Wind Speed</div>
<div id="jnmWind" class="jnmWind <?php echo $InfoTextColor; ?>" style="display:<?php echo $WindSpeed ?>"><?php echo __($wind);?></div>

<div id="jnmHumidLabel" class="jnmHumidLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $Humidity ?>">Humidity</div>
<div id="jnmHumid" class="jnmHumid <?php echo $InfoTextColor; ?>" style="display:<?php echo $Humidity ?>"><?php echo __($humidity);?></div>
</div>
</div>
<div style="font-size:12px;opacity:0.3;text-align:center;" class="weather-jnm <?php echo $InfoTextColor; ?>">
<br />
Copyright © <span id="weather-jnm-copyright">2020</span>, <a style="color: #6d6d6d" href="https://jnmsolutions.co.za">
JNM Solutions(Pty) Ltd.</a>
</div>
    <script type="text/javascript">
    var copyYear = document.getElementById("weather-jnm-copyright");
    var date = new Date();
    var year = date.getFullYear();

    copyYear.innerHTML = year.toString();


</script>
<?php
}
else {
    ?>
    <div id="errorMsg" style="text-align:center;" class="<?php echo $LocTextColor; ?>">Check Weather-JNM API Key</div>
    <div class="weatherJNM-wrap">
    <div id="jnmTest" class="jnmTest"><?php ?></div>    
    <div id="jnmPlace" class="jnmPlace <?php echo $LocTextColor; ?>">Durban, South Africa</div>
    <div id="jnmTemp" class="jnmTemp <?php echo $TempTextColor; ?>">25°C</div>
    <div id="jnmWeatherImgWrap" class="jnmWeatherImgWrap"><img id="jnmWeatherImg" class="jnmWeatherImg" alt="" src="<?php echo plugin_dir_url( __FILE__ ) . 'JNMweatherIcons/02d.png'; ?>" width="20"></div>
    
    
    <div id="jnmWeatherExtra" class=jnmWeatherExtra>
    <div id="jnmCloudLabel" class="jnmCloudLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $WeatherDesc ?>">Description</div>
    <div id="jnmCloud" class="jnmCloud <?php echo $InfoTextColor; ?>" style="display:<?php echo $WeatherDesc ?>"></div>
    
    <div id="jnmFeelLabel" class="jnmFeelLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $RealFeel ?>">Real Feel</div>
    <div id="jnmFeel" class="jnmFeel <?php echo $InfoTextColor; ?>" style="display:<?php echo $RealFeel ?>"></div>
    
    <div id="jnmWindLabel" class="jnmWindLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $WindSpeed ?>">Wind Speed</div>
    <div id="jnmWind" class="jnmWind <?php echo $InfoTextColor; ?>" style="display:<?php echo $WindSpeed ?>"></div>
    
    <div id="jnmHumidLabel" class="jnmHumidLabel <?php echo $InfoTextColor; ?>" style="display:<?php echo $Humidity ?>">Humidity</div>
    <div id="jnmHumid" class="jnmHumid <?php echo $InfoTextColor; ?>" style="display:<?php echo $Humidity ?>"></div>
    </div>
    </div>
    <div id="jnm-copyright" style="font-size:12px;opacity:0.3;text-align:center;" class="<?php echo $InfoTextColor; ?>">
    <br />
    Copyright © <span id="weather-jnm-copyright">2020</span>, <a style="color: #6d6d6d" href="https://jnmsolutions.co.za">
    JNM Solutions(Pty) Ltd.</a>
    </div>
        <script type="text/javascript">
        var copyYear = document.getElementById("weather-jnm-copyright");
        var date = new Date();
        var year = date.getFullYear();
    
        copyYear.innerHTML = year.toString();
    
    
    </script>
    <?php
};



echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Weather', 'wjnm_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wjnm_widget ends here

// Display the widget
function wjnm_show_widget() {
    the_widget( 'wjnm_widget' );
};

add_shortcode('weather-jnm-free', 'wjnm_show_widget');