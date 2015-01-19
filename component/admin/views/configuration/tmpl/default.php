<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

//JHTML::_ ( 'behavior.tooltip' );
JHTMLBehavior::modal();

$option = JRequest::getVar('option');

$uri = JURI::getInstance();
$url = $uri->root();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if (pressbutton == 'save' || pressbutton == 'apply') {
			var obj = form.economic_integration;

			var sel_discount_flag = false;

			if (radioGetCheckedValue(form.discount_enable) == 1 ||
				radioGetCheckedValue(form.coupons_enable) == 1 ||
				radioGetCheckedValue(form.vouchers_enable) == 1) {
				sel_discount_flag = true;
			}

			if (sel_discount_flag) {
				if (form.discount_type.value == "0" || form.discount_type.value == "") {
					alert("<?php
				echo JText::_('COM_REDSHOP_PLEASE_SELECT_DISCOUNT_TYPE' );
				?>");
					return false;
				}
			}

			for (i = 0; i < obj.length; i++) {
				if (form.economic_integration[i].value == 1 && form.economic_integration[i].checked) {
					if (form.default_economic_account_group.value == 0) {
						alert("<?php
					echo JText::_('COM_REDSHOP_SELECT_ECONOMIC_ACCOUNTING_GROUP' );
					?>");
						form.default_economic_account_group.focus();
						return false;
					}

					if (form.economic_invoice_draft.value == 2 && form.booking_order_status.value == '0') {
						alert("<?php echo JText::_('COM_REDSHOP_SELECT_BOOK_INVOICE_ORDER_STATUS' );?>");
						form.booking_order_status.focus();
						return false;
					}
				}
			}
			if (form.thousand_seperator.value == "'" || form.thousand_seperator.value == '"') {
				alert("<?php echo JText::_('COM_REDSHOP_INVALID_THOUSAND_SEPERATOR' );?>");
				form.thousand_seperator.value = '';
				form.thousand_seperator.focus();
				return false;
			}
			if (form.price_seperator.value == "'" || form.price_seperator.value == '"') {
				alert("<?php echo JText::_('COM_REDSHOP_INVALID_PRICE_SEPERATOR' );?>");
				form.price_seperator.value = '';
				form.price_seperator.focus();
				return false;
			}
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<?php
	$dashboard = JRequest::getVar('dashboard');

	if ($dashboard == 1)
	{
		$offset = 9;
	}
	else
	{
		$offset = 0;
	}

	echo JHtml::_('tabs.start', 'pane', array('startOffset' => $offset));
	$output = '';
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_GENERAL_CONFIGURATION'), 'tab1');
	?>
	<input type="hidden" name="view" value="configuration"/>
	<input type="hidden" name="task" value=""/>
	<?php
	echo $this->loadTemplate('general');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_USER'), 'tab11');
	echo $this->loadTemplate('user');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_CATEGORY_TAB'), 'tab5');
	echo $this->loadTemplate('cattab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_REDMANUFACTURER_TAB'), 'tab5');
	echo $this->loadTemplate('manufacturertab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_PRODUCT_TAB'), 'tab5');
	echo $this->loadTemplate('producttab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_FEATURE_TAB'), 'tab5');
	echo $this->loadTemplate('featuretab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_PRICE_TAB'), 'tab5');
	echo $this->loadTemplate('pricetab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_CART_TAB'), 'tab5');
	echo $this->loadTemplate('carttab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_ORDER_TAB'), 'tab5');
	echo $this->loadTemplate('ordertab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_NEWSLETTER_TAB'), 'tab5');
	echo $this->loadTemplate('newslettertab');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_INTEGRATION'), 'tab4');
	echo $this->loadTemplate('integration');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_SEO'), 'tab7');
	echo $this->loadTemplate('seo');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_DASHBOARD'), 'tab8');
	echo $this->loadTemplate('dashboard');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_ABOUT'), 'tab9');
	echo $this->loadTemplate('redshopabout');
	echo JHtml::_('tabs.end');
	?>
	<input type="hidden" name="cid" value="1"/>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
</form>
<script type="text/javascript">
	function clearsef() {

		request = getHTTPObject();
		request.onreadystatechange = cleardata;
		var rand_no = Math.random();
		request.open("GET", "index.php?tmpl=component&option=com_redshop&view=configuration&rand_no=" + rand_no + "&task=clearsef", true);
		request.send(null);
	}
	function cleardata() {

		if (request.readyState == 4) {
			var output = request.responseText;
			if (output == 0) {
				document.getElementById('responce_clear').style.color = "red";
				document.getElementById('responce_clear').innerHTML = "<?php
echo JText::_('COM_REDSHOP_NO_DATA_DELETE' );
?>";
			} else {
				document.getElementById('responce_clear').style.color = "green";
				document.getElementById('responce_clear').innerHTML = output + " <?php echo JText::_('COM_REDSHOP_RECORDS_DELETED');?>";
			}
		}
	}
	function getHTTPObject() {
		var xhr = false;
		if (window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e) {
				try {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e) {
					xhr = false;
				}
			}
		}
		return xhr;
	}
</script>
