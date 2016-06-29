<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHTMLBehavior::modal();

$uri = JURI::getInstance();
$url = $uri->root();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		// Find the position of selected tab
		var allTabsNames = document.querySelectorAll('dt.tabs a');
		var selectedTabName  = document.querySelectorAll('dt.tabs.open a');
		for (var i=0; i < allTabsNames.length; i++) {
			if (selectedTabName[0].innerHTML === allTabsNames[i].innerHTML) {
				var selectedTabPosition = i;
				break;
			}
		}

		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if (pressbutton == 'save' || pressbutton == 'apply') {
			if (pressbutton == 'save')
				form.selectedTabPosition.value = 0;
			else
				form.selectedTabPosition.value = selectedTabPosition;

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
<form action="<?php echo 'index.php?option=com_redshop'; ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<?php
	$dashboard = JFactory::getApplication()->input->getInt('dashboard', 0);
	$app = JFactory::getApplication();
	$options = array('active' => 'general');

	if ($dashboard)
	{
		$options = array('active' => 'dashboard');
	}

	echo JHtml::_('bootstrap.startTabSet', 'config', $options);

	$app->setUserState('com_redshop.configuration.selectedTabPosition', null);
	$output = '';
	echo JHtml::_('bootstrap.addTab', 'config', 'general', JText::_('COM_REDSHOP_GENERAL_CONFIGURATION', true));
	?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<input type="hidden" name="view" value="configuration"/>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="selectedTabPosition" value=""/>
			<?php echo $this->loadTemplate('general'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'user', JText::_('COM_REDSHOP_USER', true)); ?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('user'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'config', 'cattab', JText::_('COM_REDSHOP_CATEGORY_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('cattab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'config', 'manufacturertab', JText::_('COM_REDSHOP_REDMANUFACTURER_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('manufacturertab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'producttab', JText::_('COM_REDSHOP_PRODUCT_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('producttab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'featuretab', JText::_('COM_REDSHOP_FEATURE_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('featuretab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'pricetab', JText::_('COM_REDSHOP_PRICE_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('pricetab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'carttab', JText::_('COM_REDSHOP_CART_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('carttab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'ordertab', JText::_('COM_REDSHOP_ORDER_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('ordertab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'newslettertab', JText::_('COM_REDSHOP_NEWSLETTER_TAB', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('newslettertab'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'integration', JText::_('COM_REDSHOP_INTEGRATION', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('integration'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'seo', JText::_('COM_REDSHOP_SEO', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('seo'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'dashboard', JText::_('COM_REDSHOP_DASHBOARD', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('dashboard'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'config', 'redshopabout', JText::_('COM_REDSHOP_ABOUT', true));?>
	<div class="row-fluid">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('redshopabout'); ?>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	<input type="hidden" name="cid" value="1"/>
	<input type="hidden" name="option" value="com_redshop"/>
</form>
<script type="text/javascript">
	function cleardata() {

		if (request.readyState == 4) {
			var output = request.responseText;
			if (output == 0) {
				document.getElementById('responce_clear').style.color = "red";
				document.getElementById('responce_clear').innerHTML = "<?php echo JText::_('COM_REDSHOP_NO_DATA_DELETE' ); ?>";
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
