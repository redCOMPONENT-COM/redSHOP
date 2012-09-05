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
var xmlhttp;

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
	importdata(1);
}
function importdata(new_line)
{
	//alert(new_line);
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	{
		alert ("Your browser does not support XMLHTTP!");
		return;
	}
	
	
	var url='index.php?option=com_redshop&view=import&task=importdata&json=1&new_line='+new_line;
	url= url + "&sid="+Math.random();
	
	xmlhttp = GetXmlHttpObject();
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState == 4)
		{
			var response = xmlhttp.responseText;
			
			var arrResponse = response.split("`_`");
			
			if(arrResponse[0] !="")
			{	
				
				importdata(arrResponse[0]);
				if(document.getElementById('tmpresponse') && document.getElementById('divStatus'))
				{
					document.getElementById('divStatus').innerHTML = arrResponse[0];
					document.getElementById('divStatus').style.display = '';
					document.getElementById('tmpresponse').style.display = '';
				}
				
			} else {
				if(document.getElementById('divLoading'))
				{
					document.getElementById('divLoading').style.display = 'none';
				}
				if(document.getElementById('tmpresponse') && document.getElementById('divStatus'))
				{
					document.getElementById('divStatus').innerHTML = arrResponse[1];
					document.getElementById('divStatus').style.display = '';
					document.getElementById('tmpresponse').style.display = '';
				}
				if(document.getElementById('divCompleted'))
				{
					document.getElementById('divCompleted').innerHTML = "Complete Process";
					document.getElementById('divCompleted').style.display = '';
				}
				
			}

			
		} else {
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
	<legend><?php echo JText::_('IMPORT_UPDATE_LOG');?></legend>
	<div id="tmpresponse" style="display: none;"><?php echo JText::_('IMPORT_OK');?> &nbsp;&nbsp;&nbsp;<span  id="divStatus"></span></div>
	<table class="admintable" cellpadding="3" cellspacing="0" border="0">
		<tr><td><div id="divpreviewlog"></div></td></tr>
		<tr><td><div id="divCompleted" style="display:none;"></div></td></tr>
		<tr><td><div id="divLoading"><img src="<?php echo JURI::root()?>administrator/components/com_redshop/assets/images/preloader.gif" ></div></td></tr>
	</table>
</fieldset>
</div>

<input type="hidden" name="option" value="<?php echo $option;?>" />

</form>
