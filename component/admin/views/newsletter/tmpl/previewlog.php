<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


$model = $this->getModel('newsletter');
$cid = JRequest::getVar('cid', array(0), 'post', 'array');
$newsletter_id = JRequest::getVar('newsletter_id');
?>
<script language="javascript" type="text/javascript">
	var xmlhttp

	function GetXmlHttpObject() {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			return new XMLHttpRequest();
		}
		if (window.ActiveXObject) {
			// code for IE6, IE5
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		return null;
	}
	window.onload = function () {
		setTimeout('sendrsNewsletter()', <?php echo (Redshop::getConfig()->get('NEWSLETTER_MAIL_PAUSE_TIME')*1000);?>);
	}
	//function submitform()
	//{
	//	window.location.href =
	//}
	function sendrsNewsletter() {
		xmlhttp = GetXmlHttpObject();
		if (xmlhttp == null) {
			alert("Your browser does not support XMLHTTP!");
			return;
		}

		var extraurl = '';
		if (document.getElementById('newsletter_id')) {
			extraurl = extraurl + "&newsletter_id=" + parseInt(document.getElementById('newsletter_id').value);
		}

		var url = 'index.php?option=com_redshop&view=newsletter&task=sendRecursiveNewsletter&json=1';
		url = url + extraurl + "&sid=" + Math.random();

		xmlhttp = GetXmlHttpObject();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4) {
				if (document.getElementById('divpreviewlog')) {
					var newlog = "";
					if (document.getElementById('tmpmailresponse')) {
						document.getElementById('tmpmailresponse').innerHTML = xmlhttp.responseText;
						if (document.getElementById('sentresponse')) {
							newlog = document.getElementById('sentresponse').innerHTML;
						}
					}
					if (newlog != "") {
						var log = document.getElementById('divpreviewlog').innerHTML;
						document.getElementById('divpreviewlog').innerHTML = log + newlog;
						setTimeout('sendrsNewsletter()', <?php echo (Redshop::getConfig()->get('NEWSLETTER_MAIL_PAUSE_TIME')*1000);?>);
					}
				}
			}
		}
		xmlhttp.open("GET", url, true);
		xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xmlhttp.send(null);
	}
</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div>
		<fieldset>
			<legend><?php echo JText::_('COM_REDSHOP_NEWSLETTER_SEND_LOG');?></legend>
			<table class="admintable" cellpadding="3" cellspacing="0" border="0">
				<tr>
					<td>
						<div id="divpreviewlog"></div>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div style="display: none;" id="tmpmailresponse"></div>
	<input type="hidden" name="newsletter_id" id="newsletter_id" value="<?php echo $newsletter_id; ?>"/>
	<input type="hidden" name="cid[]" value="<?php echo $newsletter_id; ?>"/>
	<input type="hidden" name="view" value="newsletter"/>
</form>
