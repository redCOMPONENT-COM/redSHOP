<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

//JHTML::_ ( 'behavior.tooltip' );
JHTMLBehavior::modal();

$option = JRequest::getVar('option');
jimport('joomla.html.pane');

$uri = JURI::getInstance();
$url = $uri->root();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if (pressbutton == 'save' || pressbutton == 'apply') {
			//checkDiscountType();
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
	//Get JPaneTabs instance
	$dashboard = JRequest::getVar('dashboard');
	if ($dashboard == 1)
	{
		$myTabs = JPane::getInstance('tabs', array('startOffset' => 9));

	}
	else
	{
		$myTabs = JPane::getInstance('tabs', array('startOffset' => 0));
	}
	$output = '';

	//Create Pane
	$output .= $myTabs->startPane('pane');
	//Create 1st Tab
	echo $output .= $myTabs->startPanel(JText::_('COM_REDSHOP_GENERAL_CONFIGURATION'), 'tab1');
	?>
	<input type="hidden" name="view" value="configuration"/>
	<input type="hidden" name="task" value=""/>
	<?php

	echo $this->loadTemplate('general');

	echo $myTabs->endPanel();

	# user tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_USER'), 'tab11');

	echo $this->loadTemplate('user');

	echo $myTabs->endPanel();
	//End Pane
	# category tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_CATEGORY_TAB'), 'tab5');

	echo $this->loadTemplate('cattab');

	echo $myTabs->endPanel();
	# End tab
	# category tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_REDMANUFACTURER_TAB'), 'tab5');

	echo $this->loadTemplate('manufacturertab');

	echo $myTabs->endPanel();
	# End tab
	# product tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_PRODUCT_TAB'), 'tab5');

	echo $this->loadTemplate('producttab');

	echo $myTabs->endPanel();
	# End tab
	# Feature tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_FEATURE_TAB'), 'tab5');

	echo $this->loadTemplate('featuretab');

	echo $myTabs->endPanel();
	# End tab

	//Create *th Tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_PRICE_TAB'), 'tab5');

	echo $this->loadTemplate('pricetab');

	echo $myTabs->endPanel();



	# product tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_CART_TAB'), 'tab5');

	echo $this->loadTemplate('carttab');

	echo $myTabs->endPanel();

	# product tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_ORDER_TAB'), 'tab5');

	echo $this->loadTemplate('ordertab');

	echo $myTabs->endPanel();

	# product tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_NEWSLETTER_TAB'), 'tab5');

	echo $this->loadTemplate('newslettertab');

	echo $myTabs->endPanel();
	//Create 2nd Tab
	//echo $myTabs->startPanel ( JText::_ ( 'LAYOUT_CONFIGURATION' ), 'tab2' );

	//echo $this->loadTemplate('layout');

	//echo $myTabs->startPanel ( JText::_ ( 'IMAGES' ), 'tab7' );

	//echo $this->loadTemplate('images');

	//echo $myTabs->endPanel ();

	//Create 4th Tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_INTEGRATION'), 'tab4');

	echo $this->loadTemplate('integration');

	echo $myTabs->endPanel();

	//Create 8th Tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_SEO'), 'tab7');

	echo $this->loadTemplate('seo');

	echo $myTabs->endPanel();

	//Create 8th Tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_DASHBOARD'), 'tab8');

	echo $this->loadTemplate('dashboard');

	echo $myTabs->endPanel();


	//Create 9th Tab
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_ABOUT'), 'tab9');

	echo $this->loadTemplate('redshopabout');

	echo $myTabs->endPanel();

	echo $myTabs->endPane();
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
