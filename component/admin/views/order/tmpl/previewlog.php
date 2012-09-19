<?php
/**
 * @version 1.0.5 $Id: default.php 30 2009-11-12 10:22:21Z gunjan $
 * @package Joomla
 * @subpackage redCRM
 * @copyright redCRM (C) 2008 redCOMPONENT.com / redCRM (C) 2005 - 2008 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
 * redCRM is based on EventList made by Christoph Lukes from schlu.net
 * redCRM can be downloaded from www.redcomponent.com
 * redCRM is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * redCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with redCRM; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$option = JRequest::getCmd('option','com_redshop','request','string');
?>
<script language="javascript" type="text/javascript">
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
window.onload = function()
{
	setTimeout('updateOrderStatus(0)', 1000);
}
function updateOrderStatus(cnt)
{
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	{
		alert ("Your browser does not support XMLHTTP!");
		return;
	}

	var rand_invoice_name = document.getElementById('rand_invoice_name').value;
	
	var url='index.php?option=com_redshop&view=order&task=updateOrderStatus&json=1&cnt='+cnt+'&rand_invoice_name='+rand_invoice_name;
	url= url + "&sid="+Math.random();

	xmlhttp = GetXmlHttpObject();
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState == 4)
		{
			if(document.getElementById('divpreviewlog'))
			{
				var newlog = "";
				if(document.getElementById('tmpresponse'))
				{
					document.getElementById('tmpresponse').innerHTML = xmlhttp.responseText;
					if(document.getElementById('sentresponse'))
					{
						newlog = document.getElementById('sentresponse').innerHTML;
					}
				}
				if(newlog!="")
				{
					document.getElementById('divpreviewlog').innerHTML += newlog;
					cnt = parseInt(cnt) + 1;
					setTimeout('updateOrderStatus('+cnt+')', 1000);
				} else {
					if(document.getElementById('divCompleted'))
					{
						document.getElementById('divCompleted').style.display = '';
					}
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
	};
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
}
</script>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" id="adminForm" >
<div id="editcell">
<fieldset>
	<legend><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_UPDATE_LOG');?></legend>
	<table class="admintable" cellpadding="3" cellspacing="0" border="0">
		<tr><td><div id="divpreviewlog"></div></td></tr>
		<tr><td><div id="divCompleted" style="display:none;"><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_COMPLETED');?></div></td></tr>
		<tr><td><div id="divLoading"><img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH;?>preloader.gif" ></div></td></tr>
	</table>
</fieldset>
</div>
<div style="display: none;" id="tmpresponse"></div>
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="view" value="order" />
<input type="hidden" name="rand_invoice_name" id="rand_invoice_name" value="<?php echo rand();?>" />
</form>