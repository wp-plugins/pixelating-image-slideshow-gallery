<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_pisg_display']) && $_POST['frm_pisg_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
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
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'pixelating-image-slideshow-gallery'); ?></strong></p></div><?php
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
			$pisg_success = __('Selected record was successfully deleted.', 'pixelating-image-slideshow-gallery');
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
    <h2><?php _e('Pixelating image slideshow', 'pixelating-image-slideshow-gallery'); ?>
	<a class="add-new-h2" href="<?php echo WP_pisg_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'pixelating-image-slideshow-gallery'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_pisg_TABLE."` order by pisg_type, pisg_order";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo WP_pisg_PLUGIN_URL; ?>/pages/setting.js"></script>
		<form name="frm_pisg_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="row" style="padding: 8px 2px;"><input type="checkbox" name="pisg_group_item[]" /></th>
			<th scope="col"><?php _e('Type', 'pixelating-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('URL', 'pixelating-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('Target', 'pixelating-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Order', 'pixelating-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Display', 'pixelating-image-slideshow-gallery'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="row" style="padding: 8px 2px;"><input type="checkbox" name="pisg_group_item[]" /></th>
			<th scope="col"><?php _e('Type', 'pixelating-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('URL', 'pixelating-image-slideshow-gallery'); ?></th>
			<th scope="col"><?php _e('Target', 'pixelating-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Order', 'pixelating-image-slideshow-gallery'); ?></th>
            <th scope="col"><?php _e('Display', 'pixelating-image-slideshow-gallery'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['pisg_id']; ?>" name="pisg_group_item[]"></td>
						<td>
						<strong><?php echo esc_html(stripslashes($data['pisg_type'])); ?></strong>
						<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo WP_pisg_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['pisg_id']; ?>"><?php _e('Edit', 'pixelating-image-slideshow-gallery'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:pisg_delete('<?php echo $data['pisg_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'pixelating-image-slideshow-gallery'); ?></a></span> 
						</div>
						</td>
						<td><a href="<?php echo $data['pisg_path']; ?>" target="_blank"><?php echo $data['pisg_path']; ?></a></td>
						<td><?php echo esc_html(stripslashes($data['pisg_target'])); ?></td>
						<td><?php echo esc_html(stripslashes($data['pisg_order'])); ?></td>
						<td><?php echo esc_html(stripslashes($data['pisg_status'])); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				}
			}
			else
			{
				?><tr><td colspan="6" align="center"><?php _e('No records available.', 'pixelating-image-slideshow-gallery'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('pisg_form_show'); ?>
		<input type="hidden" name="frm_pisg_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo WP_pisg_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'pixelating-image-slideshow-gallery'); ?></a>
	  <a class="button add-new-h2" href="<?php echo WP_pisg_ADMIN_URL; ?>&amp;ac=set"><?php _e('Widget Setting', 'pixelating-image-slideshow-gallery'); ?></a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_pisg_FAV; ?>"><?php _e('Help', 'pixelating-image-slideshow-gallery'); ?></a>
	  </h2>
	  </div>
	  <br />
	<h3><?php _e('Plugin configuration option', 'pixelating-image-slideshow-gallery'); ?></h3>
	<ol>
		<li><?php _e('Drag and drop the widget.', 'pixelating-image-slideshow-gallery'); ?></li>
		<li><?php _e('Add directly in to the theme using PHP code.', 'pixelating-image-slideshow-gallery'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'pixelating-image-slideshow-gallery'); ?>
		<a target="_blank" href="<?php echo WP_pisg_FAV; ?>"><?php _e('click here', 'pixelating-image-slideshow-gallery'); ?></a>
	</p>
	</div>
</div>