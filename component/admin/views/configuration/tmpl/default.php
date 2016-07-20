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

$app = JFactory::getApplication();
$dashboard = $app->input->getInt('dashboard', 0);
$selectedTabPosition = $app->getUserState('com_redshop.configuration.selectedTabPosition', 'general');

if ($dashboard)
{
	$selectedTabPosition = $dashboard;
}


?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		// Find the position of selected tab
		var allTabsNames = document.querySelectorAll('.tabconfig a');
		var selectedTabName  = document.querySelectorAll('.tabconfig li.active a');

		for (var i=0; i < allTabsNames.length; i++) {
			if (selectedTabName[0].innerHTML === allTabsNames[i].innerHTML) {
				var selectedTabPosition =allTabsNames[i].getAttribute("aria-controls");
				break;
			}
		}

		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if (pressbutton == 'save' || pressbutton == 'apply') {
			if (pressbutton == 'save')
				form.selectedTabPosition.value = '';
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
	<div class="row">
		<div class="col-sm-2">
			<div class="box">
				<div class="box-body no-padding">
				  <ul class="tabconfig nav nav-pills nav-stacked" role="tablist">
					<li role="presentation" class="<?php echo ($selectedTabPosition == 'general') ? 'active' : '' ?>">
						<a href="#general" aria-controls="general" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_GENERAL_CONFIGURATION', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'user') ? 'active' : '' ?>">
						<a href="#user" aria-controls="user" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_USER', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'cattab') ? 'active' : '' ?>">
						<a href="#cattab" aria-controls="cattab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_CATEGORY_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'manufacturertab') ? 'active' : '' ?>">
						<a href="#manufacturertab" aria-controls="manufacturertab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_REDMANUFACTURER_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'producttab') ? 'active' : '' ?>">
						<a href="#producttab" aria-controls="producttab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'featuretab') ? 'active' : '' ?>">
						<a href="#featuretab" aria-controls="featuretab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_FEATURE_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'pricetab') ? 'active' : '' ?>">
						<a href="#pricetab" aria-controls="pricetab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_PRICE_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'carttab') ? 'active' : '' ?>">
						<a href="#carttab" aria-controls="carttab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_CART_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'ordertab') ? 'active' : '' ?>">
						<a href="#ordertab" aria-controls="ordertab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_ORDER_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'newslettertab') ? 'active' : '' ?>">
						<a href="#newslettertab" aria-controls="newslettertab" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_NEWSLETTER_TAB', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'integration') ? 'active' : '' ?>">
						<a href="#integration" aria-controls="integration" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_INTEGRATION', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'seo') ? 'active' : '' ?>">
						<a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_SEO', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'dashboard') ? 'active' : '' ?>">
						<a href="#dashboard" aria-controls="dashboard" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_DASHBOARD', true) ?>
						</a>
					</li>

					<li role="presentation" class="<?php echo ($selectedTabPosition == 'redshopabout') ? 'active' : '' ?>">
						<a href="#redshopabout" aria-controls="redshopabout" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_ABOUT', true) ?>
						</a>
					</li>
				  </ul>
				</div>
			</div>
		</div>
		<div class="col-sm-10">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'general') ? 'active' : '' ?>" id="general">
					<?php echo $this->loadTemplate('general'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'user') ? 'active' : '' ?>" id="user">
					<?php echo $this->loadTemplate('user'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'cattab') ? 'active' : '' ?>" id="cattab">
					<?php echo $this->loadTemplate('cattab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'manufacturertab') ? 'active' : '' ?>" id="manufacturertab">
					<?php echo $this->loadTemplate('manufacturertab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'producttab') ? 'active' : '' ?>" id="producttab">
					<?php echo $this->loadTemplate('producttab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'featuretab') ? 'active' : '' ?>" id="featuretab">
					<?php echo $this->loadTemplate('featuretab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'pricetab') ? 'active' : '' ?>" id="pricetab">
					<?php echo $this->loadTemplate('pricetab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'carttab') ? 'active' : '' ?>" id="carttab">
					<?php echo $this->loadTemplate('carttab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'ordertab') ? 'active' : '' ?>" id="ordertab">
					<?php echo $this->loadTemplate('ordertab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'newslettertab') ? 'active' : '' ?>" id="newslettertab">
					<?php echo $this->loadTemplate('newslettertab'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'integration') ? 'active' : '' ?>" id="integration">
					<?php echo $this->loadTemplate('integration'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'seo') ? 'active' : '' ?>" id="seo">
					<?php echo $this->loadTemplate('seo'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'dashboard') ? 'active' : '' ?>" id="dashboard">
					<?php echo $this->loadTemplate('dashboard'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'redshopabout') ? 'active' : '' ?>" id="redshopabout">
					<?php echo $this->loadTemplate('redshopabout'); ?>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="view" value="configuration"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="selectedTabPosition" value=""/>
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
