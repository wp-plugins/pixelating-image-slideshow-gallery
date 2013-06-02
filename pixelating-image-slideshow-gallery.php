<?php
/*
Plugin Name: Pixelating image slideshow gallery
Plugin URI: http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/
Description: This is your normal hyperlinked image slideshow, but in IE the added images are "pixelated" into view. And its good cross browser script.  
Author: Gopi.R
Version: 6.0
Author URI: http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/
Donate link: http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("WP_pisg_TABLE", $wpdb->prefix . "pisg_superb_gallery");
define("WP_pisg_UNIQUE_NAME", "pisg");
define("WP_pisg_TITLE", "Pixelating image slideshow gallery");
define('WP_pisg_LINK', 'Check official website for more information <a target="_blank" href="http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery">click here</a>');
define('WP_pisg_FAV', 'http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery');

function pisg_show() 
{
	global $wpdb;
	
	$pisg_maxsquare = get_option('pisg_maxsquare');
	$pisg_duration = get_option('pisg_duration');
	$pisg_slidespeed = get_option('pisg_slidespeed');
	$pisg_random = get_option('pisg_random');
	$pisg_type = get_option('pisg_type');
	
	if(!is_numeric(@$pisg_maxsquare)) {	@$pisg_maxsquare = 15; } 
	if(!is_numeric(@$pisg_duration)) { @$pisg_duration = 1; }
	if(!is_numeric(@$pisg_slidespeed)) { @$pisg_slidespeed = 3000; }
	
	$sSql = "select pisg_path,pisg_link,pisg_target,pisg_title from ".WP_pisg_TABLE." where 1=1";
	$sSql = $sSql . " and pisg_type='".$pisg_type."'";
	if($pisg_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY pisg_order"; }
	$data = $wpdb->get_results($sSql);
	
	if ( ! empty($data) ) 
	{
		$i = 1;
		foreach ( $data as $data ) 
		{
			if($i == 1)
			{
				$first_image = $data->pisg_path;
			}
			$i = $i +1;
			$pisg_images = @$pisg_images .'"'.$data->pisg_path.'",';
			$pisg_links = @$pisg_links .'"'.$data->pisg_link.'",';
		}
	}	
	//echo $first_image;
	$pisg_images = substr($pisg_images,0,(strlen($pisg_images)-1));
	$pisg_links = substr($pisg_links,0,(strlen($pisg_links)-1));
	
	?>
	<script language="JavaScript1.1">
	<!--
	var slidespeed=<?php echo $pisg_slidespeed; ?>;
	
	var slideimages=new Array(<?php echo $pisg_images; ?>)
	var slidelinks=new Array(<?php echo $pisg_links; ?>)
	
	var imageholder=new Array()
	var ie55=window.createPopup
	for (i=0;i<slideimages.length;i++){
	imageholder[i]=new Image()
	imageholder[i].src=slideimages[i]
	}
	
	function gotoshow(){
	window.location=slidelinks[whichlink]
	}
	//-->
	</script>
	<a href="javascript:gotoshow()"><img src="<?php $first_image; ?>" name="slide" border=0 style="filter:progid:DXImageTransform.Microsoft.Pixelate(MaxSquare=<?php echo $pisg_maxsquare; ?>,Duration=<?php echo $pisg_duration; ?>)"></a>
	<script language="JavaScript1.1">
	<!--
	var whichlink=0
	var whichimage=0
	var pixeldelay=(ie55)? document.images.slide.filters[0].duration*1000 : 0
	function slideit(){
	if (!document.images) return
	if (ie55) document.images.slide.filters[0].apply()
	document.images.slide.src=imageholder[whichimage].src
	if (ie55) document.images.slide.filters[0].play()
	whichlink=whichimage
	whichimage=(whichimage<slideimages.length-1)? whichimage+1 : 0
	setTimeout("slideit()",slidespeed+pixeldelay)
	}
	slideit()
	
	//-->
	</script>
    <?php
}

add_shortcode( 'pixelating-image', 'pisg_shortcode' );

function pisg_shortcode( $atts ) 
{
	global $wpdb;
	//[pixelating-image type="widget"]
	
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	
	$pisg_type = $atts['type'];
	$pisg_maxsquare = get_option('pisg_maxsquare');
	$pisg_duration = get_option('pisg_duration');
	$pisg_slidespeed = get_option('pisg_slidespeed');
	$pisg_random = get_option('pisg_random');
	
	if(!is_numeric(@$pisg_maxsquare)) {	@$pisg_maxsquare = 15; } 
	if(!is_numeric(@$pisg_duration)) { @$pisg_duration = 1; }
	if(!is_numeric(@$pisg_slidespeed)) { @$pisg_slidespeed = 3000; }
	
	$sSql = "select pisg_path,pisg_link,pisg_target,pisg_title from ".WP_pisg_TABLE." where 1=1";
	$sSql = $sSql . " and pisg_type='".$pisg_type."'";
	if($pisg_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY pisg_order"; }
	$data = $wpdb->get_results($sSql);
	
	if ( ! empty($data) ) 
	{
		$i = 1;
		foreach ( $data as $data ) 
		{
			if($i == 1)
			{
				$first_image = $data->pisg_path;
			}
			$i = $i +1;
			$pisg_images = @$pisg_images .'"'.$data->pisg_path.'",';
			$pisg_links = @$pisg_links .'"'.$data->pisg_link.'",';
		}
	}	

	$pisg_images = substr($pisg_images,0,(strlen($pisg_images)-1));
	$pisg_links = substr($pisg_links,0,(strlen($pisg_links)-1));

	
	$pisg = '<script language="JavaScript1.1"> ';
	$pisg .= ' var slidespeed='.$pisg_slidespeed.'; ';
	$pisg .= ' var slideimages=new Array('.$pisg_images.');' ;
	$pisg .= ' var slidelinks=new Array('.$pisg_links.');' ;
	$pisg .= ' var imageholder=new Array();' ;
	$pisg .= ' var ie55=window.createPopup;' ;
	$pisg .= ' for (i=0;i<slideimages.length;i++){ ';
	$pisg .= ' imageholder[i]=new Image(); ';
	$pisg .= ' imageholder[i].src=slideimages[i]; ';
	$pisg .= '} ';
	$pisg .= 'function gotoshow(){ ';
	$pisg .= 'window.location=slidelinks[whichlink]; ';
	$pisg .= '} ';
	$pisg .= '</script> ';
	
	$pisg .= '<a href="javascript:gotoshow()"><img src="'.$first_image.'" name="slide" border=0 style="filter:progid:DXImageTransform.Microsoft.Pixelate(MaxSquare='.$pisg_maxsquare.',Duration='.$pisg_duration.')"></a>';
	$pisg .= '<script language="JavaScript1.1"> ';
	$pisg .= ' var whichlink=0; ';
	$pisg .= ' var whichimage=0; ';
	$pisg .= ' var pixeldelay=(ie55)? document.images.slide.filters[0].duration*1000 : 0 ; ';
	$pisg .= ' function slideit(){ ';
	$pisg .= 'if (!document.images) return ; ';
	$pisg .= 'if (ie55) document.images.slide.filters[0].apply(); ';
	$pisg .= 'document.images.slide.src=imageholder[whichimage].src; ';
	$pisg .= 'if (ie55) document.images.slide.filters[0].play(); ';
	$pisg .= 'whichlink=whichimage ; ';
	$pisg .= 'whichimage=(whichimage<slideimages.length-1)? whichimage+1 : 0;';
	$pisg .= 'setTimeout("slideit()",slidespeed+pixeldelay); ';
	$pisg .= '} ';
	$pisg .= 'slideit(); ';
	$pisg .= '</script> ';
	
	echo $pisg;
}

function pisg_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_pisg_TABLE . "'") != WP_pisg_TABLE) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". WP_pisg_TABLE . "` (";
		$sSql = $sSql . "`pisg_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`pisg_path` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`pisg_link` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`pisg_target` VARCHAR( 50 ) NOT NULL ,";
		$sSql = $sSql . "`pisg_title` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "`pisg_order` INT NOT NULL ,";
		$sSql = $sSql . "`pisg_status` VARCHAR( 10 ) NOT NULL ,";
		$sSql = $sSql . "`pisg_type` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`pisg_date` INT NOT NULL ,";
		$sSql = $sSql . "PRIMARY KEY ( `pisg_id` )";
		$sSql = $sSql . ")";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_pisg_TABLE . "` (`pisg_path`, `pisg_link`, `pisg_target` , `pisg_title` , `pisg_order` , `pisg_status` , `pisg_type` , `pisg_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/pixelating-image-slideshow-gallery/images/sing_1.jpg','#','_blank','','1', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_pisg_TABLE . "` (`pisg_path`, `pisg_link`, `pisg_target` , `pisg_title` , `pisg_order` , `pisg_status` , `pisg_type` , `pisg_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/pixelating-image-slideshow-gallery/images/sing_2.jpg','#','_blank','','2', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_pisg_TABLE . "` (`pisg_path`, `pisg_link`, `pisg_target` , `pisg_title` , `pisg_order` , `pisg_status` , `pisg_type` , `pisg_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/pixelating-image-slideshow-gallery/images/sing_3.jpg','#','_blank','','3', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_pisg_TABLE . "` (`pisg_path`, `pisg_link`, `pisg_target` , `pisg_title` , `pisg_order` , `pisg_status` , `pisg_type` , `pisg_date`)"; 
		$sSql = $sSql . "VALUES ('".get_option('siteurl')."/wp-content/plugins/pixelating-image-slideshow-gallery/images/sing_4.jpg','#','_blank','','4', 'YES', 'WIDGET', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	add_option('pisg_title', "Pixelating gallery");
	add_option('pisg_maxsquare', "15");
	add_option('pisg_duration', "1");
	add_option('pisg_slidespeed', "3000");
	add_option('pisg_random', "YES");
	add_option('pisg_type', "WIDGET");
}

function pisg_widget($args) 
{
	extract($args);
	if(get_option('pisg_title') <> "")
	{
		echo $before_widget . $before_title;
		echo get_option('pisg_title');
		echo $after_title;
	}
	pisg_show();
	if(get_option('pisg_title') <> "")
	{
		echo $after_widget;
	}
}

function pisg_admin_option() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/image-management-edit.php');
			break;
		case 'add':
			include('pages/image-management-add.php');
			break;
		case 'set':
			include('pages/image-setting.php');
			break;
		default:
			include('pages/image-management-show.php');
			break;
	}
}

function pisg_control()
{
	echo '<p>To change the setting goto <b>Pixelating image slideshow gallery</b> link under Settings menu.';
	echo ' <a href="options-general.php?page=pixelating-image-slideshow-gallery">click here</a></p>';
	echo WP_pisg_LINK;
}

function pisg_widget_init() 
{ 	
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('Pixelating image slideshow gallery', 'Pixelating image slideshow gallery', 'pisg_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('Pixelating image slideshow gallery', array('Pixelating image slideshow gallery', 'widgets'), 'pisg_control');
	} 
}

function pisg_deactivation() 
{
	// No action required.
}

function pisg_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page('Pixelating image slideshow gallery', 'Pixelating image slideshow gallery', 'manage_options', "pixelating-image-slideshow-gallery", 'pisg_admin_option' );
		//add_options_page('Pixelating image slideshow gallery', '', 'manage_options', "pixelating-image-slideshow-gallery/image-management.php",'' );
	}
}

add_action('admin_menu', 'pisg_add_to_menu');
add_action("plugins_loaded", "pisg_widget_init");
register_activation_hook(__FILE__, 'pisg_install');
register_deactivation_hook(__FILE__, 'pisg_deactivation');
add_action('init', 'pisg_widget_init');
?>