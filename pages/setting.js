/*
##################################################################################################################################
###### Project   : Pixelating image slideshow gallery 6.7																	######
###### File Name : setting.js                   																			######
###### Purpose   : This javascript is to authenticate the form.  															######
###### Author    : Gopi.R (http://www.gopiplus.com/work/)                        											######
###### Link      : http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/      						######
##################################################################################################################################
*/

function pisg_submit()
{
	if(document.pisg_form.pisg_path.value=="")
	{
		alert("Please enter the image path.")
		document.pisg_form.pisg_path.focus();
		return false;
	}
	else if(document.pisg_form.pisg_link.value=="")
	{
		alert("Please enter the target link.")
		document.pisg_form.pisg_link.focus();
		return false;
	}
	else if(document.pisg_form.pisg_type.value=="")
	{
		alert("Please enter the gallery type.")
		document.pisg_form.pisg_type.focus();
		return false;
	}
	else if(document.pisg_form.pisg_status.value=="")
	{
		alert("Please select the display status.")
		document.pisg_form.pisg_status.focus();
		return false;
	}
	else if(document.pisg_form.pisg_order.value=="")
	{
		alert("Please enter the display order, only number.")
		document.pisg_form.pisg_order.focus();
		return false;
	}
	else if(isNaN(document.pisg_form.pisg_order.value))
	{
		alert("Please enter the display order, only number.")
		document.pisg_form.pisg_order.focus();
		return false;
	}
}

function pisg_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_pisg_display.action="options-general.php?page=pixelating-image-slideshow-gallery&ac=del&did="+id;
		document.frm_pisg_display.submit();
	}
}	

function pisg_redirect()
{
	window.location = "options-general.php?page=pixelating-image-slideshow-gallery";
}

function pisg_help()
{
	window.open("http://www.gopiplus.com/work/2010/10/13/pixelating-image-slideshow-gallery/");
}