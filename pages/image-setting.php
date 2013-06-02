<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php echo WP_pisg_TITLE; ?></h2>
	<h3>Widget setting</h3>
    <?php
	$pisg_title = get_option('pisg_title');
	$pisg_maxsquare = get_option('pisg_maxsquare');
	$pisg_duration = get_option('pisg_duration');
	$pisg_slidespeed = get_option('pisg_slidespeed');
	$pisg_random = get_option('pisg_random');
	$pisg_type = get_option('pisg_type');
	
	if (@$_POST['pisg_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('pisg_form_setting');
			
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
		
		?>
		<div class="updated fade">
			<p><strong>Details successfully updated.</strong></p>
		</div>
		<?php
	}
	?>
	<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/superb-slideshow-gallery/pages/setting.js"></script>
    <form name="pisg_form" method="post" action="">
      
	  <label for="tag-title">Enter widget title</label>
      <input name="pisg_title" id="pisg_title" type="text" value="<?php echo $pisg_title; ?>" size="80" />
      <p>Enter widget title, Only for widget.</p>
      
	  <label for="tag-width">Maxsquare (Only number)</label>
      <input name="pisg_maxsquare" id="pisg_maxsquare" type="text" value="<?php echo $pisg_maxsquare; ?>" />
      <p>(only number). (Example: 15)</p>
      
	  <label for="tag-height">Duration</label>
      <input name="pisg_duration" id="pisg_duration" type="text" value="<?php echo $pisg_duration; ?>" />
      <p>(only number). (Example: 1)</p>
	  
	  <label for="tag-height">Slidespeed</label>
      <input name="pisg_slidespeed" id="pisg_slidespeed" type="text" value="<?php echo $pisg_slidespeed; ?>" />
      <p>Only Number / Pause time of the slideshow in milliseconds.</p>
	    
	  <label for="tag-height">Random</label>
      <input name="pisg_random" id="pisg_random" type="text" value="<?php echo $pisg_random; ?>" />
      <p>Enter : YES (or) NO</p>
      
	  <label for="tag-height">Select your gallery group (Gallery  Type)</label>
	  <select name="pisg_type" id="pisg_type">
	 	<?php
		$sSql = "SELECT distinct(pisg_type) as pisg_type FROM `".WP_pisg_TABLE."` order by pisg_type, pisg_order";
		$myDistinctData = array();
		$arrDistinctDatas = array();
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		$i = 0;
		foreach ($myDistinctData as $DistinctData)
		{
			$arrDistinctData[$i]["pisg_type"] = strtoupper($DistinctData['pisg_type']);
			$i = $i+1;
		}
		foreach ($arrDistinctData as $arrDistinct)
		{
			if(strtoupper($pisg_type) == strtoupper($arrDistinct["pisg_type"]) ) 
			{ 
				$selected = "selected='selected'"; 
			}
			?>
			<option value='<?php echo $arrDistinct["pisg_type"]; ?>' <?php echo $selected; ?>><?php echo strtoupper($arrDistinct["pisg_type"]); ?></option>
			<?php
			$selected = "";
		}
		?>
      </select>
      <p>This field is to group the images. Select your group name to fetch the images for widget.</p>
      <br />
	  <input name="pisg_submit" id="pisg_submit" class="button-primary" value="Submit" type="submit" />
	  <input name="publish" lang="publish" class="button-primary" onclick="pisg_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="pisg_help()" value="Help" type="button" />
	  <?php wp_nonce_field('pisg_form_setting'); ?>
    </form>
  </div>
  <br /><p class="description"><?php echo WP_pisg_LINK; ?></p>
</div>
