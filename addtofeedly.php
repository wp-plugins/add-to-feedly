<?php
/*
Plugin Name: Add to feedly
Plugin URI: http://wordpress.org/plugins/add-to-feedly/
Description: Feedly users can subscribe your RSS feed just by clicking the banner "Follow on Feedly" or the floating button that this plugin provides. Banner available in English, French or Spanish. 
Version: 1.2.6
Author: davidmerinas
Author URI: http://www.davidmerinas.com
*/

define('ADD_TO_FEEDLY_WIDGET_ID', "widget_ADD_TO_FEEDLY");

function ADD_TO_FEEDLY_create_menu() {

	//create new top-level menu
	add_menu_page('Add to Feedly Plugin', 'Add to Feedly', 'administrator', __FILE__, 'ADD_TO_FEEDLY_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'ADD_TO_FEEDLY_register_mysettings' );
}

function ADD_TO_FEEDLY_register_mysettings() {
	//register our settings
	register_setting( 'ADD_TO_FEEDLY-settings-group', 'ADD_TO_FEEDLY_active' );
	register_setting( 'ADD_TO_FEEDLY-settings-group', 'ADD_TO_FEEDLY_feed_url' );
	register_setting( 'ADD_TO_FEEDLY-settings-group', 'ADD_TO_FEEDLY_customtext' );
	register_setting( 'ADD_TO_FEEDLY-settings-group', 'ADD_TO_FEEDLY_position' );
	register_setting( 'ADD_TO_FEEDLY-settings-group', 'ADD_TO_FEEDLY_size' );
}

// Add settings link on plugin page
function addtofeedly_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=add-to-feedly/addtofeedly.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 

function ADD_TO_FEEDLY_settings_page() {
?>
<div class="wrap">
<h2>Add to Feedly</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'ADD_TO_FEEDLY-settings-group' ); ?>
    <?php do_settings_sections( 'ADD_TO_FEEDLY-settings-group' ); ?>
    <table class="form-table">
		<tr style="width:420px" valign="top">
			<th scope="row"><?php _e('Active','addtofeedly');?> Floating Add to Feedly</th>
			<td><input type="checkbox" name="ADD_TO_FEEDLY_active" <?php echo get_option('ADD_TO_FEEDLY_active')?'checked="checked"':''; ?>/></td>
        </tr>
		
        <tr style="width:420px" valign="top">
        <th scope="row">Feed URL (http://...)</th>
        <td><input style="width:320px" type="text" name="ADD_TO_FEEDLY_feed_url" value="<?php echo get_option('ADD_TO_FEEDLY_feed_url'); ?>" /></td>
        </tr>
         
		 <tr style="width:420px" valign="top">
        <th scope="row"><?php _e('Size','addtofeedly');?></th>
        <td>
			<select style="width:120px" name="ADD_TO_FEEDLY_size">
				<option value="big" <?php echo(get_option('ADD_TO_FEEDLY_size')=="big"?'selected="selected"':'')?>><?php _e('Big','addtofeedly');?></option>
				<option value="medium" <?php echo(get_option('ADD_TO_FEEDLY_size')=="medium"?'selected="selected"':'')?>><?php _e('Medium','addtofeedly');?></option>
				<option value="small" <?php echo(get_option('ADD_TO_FEEDLY_size')=="small"?'selected="selected"':'')?>><?php _e('Small','addtofeedly');?></option>
			</select>
		</td>
        </tr>
		
        <tr style="width:420px" valign="top">
        <th scope="row"><?php _e('Position','addtofeedly');?></th>
        <td>
			<select style="width:120px" name="ADD_TO_FEEDLY_position">
				<option value="left" <?php echo(get_option('ADD_TO_FEEDLY_position')=="left"?'selected="selected"':'')?>><?php _e('Left','addtofeedly');?></option>
				<option value="right" <?php echo(get_option('ADD_TO_FEEDLY_position')=="right"?'selected="selected"':'')?>><?php _e('Right','addtofeedly');?></option>
			</select>
		</td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php
}

function ADD_TO_FEEDLY_showimage($feeds='http://feeds.feedburner.com/davidmerinas',$lang="es",$custom=""){
		$path=get_bloginfo('url')."/wp-content/plugins/".basename( dirname( __FILE__ ) )."/";
		$url="http://cloud.feedly.com/#subscription%2Ffeed%2F".urlencode($feeds);
		if($custom!="")
		{
			echo('<a  href="'.$url.'" title="'.$custom.' Feedly" target="_blank"><div  id="addtofeedly"><img src="'.$path.'images/addtofeedly_master.png" alt="'.$custom.' Feedly"/><p class="customtext">'.$custom.'</p></div></a>');
		}
		else
		{
			$image='addtofeedly_'.$lang;
			echo('<a id="addtofeedly" href="'.$url.'" title="'.__("Follow on","addtofeedly").' Feedly" target="_blank"><img src="'.$path.'images/'.$image.'.png" alt="'.__("Follow on","addtofeedly").' Feedly"/></a>');
		}
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
		$options['ADD_TO_FEEDLY_customtext'] = $widget_data['ADD_TO_FEEDLY_customtext'];
		update_option(ADD_TO_FEEDLY_WIDGET_ID, $options);
	}
	// Datos para el formulario
	$ADD_TO_FEEDLY_feedurl = $options['ADD_TO_FEEDLY_feedurl'];
	$ADD_TO_FEEDLY_lang = $options['ADD_TO_FEEDLY_lang'];
	$ADD_TO_FEEDLY_customtext = $options['ADD_TO_FEEDLY_customtext'];
	
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
	    <?php _e('Language','addtofeedly');?>
	  </label>
	  <select class="widefat"
	    type="text"
	    name="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>[ADD_TO_FEEDLY_lang]"
	    id="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>-lang">
		<option value="en" <?php echo($ADD_TO_FEEDLY_lang=="en"?'selected="selected"':'');?>>English</option>
		<option value="es" <?php echo($ADD_TO_FEEDLY_lang=="es"?'selected="selected"':'');?>>Espa&ntilde;ol</option>
		<option value="fr" <?php echo($ADD_TO_FEEDLY_lang=="fr"?'selected="selected"':'');?>>Fran&ccedil;ais</option>
	    </select>
	</p>
	<p> 
		 <label for="<?php echo ADD_TO_FEEDLY_WIDGET_ID;?>-customtext">
			<?php _e('Custom text','addtofeedly');?> (<?php _e('Leave blank for default','addtofeedly');?>)
		  </label>
		  <input class="widefat"
			type="text"
			name="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>[ADD_TO_FEEDLY_customtext]"
			id="<?php echo ADD_TO_FEEDLY_WIDGET_ID; ?>-customtext"
			value="<?php echo $ADD_TO_FEEDLY_customtext; ?>"/>
	
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
	$ADD_TO_FEEDLY_customtext = isset($options["ADD_TO_FEEDLY_customtext"])?$options["ADD_TO_FEEDLY_customtext"]:"";
	echo $before_widget;
	ADD_TO_FEEDLY_showimage($ADD_TO_FEEDLY_feedurl,$ADD_TO_FEEDLY_lang,$ADD_TO_FEEDLY_customtext);
	echo $after_widget;
}

function widget_ADD_TO_FEEDLY_init() {
	wp_register_sidebar_widget(ADD_TO_FEEDLY_WIDGET_ID,
			__('Add to Feedly'), 'widget_ADD_TO_FEEDLY');
	wp_register_widget_control(ADD_TO_FEEDLY_WIDGET_ID,
			__('ADD_TO_FEEDLY'), 'widget_ADD_TO_FEEDLY_control');
}

function addtofeedly_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'addtofeedly-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'addtofeedly-style' );
}

function addtofeedly_javascript() {
    // Respects SSL, scripts.js is relative to the current file
	wp_register_script( 'addtofeedly-jquery-script', plugins_url( '/js/jquery-1.10.2.min.js', __FILE__ ) ); 
	wp_enqueue_script( 'addtofeedly-jquery-script' ); 	
	wp_register_script( 'addtofeedly-script', plugins_url( '/js/scripts.js', __FILE__ ) );  
    wp_enqueue_script( 'addtofeedly-script' ); 
}

function ADD_TO_FEEDLY_init(){
	if(get_option('ADD_TO_FEEDLY_active'))
	{
		$path=get_bloginfo('url')."/wp-content/plugins/".basename( dirname( __FILE__ ) )."/";
		echo('<div id="float_feed_box" class="'.get_option('ADD_TO_FEEDLY_position').' floatfeed_'.get_option('ADD_TO_FEEDLY_size').'"><a href="http://cloud.feedly.com/#subscription%2Ffeed%2F'.urlencode(get_option('ADD_TO_FEEDLY_feed_url')).'" title="'.__('Follow on','addtofeedly').' Feedly" target="_blank"><img src="'.$path.'/images/feedly-follow-'.get_option('ADD_TO_FEEDLY_size').'.png" alt="'.__('Follow on','addtofeedly').' Feedly"/></a></div>');
	}
}

// Registrar el widget en WordPress
load_plugin_textdomain('addtofeedly', false, basename( dirname( __FILE__ ) ) . '/languages' );
add_action('wp_enqueue_scripts', 'addtofeedly_stylesheet');
add_action('wp_enqueue_scripts', 'addtofeedly_javascript');

if ( is_admin() ){
	add_action('admin_menu', 'ADD_TO_FEEDLY_create_menu');
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'addtofeedly_settings_link' );
}
add_action("plugins_loaded", "widget_ADD_TO_FEEDLY_init");
//$queried_object = get_queried_object();
if ( !is_admin() && !is_feed()){
	add_action("wp_footer", "ADD_TO_FEEDLY_init");
}
?>