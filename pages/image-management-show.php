<?php
// Form submitted, check the data
if (isset($_POST['frm_pisg_display']) && $_POST['frm_pisg_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$pisg_success = '';
	$pisg_success_msg = FALSE;
	
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
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('pisg_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_pisg_TABLE."`
					WHERE `pisg_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$pisg_success_msg = TRUE;
			$pisg_success = __('Selected record was successfully deleted.', WP_pisg_UNIQUE_NAME);
		}
	}
	
	if ($pisg_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $pisg_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo WP_pisg_TITLE; ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pixelating-image-slideshow-gallery&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_pisg_TABLE."` order by pisg_type, pisg_order";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/pixelating-image-slideshow-gallery/pages/setting.js"></script>
		<form name="frm_pisg_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="row" scope="col"><input type="checkbox" name="pisg_group_item[]" /></td>
			<th scope="col">Type</td>
			<!--<th scope="col">Reference</td>-->
            <th scope="col">URL</td>
			<th scope="col">Target</td>
            <th scope="col">Order</td>
            <th scope="col">Display</td>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="row" scope="col"><input type="checkbox" name="pisg_group_item[]" /></td>
			<th scope="col">Type</td>
			<!--<th scope="col">Reference</td>-->
            <th scope="col">URL</td>
			<th scope="col">Target</td>
            <th scope="col">Order</td>
            <th scope="col">Display</td>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			$displayisthere = FALSE;
			foreach ($myData as $data)
			{
				if($data['pisg_status'] == 'YES') 
				{
					$displayisthere = TRUE; 
				}
				?>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<td align="left"><input type="checkbox" value="<?php echo $data['pisg_id']; ?>" name="pisg_group_item[]"></th>
					<td>
					<strong><?php echo esc_html(stripslashes($data['pisg_type'])); ?></strong>
					<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pixelating-image-slideshow-gallery&amp;ac=edit&amp;did=<?php echo $data['pisg_id']; ?>">Edit</a> | </span>
						<span class="trash"><a onClick="javascript:pisg_delete('<?php echo $data['pisg_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
					</div>
					</td>
					<!--<td><?php //echo esc_html(stripslashes($data['pisg_title'])); ?></td>-->
					<td><a href="<?php echo esc_html(stripslashes($data['pisg_path'])); ?>" target="_blank"><?php echo esc_html(stripslashes($data['pisg_path'])); ?></a></td>
					<td><?php echo esc_html(stripslashes($data['pisg_target'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['pisg_order'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['pisg_status'])); ?></td>
				</tr>
				<?php 
				$i = $i+1; 
				} 
			?>
			<?php 
			if ($displayisthere == FALSE) 
			{ 
				?><tr><td colspan="6" align="center">No records available.</td></tr><?php 
			} 
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('pisg_form_show'); ?>
		<input type="hidden" name="frm_pisg_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pixelating-image-slideshow-gallery&amp;ac=add">Add New</a>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=pixelating-image-slideshow-gallery&amp;ac=set">Widget setting</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_pisg_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <br />
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Drag and drop the widget.</li>
		<li>Add directly in to the theme using PHP code.</li>
	</ol>
	  <p class="description"><?php echo WP_pisg_LINK; ?></p>
	</div>
</div>