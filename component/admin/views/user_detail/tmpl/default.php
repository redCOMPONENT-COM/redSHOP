<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$this->producthelper   = productHelper::getInstance();
$this->order_functions = order_functions::getInstance();
$this->config          = Redconfiguration::getInstance();
$this->model           = $this->getModel('user_detail');
$this->flag            = JRequest::getVar('flag', '', 'request', 'string');
$this->shipping        = JRequest::getVar('shipping', '', 'request', 'string');
$cancel                = JRequest::getVar('cancel', '', 'cancel', 'string');
$this->silerntuser     = ($this->detail->users_info_id) ? true : false;

if ($this->detail->users_info_id && $this->detail->user_id && $this->detail->username)
{
	$this->silerntuser = false;
}

/*
 set selected tab index
 0 default
 2 for shipping
 3 for order
 */
$tab = 0;

if ($cancel)
{
	$tab = 2;
}

if ($this->pagination->limitstart > 0)
{
	$tab = 3;
}
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (pressbutton == 'order') {
			submitform(pressbutton);
			return;
		}

		var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");

		<?php if ($this->shipping) : ?>
			if ((form.firstname.value) == "") {
				alert("<?php echo JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME', true );?>");
			} else if (form.lastname.value == "") {
				alert("<?php echo JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME', true );?>");
			} else {
				submitform(pressbutton);
			}
		<?php else: ?>
			if ((form.email.value) == "") {
				alert("<?php echo JText::_('COM_REDSHOP_PROVIDE_EMAIL_ADDRESS', true );?>");
				return false;
			}
			else if (email_valid == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_EMAIL_NOT_AVAILABLE', true );?>");
				return false;
			}
		<?php endif; ?>

		<?php if(!$this->silerntuser) : ?>
			if (form.username.value == "") {
				alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME', true );?>");
				return false;
			} else if (r.exec(form.username.value) || form.username.value.length < 2) {
				alert("<?php echo JText::_('COM_REDSHOP_WARNLOGININVALID', true );?>");
				return false;
			} else if (document.getElementById('user_valid').style.color == "red") {
				alert("<?php echo JText::_('COM_REDSHOP_USERNAME_NOT_AVAILABLE', true );?>");
				return false;

			} else if ((((form.password.value) != "") || (form.password2.value != "")) && (form.password.value != form.password2.value)) {
				alert("<?php echo JText::_('COM_REDSHOP_PASSWORD_NOT_MATCH', true );?>");
				return false;
			}
		<?php endif; ?>

		var chks       = document.getElementsByName('groups[]');

		if (chks.length)
		{
			var checkCount = 0;

			for (var i = 0; i < chks.length; i++){
				if (chks[i].checked){
					checkCount++;
				}
			}

			if (checkCount == 0){
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_USER_GROUP', true );?>");
				return false;
			}
		}

		// Added Rule for shopper group
		if (form.shopper_group_id.value == 0)
		{
			alert("<?php echo JText::_('COM_REDSHOP_SELECT_SHOPPER_GROUP', true );?>");
			return false;
		}

		if (form.firstname.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME', true );?>");
			return false;
		}
		if (form.lastname.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME', true );?>");
			return false;
		}

		if (document.getElementById('is_company1').checked) {
			if (form.company_name.value == "") {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME', true );?>");
				return false;
			}
		}

		submitform(pressbutton);
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<?php
	echo JHtml::_('tabs.start', 'user-pane', array('startOffset' => $tab));

	if (!$this->shipping)
	{
		// Create 1st Tab
		echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_GENERAL_USER_INFO'), 'tab1');
		echo $this->loadTemplate('user');
	}

	$title = ($this->shipping == 1) ? JText::_('COM_REDSHOP_SHIPPING_INFORMATION') : JText::_('COM_REDSHOP_BILLING_INFORMATION');
	echo JHtml::_('tabs.panel', $title, 'tab2');
	echo $this->loadTemplate('billing');

	if (!$this->shipping && $this->detail->user_id != 0 || $cancel == 1)
	{
		echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_SHIPPING_INFORMATION'), 'tab3');
		echo $this->loadTemplate('shipping');
	}

	$this->userorders = $this->model->userOrders();

	if ($this->detail->user_id && count($this->userorders) > 0)
	{
		echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_ORDER_INFORMATION'), 'tab4');
		echo $this->loadTemplate('order');
	}

	if ($this->lists['extra_field'] != "")
	{
		echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_EXTRA_FIELD'), 'tab5');    ?>
		<div class="col50"><?php echo $this->lists ['extra_field']; ?></div><?php
	}
	else
	{
		echo '<input type="hidden" name="noextra_field" value="1">';
		echo '<input type="hidden" name="tab5" value="tab5">';
	}

	echo JHtml::_('tabs.end');

	if ($this->shipping)
	{
		$info_id = JRequest::getVar('info_id', '', 'request', 'string');
		echo '<input type="hidden" name="address_type" id="address_type" value="ST"  />
		<input type="hidden" name="shipping" value="' . $this->shipping . '" />
		<input type="hidden" name="info_id" value="' . $info_id . '" />';
	}
	else
	{
		echo '<input type="hidden" name="address_type" id="address_type" value="BT"  />';
	}
?>
	<div class="clr"></div>
	<input type="hidden" name="user_id" value="<?php echo $this->detail->user_id; ?>"/>
	<input type="hidden" name="users_info_id" value="<?php echo $this->detail->users_info_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="user_detail"/>
</form>
