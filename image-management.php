<!--
##############################################################################################################################
###### Project   : Pixelating image slideshow gallery  																	######
###### File Name : image-management.php                   																######
###### Purpose   : This page is to manage the image.  																	######
###### Created   : 13-10-10                  																			######
###### Modified  : 13-10-10                  																			######
###### Author    : Gopi.R (http://www.gopipulse.com/work/)                        										######
###### Link      : http://www.gopipulse.com/work/2010/10/13/pixelating-image-slideshow-gallery/  						######
##############################################################################################################################
-->

<div class="wrap">
  <?php
  	global $wpdb;
    $mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=pixelating-image-slideshow-gallery/image-management.php";
    $DID=@$_GET["DID"];
    $AC=@$_GET["AC"];
    $submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['pisg_link']) <>"")
    {
			if(@$_POST['pisg_id'] == "" )
			{
					$sql = "insert into ".WP_pisg_TABLE.""
					. " set `pisg_path` = '" . mysql_real_escape_string(trim($_POST['pisg_path']))
					. "', `pisg_link` = '" . mysql_real_escape_string(trim($_POST['pisg_link']))
					. "', `pisg_target` = '" . mysql_real_escape_string(trim($_POST['pisg_target']))
					. "', `pisg_title` = '" . mysql_real_escape_string(trim($_POST['pisg_title']))
					. "', `pisg_order` = '" . mysql_real_escape_string(trim($_POST['pisg_order']))
					. "', `pisg_status` = '" . mysql_real_escape_string(trim($_POST['pisg_status']))
					. "', `pisg_type` = '" . mysql_real_escape_string(trim($_POST['pisg_type']))
					. "'";	
			}
			else
			{
					$sql = "update ".WP_pisg_TABLE.""
					. " set `pisg_path` = '" . mysql_real_escape_string(trim($_POST['pisg_path']))
					. "', `pisg_link` = '" . mysql_real_escape_string(trim($_POST['pisg_link']))
					. "', `pisg_target` = '" . mysql_real_escape_string(trim($_POST['pisg_target']))
					. "', `pisg_title` = '" . mysql_real_escape_string(trim($_POST['pisg_title']))
					. "', `pisg_order` = '" . mysql_real_escape_string(trim($_POST['pisg_order']))
					. "', `pisg_status` = '" . mysql_real_escape_string(trim($_POST['pisg_status']))
					. "', `pisg_type` = '" . mysql_real_escape_string(trim($_POST['pisg_type']))
					. "' where `pisg_id` = '" . $_POST['pisg_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".WP_pisg_TABLE." where pisg_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".WP_pisg_TABLE." where pisg_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) @$pisg_id_x = htmlspecialchars(stripslashes($data->pisg_id)); 
		if ( !empty($data) ) @$pisg_path_x = htmlspecialchars(stripslashes($data->pisg_path)); 
        if ( !empty($data) ) @$pisg_link_x = htmlspecialchars(stripslashes($data->pisg_link));
		if ( !empty($data) ) @$pisg_target_x = htmlspecialchars(stripslashes($data->pisg_target));
        if ( !empty($data) ) @$pisg_title_x = htmlspecialchars(stripslashes($data->pisg_title));
		if ( !empty($data) ) @$pisg_order_x = htmlspecialchars(stripslashes($data->pisg_order));
		if ( !empty($data) ) @$pisg_status_x = htmlspecialchars(stripslashes($data->pisg_status));
		if ( !empty($data) ) @$pisg_type_x = htmlspecialchars(stripslashes($data->pisg_type));
        $submittext = "Update Message";
    }
    ?>
  <h2>Pixelating image slideshow gallery</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/pixelating-image-slideshow-gallery/setting.js"></script>
  <form name="pisg_form" method="post" action="<?php echo $mainurl; ?>" onsubmit="return pisg_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="2" align="left" valign="middle">Enter image url:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pisg_path" type="text" id="pisg_path" value="<?php echo @$pisg_path_x; ?>" size="125" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter target link:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pisg_link" type="text" id="pisg_link" value="<?php echo @$pisg_link_x; ?>" size="125" /></td>
      </tr>
	  <!--<tr>
        <td colspan="2" align="left" valign="middle">Enter target option:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pisg_target" type="text" id="pisg_target" value="<?php //echo $pisg_target_x; ?>" size="50" /> ( _blank, _parent, _self, _new )</td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter image title:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pisg_title" type="text" id="pisg_title" value="<?php //echo $pisg_title_x; ?>" size="125" /></td>
      </tr>-->
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter gallery type (This is to group the images):</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pisg_type" type="text" id="pisg_type" value="<?php echo @$pisg_type_x; ?>" size="50" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Display Status:</td>
        <td align="left" valign="middle">Display Order:</td>
      </tr>
      <tr>
        <td width="22%" align="left" valign="middle"><select name="pisg_status" id="pisg_status">
            <option value="">Select</option>
            <option value='YES' <?php if(@$pisg_status_x=='YES') { echo 'selected' ; } ?>>Yes</option>
            <option value='NO' <?php if(@$pisg_status_x=='NO') { echo 'selected' ; } ?>>No</option>
          </select>
        </td>
        <td width="78%" align="left" valign="middle"><input name="pisg_order" type="text" id="pisg_rder" size="10" value="<?php echo @$pisg_order_x; ?>" maxlength="3" /></td>
      </tr>
      <tr>
        <td height="35" colspan="2" align="left" valign="bottom"><table width="100%">
            <tr>
              <td width="50%" align="left"><input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
                <input name="publish" lang="publish" class="button-primary" onclick="pisg_redirect()" value="Cancel" type="button" />
              </td>
              <td width="50%" align="right">
			  <input name="text_management1" lang="text_management" class="button-primary" onClick="location.href='options-general.php?page=pixelating-image-slideshow-gallery/image-management.php'" value="Go to - Image Management" type="button" />
        	  <input name="setting_management1" lang="setting_management" class="button-primary" onClick="location.href='options-general.php?page=pixelating-image-slideshow-gallery/pixelating-image-slideshow-gallery.php'" value="Go to - Gallery Setting" type="button" />
			  <input name="Help1" lang="publish" class="button-primary" onclick="pisg_help()" value="Help" type="button" />
			  </td>
            </tr>
          </table></td>
      </tr>
      <input name="pisg_id" id="pisg_id" type="hidden" value="<?php echo @$pisg_id_x; ?>">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WP_pisg_TABLE." order by pisg_type,pisg_order");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="frm_pisg_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="10%" align="left" scope="col">Type
              </td>
            <th width="52%" align="left" scope="col">Image
              </td>
		    <th width="8%" align="left" scope="col">Order
              </td>
            <th width="7%" align="left" scope="col">Display
              </td>
            <th width="13%" align="left" scope="col">Action
          </td>          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		if($data->pisg_status=='YES') { $displayisthere="True"; }
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->pisg_type)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->pisg_path)); ?></td>
			<td align="left" valign="middle"><?php echo(stripslashes($data->pisg_order)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->pisg_status)); ?></td>
            <td align="left" valign="middle"><a href="options-general.php?page=pixelating-image-slideshow-gallery/image-management.php&DID=<?php echo($data->pisg_id); ?>">Edit</a> &nbsp; <a onClick="javascript:pisg_delete('<?php echo($data->pisg_id); ?>')" href="javascript:void(0);">Delete</a> </td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
        <?php if($displayisthere<>"True") { ?>
        <tr>
          <td colspan="5" align="center" style="color:#FF0000" valign="middle">No message available with display status 'Yes'!' </td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
  <table width="100%">
    <tr>
      <td align="right"><input name="text_management" lang="text_management" class="button-primary" onClick="location.href='options-general.php?page=pixelating-image-slideshow-gallery/image-management.php'" value="Go to - Image Management" type="button" />
        <input name="setting_management" lang="setting_management" class="button-primary" onClick="location.href='options-general.php?page=pixelating-image-slideshow-gallery/pixelating-image-slideshow-gallery.php'" value="Go to - Gallery Setting" type="button" />
		<input name="Help" lang="publish" class="button-primary" onclick="pisg_help()" value="Help" type="button" />
      </td>
    </tr>
  </table>
</div>
Check official website for more info <a target="_blank" href='http://www.gopipulse.com/work/2010/10/13/pixelating-image-slideshow-gallery/'>Click here</a>.  