<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>

<script type="text/javascript">
    (function($){
        $(window).load(function(){
            importdata(1);
        });
    })(jQuery);
</script>

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
			return new ActiveXObject("Microsoft.XMLHTTP");
		}

		return null;
	}

	function importdata(new_line)
	{
		xmlhttp = GetXmlHttpObject();

		if (xmlhttp == null)
		{
			alert("Your browser does not support XMLHTTP!");

			return;
		}

		var url = 'index.php?option=com_redshop&view=import&task=importdata&json=1&new_line=' + new_line;
		url = url + "&sid=" + Math.random();

		xmlhttp = GetXmlHttpObject();

		xmlhttp.onreadystatechange = function ()
		{
			if (xmlhttp.readyState == 4)
			{
				var response    = xmlhttp.responseText;
				var arrResponse = response.split("`_`");

				if (arrResponse[0] != "")
				{
					importdata(arrResponse[0]);

					if (document.getElementById('tmpresponse') && document.getElementById('divStatus'))
					{
						document.getElementById('divStatus').innerHTML = arrResponse[0];
						document.getElementById('divStatus').style.display = '';
						document.getElementById('tmpresponse').style.display = '';
					}

				}
				else
				{
					if (document.getElementById('divLoading'))
					{
						document.getElementById('divLoading').style.display = 'none';
					}

					if (document.getElementById('tmpresponse') && document.getElementById('divStatus'))
					{
						document.getElementById('divStatus').innerHTML = arrResponse[1];
						document.getElementById('divStatus').style.display = '';
						document.getElementById('tmpresponse').style.display = '';
					}

					if (document.getElementById('divCompleted'))
					{
						document.getElementById('divCompleted').innerHTML = "Complete Process";
						document.getElementById('divCompleted').style.display = '';
					}
				}
			}
			else
			{
				if (document.getElementById('divLoading')) {
					document.getElementById('divLoading').style.display = '';
				}
			}

		};

		xmlhttp.open("GET", url, true);
		xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xmlhttp.send(null);
	}
</script>
<form action="index.php?option=com_redshop&view=import&layout=importlog" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<fieldset>
			<legend><?php echo JText::_('COM_REDSHOP_IMPORT_UPDATE_LOG'); ?></legend>
			<div id="tmpresponse" style="display: none;"><?php echo JText::_('COM_REDSHOP_IMPORT_OK'); ?> &nbsp;&nbsp;&nbsp;<span
					id="divStatus"></span></div>
			<table class="admintable" cellpadding="3" cellspacing="0" border="0">
				<tr>
					<td>
						<div id="divpreviewlog"></div>
					</td>
				</tr>
				<tr>
					<td>
						<div id="divCompleted" style="display:none;"></div>
					</td>
				</tr>
				<tr>
					<td>
						<div id="divLoading"><img
								src="<?php echo JURI::root() ?>administrator/components/com_redshop/assets/images/preloader.gif">
						</div>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_redshop"/>
</form>
