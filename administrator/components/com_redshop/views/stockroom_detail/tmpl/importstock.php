<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die('Restricted access');
?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton) 
{
	submitbutton(pressbutton);
}
submitbutton = function(pressbutton) 
{
	var form = document.adminForm;

	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
}

var xmlhttp

function GetXmlHttpObject()
{
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject)
	{
		// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}
function sendrsImportStock(cnt)
{
	if(document.getElementById('stockroom_id') && document.getElementById('stockroom_id').value!=0)
	{
		xmlhttp=GetXmlHttpObject();
		if (xmlhttp==null)
		{
			alert ("Your browser does not support XMLHTTP!");
			return;
		}

		var url="index.php?option=com_redshop&view=stockroom_detail&task=importStockFromEconomic&json=1";
		url= url + "&cnt="+cnt+"&stockroom_id="+parseInt(document.getElementById('stockroom_id').value) + "&sid="+Math.random();
	
		xmlhttp = GetXmlHttpObject();
		xmlhttp.onreadystatechange=function()
		{
			if(xmlhttp.readyState == 4)
			{
				if(document.getElementById('divpreviewlog'))
				{
					var totalusers = 0;
					var newlog = "";
					var txtresponse = "";
					if(document.getElementById('tmpresponse'))
					{
						document.getElementById('tmpresponse').innerHTML = xmlhttp.responseText;
						if(document.getElementById('sentresponse'))
						{
							txtresponse = document.getElementById('sentresponse').innerHTML;
							arrResponse = txtresponse.split("`_`");
							totalusers = arrResponse[0];
							newlog = arrResponse[1];
						}
					}
					if(totalusers!=0)
					{
						document.getElementById('divpreviewlog').innerHTML += newlog;
						cnt = parseInt(cnt) + 10;
						setTimeout('sendrsImportStock('+cnt+')', 1000);
					} else {
						document.getElementById('divpreviewlog').innerHTML += newlog;
					}
				}
				if(document.getElementById('divLoading'))
				{
					document.getElementById('divLoading').style.display = 'none';
				}
			}
			else
			{
				if(document.getElementById('divLoading'))
				{
					document.getElementById('divLoading').style.display = '';
				}
			}
		}
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
	}
	else
	{
		alert("<?php echo JText::_('COM_REDSHOP_SELECT_STOCKROOM_NAME');?>");
		return false;
	}
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_REDSHOP_DETAILS' ); ?></legend>
		<table class="admintable">
		<tr><td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME');?></td><td><?php echo $this->lists['stockroom_id'];?>&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo JText::_('COM_REDSHOP_IMPORT');?>" onclick="sendrsImportStock(0);" /></td></tr>
	</table>
	</fieldset>	
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_REDSHOP_IMPORT_LOG' ); ?></legend>
		<table class="admintable">
		<tr><td><div id="divpreviewlog"></div></td></tr>
		<tr><td><div id="divLoading" style="display: none;"><img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH;?>preloader.gif" ></div></td></tr>
	</table>
	</fieldset>
</div>
<div style="display: none;" id="tmpresponse"></div>
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="stockroom_detail" />
<input type="hidden" name="option" value="com_redshop" />
</form>