<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_pisg_TABLE."
	WHERE `pisg_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'pixelating-image'); ?></strong></p></div><?php
}
else
{
	$pisg_errors = array();
	$pisg_success = '';
	$pisg_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_pisg_TABLE."`
		WHERE `pisg_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'pisg_path' => $data['pisg_path'],
		'pisg_link' => $data['pisg_link'],
		'pisg_target' => $data['pisg_target'],
		'pisg_title' => $data['pisg_title'],
		'pisg_order' => $data['pisg_order'],
		'pisg_status' => $data['pisg_status'],
		'pisg_type' => $data['pisg_type']
	);
}
// Form submitted, check the data
if (isset($_POST['pisg_form_submit']) && $_POST['pisg_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('pisg_form_edit');
	
	$form['pisg_path'] = isset($_POST['pisg_path']) ? $_POST['pisg_path'] : '';
	if ($form['pisg_path'] == '')
	{
		$pisg_errors[] = __('Please enter the image path.', 'pixelating-image');
		$pisg_error_found = TRUE;
	}

	$form['pisg_link'] = isset($_POST['pisg_link']) ? $_POST['pisg_link'] : '';
	if ($form['pisg_link'] == '')
	{
		$pisg_errors[] = __('Please enter the target link.', 'pixelating-image');
		$pisg_error_found = TRUE;
	}
	
	$form['pisg_target'] = isset($_POST['pisg_target']) ? $_POST['pisg_target'] : '';
	$form['pisg_title'] = ""; //isset($_POST['pisg_title']) ? $_POST['pisg_title'] : '';
	$form['pisg_order'] = isset($_POST['pisg_order']) ? $_POST['pisg_order'] : '';
	$form['pisg_status'] = isset($_POST['pisg_status']) ? $_POST['pisg_status'] : '';
	$form['pisg_type'] = isset($_POST['pisg_type']) ? $_POST['pisg_type'] : '';

	//	No errors found, we can add this Group to the table
	if ($pisg_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_pisg_TABLE."`
				SET `pisg_path` = %s,
				`pisg_link` = %s,
				`pisg_target` = %s,
				`pisg_title` = %s,
				`pisg_order` = %d,
				`pisg_status` = %s,
				`pisg_type` = %s
				WHERE pisg_id = %d
				LIMIT 1",
				array($form['pisg_path'], $form['pisg_link'], $form['pisg_target'], $form['pisg_title'], $form['pisg_order'], $form['pisg_status'], $form['pisg_type'], $did)
			);
		$wpdb->query($sSql);
		$pisg_success = __('Image details was successfully updated.', 'pixelating-image');
	}
}

if ($pisg_error_found == TRUE && isset($pisg_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $pisg_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($pisg_error_found == FALSE && strlen($pisg_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $pisg_success; ?> <a href="<?php echo WP_pisg_ADMIN_URL; ?>"><?php _e('Click here to view the details', 'pixelating-image'); ?></a></strong></p>
	</div>
	<?php
}
?>
<script language="JavaScript" src="<?php echo WP_pisg_PLUGIN_URL; ?>/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Pixelating image slideshow', 'pixelating-image'); ?></h2>
	<form name="pisg_form" method="post" action="#" onsubmit="return pisg_submit()"  >
      <h3><?php _e('Update image details', 'pixelating-image'); ?></h3>
      <label for="tag-image"><?php _e('Enter image path', 'pixelating-image'); ?></label>
      <input name="pisg_path" type="text" id="pisg_path" value="<?php echo $form['pisg_path']; ?>" size="125" />
      <p><?php _e('Where is the picture located on the internet', 'pixelating-image'); ?></p>
      <label for="tag-link"><?php _e('Enter target link', 'pixelating-image'); ?></label>
      <input name="pisg_link" type="text" id="pisg_link" value="<?php echo $form['pisg_link']; ?>" size="125" />
      <p><?php _e('When someone clicks on the picture, where do you want to send them', 'pixelating-image'); ?></p>
      <label for="tag-target"><?php _e('Select target option', 'pixelating-image'); ?></label>
      <select name="pisg_target" id="pisg_target">
        <option value='_blank' <?php if($form['pisg_target']=='_blank') { echo 'selected' ; } ?>>_blank</option>
        <option value='_parent' <?php if($form['pisg_target']=='_parent') { echo 'selected' ; } ?>>_parent</option>
        <option value='_self' <?php if($form['pisg_target']=='_self') { echo 'selected' ; } ?>>_self</option>
        <option value='_new' <?php if($form['pisg_target']=='_new') { echo 'selected' ; } ?>>_new</option>
      </select>
      <p><?php _e('Do you want to open link in new window?', 'pixelating-image'); ?></p>
      <!--<label for="tag-title">Enter image reference</label>
      <input name="pisg_title" type="text" id="pisg_title" value="<?php //echo $form['pisg_title']; ?>" size="125" />
      <p>Enter image reference. This is only for reference.</p>-->
      <label for="tag-select-gallery-group"><?php _e('Select gallery type/group', 'pixelating-image'); ?></label>
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
		for($j=$i; $j<$i+5; $j++)
		{
			$arrDistinctData[$j]["pisg_type"] = "GROUP" . $j;
		}
		$arrDistinctData[$j+1]["pisg_type"] = "WIDGET";
		$arrDistinctData[$j+2]["pisg_type"] = "SAMPLE";
		$selected = "";
		$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
		foreach ($arrDistinctDatas as $arrDistinct)
		{
			if(strtoupper($form['pisg_type']) == strtoupper($arrDistinct["pisg_type"]) ) 
			{ 
				$selected = "selected"; 
			}
			?>
			<option value='<?php echo $arrDistinct["pisg_type"]; ?>' <?php echo $selected; ?>><?php echo strtoupper($arrDistinct["pisg_type"]); ?></option>
			<?php
			$selected = "";
		}
		?>
		</select>
      <p><?php _e('This is to group the images. Select your slideshow group.', 'pixelating-image'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'pixelating-image'); ?></label>
      <select name="pisg_status" id="pisg_status">
        <option value='YES' <?php if($form['pisg_status']=='YES') { echo 'selected' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['pisg_status']=='NO') { echo 'selected' ; } ?>>No</option>
      </select>
      <p><?php _e('Do you want the picture to show in your galler?', 'pixelating-image'); ?></p>
      <label for="tag-display-order"><?php _e('Display order', 'pixelating-image'); ?></label>
      <input name="pisg_order" type="text" id="pisg_order" size="10" value="<?php echo $form['pisg_order']; ?>" maxlength="3" />
      <p><?php _e('What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.', 'pixelating-image'); ?></p>
      <input name="pisg_id" id="pisg_id" type="hidden" value="">
      <input type="hidden" name="pisg_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="<?php _e('Update Details', 'pixelating-image'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="pisg_redirect()" value="<?php _e('Cancel', 'pixelating-image'); ?>" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="pisg_help()" value="<?php _e('Help', 'pixelating-image'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('pisg_form_edit'); ?>
    </form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'pixelating-image'); ?>
	<a target="_blank" href="<?php echo WP_pisg_FAV; ?>"><?php _e('click here', 'pixelating-image'); ?></a>
</p>
</div>