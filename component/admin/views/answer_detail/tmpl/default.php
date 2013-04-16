<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper();

$option = JRequest::getVar('option');
$model = $this->getModel('answer_detail');
$editor = JFactory::getEditor();
$uri = JURI::getInstance();
$url = $uri->root();

$product = $producthelper->getProductById($this->qdetail->product_id);?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {

		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		submitform(pressbutton);
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>:</td>
					<td><?php echo $product->product_name;    ?>
						<input type="hidden" name="product_id" id="product_id"
						       value="<?php echo $this->qdetail->product_id; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_QUESTION_OWNER_NAME'); ?>
						:
					</td>
					<td><?php echo $this->qdetail->user_name;    ?></td>
				</tr>
				<tr>
					<td width="100" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_QUESTION_OWNER_EMAIL'); ?>:
					</td>
					<td><?php echo $this->qdetail->user_email;    ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?>:</td>
					<td><?php echo $this->detail->user_name;?>
						<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->detail->user_id; ?>"/>
						<input type="hidden" name="user_name" id="user_name"
						       value="<?php echo $this->detail->user_name; ?>"/>
						<tr>
							<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?>
								:
							</td>
							<td><?php echo $this->detail->user_email;?>
								<input type="hidden" name="user_email" id="user_email"
								       value="<?php echo $this->detail->user_email; ?>"/>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
					<td><?php echo $this->lists['published']; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50">

	</div>

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_QUESTION'); ?></legend>

			<table class="admintable">
				<tr>
					<td><?php echo $this->qdetail->question;    ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></legend>

			<table class="admintable">

				<tr>
					<td><?php echo $editor->display("question", $this->detail->question, '$widthPx', '$heightPx', '100', '20', '1');    ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->question_id; ?>"/>
	<input type="hidden" name="parent_id" value="<?php echo $this->detail->parent_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="answer_detail"/>
</form>
