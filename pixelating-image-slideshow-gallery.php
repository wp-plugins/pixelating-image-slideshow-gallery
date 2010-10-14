<?php

/*
Plugin Name: Pixelating image slideshow gallery
Plugin URI: http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/
Description: This is your normal hyperlinked image slideshow, but in IE the added images are "pixelated" into view. And its good cross browser script.  
Author: Gopi.R
Version: 1.0
Author URI: http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/
Donate link: http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/
*/

/*
##############################################################################################################################
###### Project   : Pixelating image slideshow gallery  																	######
###### File Name : pixelating-image-slideshow-gallery.php                   											######
###### Purpose   : This page is plugin main page.  																		######
###### Created   : 13-10-10                 																			######
###### Modified  : 13-10-10                 																			######
###### Author    : Gopi.R (http://www.gopiplus.com/work/)                        										######
###### Link      : http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/  						######
##############################################################################################################################
*/

global $wpdb, $wp_version;
define("WP_pisg_TABLE", $wpdb->prefix . "pisg_superb_gallery");

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
			$pisg_images = $pisg_images .'"'.$data->pisg_path.'",';
			$pisg_links = $pisg_links .'"'.$data->pisg_link.'",';
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


add_filter('the_content','pisg_show_filter');

function pisg_show_filter($content)
{
	return 	preg_replace_callback('/\[pixelating-slideshow=(.*?)\]/sim','pisg_show_filter_Callback',$content);
}

function pisg_show_filter_Callback($matches) 
{
	
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
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/250x200/250x200_1.jpg','http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/','_blank','Welcome to gopiplus.com website.','1', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_pisg_TABLE . "` (`pisg_path`, `pisg_link`, `pisg_target` , `pisg_title` , `pisg_order` , `pisg_status` , `pisg_type` , `pisg_date`)"; 
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/250x200/250x200_2.jpg','http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/','_blank','In this domain you can find some usefull plugins.','2', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_pisg_TABLE . "` (`pisg_path`, `pisg_link`, `pisg_target` , `pisg_title` , `pisg_order` , `pisg_status` , `pisg_type` , `pisg_date`)"; 
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/250x200/250x200_3.jpg','http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/','_blank','Wordpress, joomla, plugins are available in this website.','3', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		$sSql = "INSERT INTO `". WP_pisg_TABLE . "` (`pisg_path`, `pisg_link`, `pisg_target` , `pisg_title` , `pisg_order` , `pisg_status` , `pisg_type` , `pisg_date`)"; 
		$sSql = $sSql . "VALUES ('http://www.gopiplus.com/work/wp-content/uploads/pluginimages/250x200/250x200_4.jpg','http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/','_blank','Please post your comments and feedback about this site.','4', 'YES', 'widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
	}
	add_option('pisg_title', "Pixelating gallery");
	add_option('pisg_maxsquare', "15");
	add_option('pisg_duration', "1");
	add_option('pisg_slidespeed', "3000");
	add_option('pisg_random', "YES");
	add_option('pisg_type', "widget");
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
	echo "<div class='wrap'>";
	echo "<h2>"; 
	echo wp_specialchars( "Pixelating image slideshow gallery" ) ;
	echo "</h2>";
	$pisg_title = get_option('pisg_title');
	$pisg_maxsquare = get_option('pisg_maxsquare');
	$pisg_duration = get_option('pisg_duration');
	$pisg_slidespeed = get_option('pisg_slidespeed');
	$pisg_random = get_option('pisg_random');
	$pisg_type = get_option('pisg_type');
	if ($_POST['pisg_submit']) 
	{
		$pisg_title = stripslashes($_POST['pisg_title']);
		$pisg_maxsquare = stripslashes($_POST['pisg_maxsquare']);
		$pisg_duration = stripslashes($_POST['pisg_duration']);
		$pisg_slidespeed = stripslashes($_POST['pisg_slidespeed']);
		$pisg_random = stripslashes($_POST['pisg_random']);
		$pisg_type = stripslashes($_POST['pisg_type']);
		
		update_option('pisg_title', $pisg_title );
		update_option('pisg_maxsquare', $pisg_maxsquare );
		update_option('pisg_duration', $pisg_duration );
		update_option('pisg_slidespeed', $pisg_slidespeed );
		update_option('pisg_random', $pisg_random );
		update_option('pisg_type', $pisg_type );
	}
	?><form name="form_woo" method="post" action="">
	<?php
	echo '<p>Title:<br><input  style="width: 450px;" maxlength="200" type="text" value="';
	echo $pisg_title . '" name="pisg_title" id="pisg_title" /> Widget title.</p>';
	
	echo '<p>Maxsquare:<br><input  style="width: 100px;" maxlength="200" type="text" value="';
	echo $pisg_maxsquare . '" name="pisg_maxsquare" id="pisg_maxsquare" /> (only number).</p>';
	
	echo '<p>Duration:<br><input  style="width: 100px;" maxlength="200" type="text" value="';
	echo $pisg_duration . '" name="pisg_duration" id="pisg_duration" /> only number.</p>';
	
	echo '<p>Slidespeed:<br><input  style="width: 100px;" maxlength="4" type="text" value="';
	echo $pisg_slidespeed . '" name="pisg_slidespeed" id="pisg_slidespeed" /> Only Number / Pause between content change (millisec).</p>';
	
	echo '<p>Random :<br><input  style="width: 100px;" type="text" value="';
	echo $pisg_random . '" name="pisg_random" id="pisg_random" /> (YES/NO)</p>';
	
	echo '<p>Type:<br><input  style="width: 150px;" type="text" value="';
	echo $pisg_type . '" name="pisg_type" id="pisg_type" /> This field is to group the images.</p>';
	
	echo '<input name="pisg_submit" id="pisg_submit" class="button-primary" value="Submit" type="submit" />';
	?>
	</form>
	<table width="100%">
		<tr>
		  <td align="right"><input name="text_management" lang="text_management" class="button-primary" onClick="location.href='options-general.php?page=pixelating-image-slideshow-gallery/image-management.php'" value="Go to - Image Management" type="button" />
			<input name="setting_management" lang="setting_management" class="button-primary" onClick="location.href='options-general.php?page=pixelating-image-slideshow-gallery/pixelating-image-slideshow-gallery.php'" value="Go to - Gallery Setting" type="button" />
		  </td>
		</tr>
	  </table>
	<?php
	include_once("help.php");
	echo "</div>";
}

function pisg_control()
{
	echo '<p>Pixelating image slideshow gallery.<br><br> To change the setting goto "Pixelating image slideshow gallery" link under SETTING menu.';
	echo ' <a href="options-general.php?page=pixelating-image-slideshow-gallery/pixelating-image-slideshow-gallery.php">';
	echo 'click here</a></p>';
	?>
	<a target="_blank" href='http://gopi.coolpage.biz/demo/about/'>Click here</a> for more help.<br> 
	<?php
}

function pisg_widget_init() 
{
  	register_sidebar_widget(__('Pixelating image slideshow gallery'), 'pisg_widget');   
	
	if(function_exists('register_sidebar_widget')) 	
	{
		register_sidebar_widget('Pixelating image slideshow gallery', 'pisg_widget');
	}
	
	if(function_exists('register_widget_control')) 	
	{
		register_widget_control(array('Pixelating image slideshow gallery', 'widgets'), 'pisg_control');
	} 
}

function pisg_deactivation() 
{
}

function pisg_add_to_menu() 
{
	add_options_page('Pixelating image slideshow gallery', 'Pixelating image slideshow gallery', 7, __FILE__, 'pisg_admin_option' );
	add_options_page('Pixelating image slideshow gallery', '', 0, "pixelating-image-slideshow-gallery/image-management.php",'' );
}

add_action('admin_menu', 'pisg_add_to_menu');
add_action("plugins_loaded", "pisg_widget_init");
register_activation_hook(__FILE__, 'pisg_install');
register_deactivation_hook(__FILE__, 'pisg_deactivation');
add_action('init', 'pisg_widget_init');
?>
