<div class="wrap">
<?php
$pisg_errors = array();
$pisg_success = '';
$pisg_error_found = FALSE;

// Preset the form fields
$form = array(
	'pisg_path' => '',
	'pisg_link' => '',
	'pisg_target' => '',
	'pisg_title' => '',
	'pisg_order' => '',
	'pisg_status' => '',
	'pisg_type' => ''
);

// Form submitted, check the data
if (isset($_POST['pisg_form_submit']) && $_POST['pisg_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('pisg_form_add');
	
	$form['pisg_path'] = isset($_POST['pisg_path']) ? $_POST['pisg_path'] : '';
	if ($form['pisg_path'] == '')
	{
		$pisg_errors[] = __('Please enter the image path.', WP_pisg_UNIQUE_NAME);
		$pisg_error_found = TRUE;
	}

	$form['pisg_link'] = isset($_POST['pisg_link']) ? $_POST['pisg_link'] : '';
	if ($form['pisg_link'] == '')
	{
		$pisg_errors[] = __('Please enter the target link.', WP_pisg_UNIQUE_NAME);
		$pisg_error_found = TRUE;
	}
	
	$form['pisg_target'] = isset($_POST['pisg_target']) ? $_POST['pisg_target'] : '';
	$form['pisg_title'] = ""; // isset($_POST['pisg_title']) ? $_POST['pisg_title'] : '';
	$form['pisg_order'] = isset($_POST['pisg_order']) ? $_POST['pisg_order'] : '';
	$form['pisg_status'] = isset($_POST['pisg_status']) ? $_POST['pisg_status'] : '';
	$form['pisg_type'] = isset($_POST['pisg_type']) ? $_POST['pisg_type'] : '';

	//	No errors found, we can add this Group to the table
	if ($pisg_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_pisg_TABLE."`
			(`pisg_path`, `pisg_link`, `pisg_target`, `pisg_title`, `pisg_order`, `pisg_status`, `pisg_type`)
			VALUES(%s, %s, %s, %s, %d, %s, %s)",
			array($form['pisg_path'], $form['pisg_link'], $form['pisg_target'], $form['pisg_title'], $form['pisg_order'], $form['pisg_status'], $form['pisg_type'])
		);
		$wpdb->query($sql);
		
		$pisg_success = __('New image details was successfully added.', WP_pisg_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'pisg_path' => '',
			'pisg_link' => '',
			'pisg_target' => '',
			'pisg_title' => '',
			'pisg_order' => '',
			'pisg_status' => '',
			'pisg_type' => ''
		);
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
		<p><strong><?php echo $pisg_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pixelating-image-slideshow-gallery">Click here</a> to view the details</strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/pixelating-image-slideshow-gallery/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo WP_pisg_TITLE; ?></h2>
	<form name="pisg_form" method="post" action="#" onsubmit="return pisg_submit()"  >
      <h3>Add new image details</h3>
      <label for="tag-image">Enter image path (URL)</label>
      <input name="pisg_path" type="text" id="pisg_path" value="" size="125" />
      <p>Where is the picture located on the internet (ex: http://www.gopiplus.com/work/wp-content/uploads/pluginimages/250x167/250x167_2.jpg)</p>
      <label for="tag-link">Enter target link</label>
      <input name="pisg_link" type="text" id="pisg_link" value="" size="125" />
      <p>When someone clicks on the picture, where do you want to send them</p>
      <label for="tag-target">Select target option</label>
      <select name="pisg_target" id="pisg_target">
        <option value='_blank'>_blank</option>
        <option value='_parent'>_parent</option>
        <option value='_self'>_self</option>
        <option value='_new'>_new</option>
      </select>
      <p>Do you want to open link in new window?</p>
      <!--<label for="tag-title">Enter image reference</label>
      <input name="pisg_title" type="text" id="pisg_title" value="" size="125" />
      <p>Enter image reference. This is only for reference.</p>-->
      <label for="tag-select-gallery-group">Select gallery type/group</label>
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
		$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
		foreach ($arrDistinctDatas as $arrDistinct)
		{
			?><option value='<?php echo $arrDistinct["pisg_type"]; ?>'><?php echo $arrDistinct["pisg_type"]; ?></option><?php
		}
		?>
		</select>
      <p>This is to group the images. Select your slideshow group. </p>
      <label for="tag-display-status">Display status</label>
      <select name="pisg_status" id="pisg_status">
        <option value='YES'>Yes</option>
        <option value='NO'>No</option>
      </select>
      <p>Do you want the picture to show in your galler?</p>
      <label for="tag-display-order">Display order</label>
      <input name="pisg_order" type="text" id="pisg_order" size="10" value="" maxlength="3" />
      <p>What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.</p>
      <input name="pisg_id" id="pisg_id" type="hidden" value="">
      <input type="hidden" name="pisg_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="Insert Details" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="pisg_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="pisg_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('pisg_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo WP_pisg_LINK; ?></p>
</div>