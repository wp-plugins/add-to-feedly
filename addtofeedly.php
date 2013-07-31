<?php
/*
Plugin Name: Add to feedly
Plugin URI: http://wordpress.org/plugins/add-to-feedly/
Description: This plugin provides a widget to Display a beautiful "Follow on Feedly" banner which will make easier for users adding RSS feed url to Feedly reader. English and Spanish. Este plugin habilita un widget que muestra un banner "Sigueme en Feedly" que hara mas facil para los usuarios agregar la url de las feeds RSS al lector Feedly. Ingles y Castellano.
Version: 1.0
Author: David Merinas
Author URI: http://www.davidmerinas.com
*/

define(ADD_TO_FEEDLY_WIDGET_ID, "widget_ADD_TO_FEEDLY");

function ADD_TO_FEEDLY_showimage($feeds='http://feeds.feedburner.com/davidmerinas',$lang="es"){
		$path=get_bloginfo('url')."/wp-content/plugins/addtofeedly/";
		$url="http://cloud.feedly.com/#subscription%2Ffeed%2F".urlencode($feeds);
		echo('<a id="addtofeedly" href="'.$url.'" title="'.__("Follow on Feedly").'"><img src="'.$path.'images/addtofeedly_'.$lang.'.png" alt="'.__("Follow on Feedly").'"/></a>');
}

function widget_ADD_TO_FEEDLY_control() {
	$options = get_option(ADD_TO_FEEDLY_WIDGET_ID);
	if (!is_array($options)) {
		$options = array();
	}
	$widget_data = $_POST[ADD_TO_FEEDLY_WIDGET_ID];
	if ($widget_data['submit']) {
		$options['ADD_TO_FEEDLY_feedurl'] = $widget_data['ADD_TO_FEEDLY_feedurl'];
		$options['ADD_TO_FEEDLY_lang'] = $widget_data['ADD_TO_FEEDLY_lang'];
		update_option(ADD_TO_FEEDLY_WIDGET_ID, $options);
	}
	// Datos para el formulario
	$ADD_TO_FEEDLY_feedurl = $options['ADD_TO_FEEDLY_feedurl'];
	$ADD_TO_FEEDLY_lang = $options['ADD_TO_FEEDLY_lang'];
	
	// Codigo HTML del formulario	
	?>
	<p>
	  <label for="<?php echo ADD_TO_FEEDLY_WIDGET_ID;?>-feedurl">
	    URL RSS (http://...)
	  </label>
	  <input class="widefat"
	    type="text"
	    name="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>[ADD_TO_FEEDLY_feedurl]"
	    id="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>-feedurl"
	    value="<?php echo $ADD_TO_FEEDLY_feedurl; ?>"/>
	</p>
	<p>
	  <label for="<?php echo ADD_TO_FEEDLY_WIDGET_ID;?>-lang">
	    Language - Idioma
	  </label>
	  <select class="widefat"
	    type="text"
	    name="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>[ADD_TO_FEEDLY_lang]"
	    id="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>-lang">
		<option value="en" <?php echo($ADD_TO_FEEDLY_lang=="en"?'selected="selected"':'');?>>English</option>
		<option value="es" <?php echo($ADD_TO_FEEDLY_lang=="es"?'selected="selected"':'');?>>Espa&ntilde;ol</option>
	    </select>
	</p>
	<input type="hidden"
	  name="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>[submit]"
	  value="1"/>
	<?php
}

// WIDGET
function widget_ADD_TO_FEEDLY($args) {
	extract($args, EXTR_SKIP);
	$options = get_option(ADD_TO_FEEDLY_WIDGET_ID);
	// Query the next scheduled post
	$ADD_TO_FEEDLY_feedurl = $options["ADD_TO_FEEDLY_feedurl"];
	$ADD_TO_FEEDLY_lang = $options["ADD_TO_FEEDLY_lang"];
	echo $before_widget;
	ADD_TO_FEEDLY_showimage($ADD_TO_FEEDLY_feedurl,$ADD_TO_FEEDLY_lang);
	echo $after_widget;
}

function widget_ADD_TO_FEEDLY_init() {
	wp_register_sidebar_widget(ADD_TO_FEEDLY_WIDGET_ID,
			__('Add to Feedly'), 'widget_ADD_TO_FEEDLY');
	wp_register_widget_control(ADD_TO_FEEDLY_WIDGET_ID,
			__('ADD_TO_FEEDLY'), 'widget_ADD_TO_FEEDLY_control');
}

function prefix_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}

// Registrar el widget en WordPress
add_action('wp_enqueue_scripts', 'prefix_add_my_stylesheet');
add_action("plugins_loaded", "widget_ADD_TO_FEEDLY_init");

?>