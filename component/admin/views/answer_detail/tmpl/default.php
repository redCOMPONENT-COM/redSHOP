<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );
$producthelper = new producthelper();

$option = JRequest::getVar('option');
$model = $this->getModel('answer_detail');
$editor =& JFactory::getEditor();
$uri = & JURI::getInstance ();
$url = $uri->root ();

$product = $producthelper->getProductById($this->qdetail->product_id);?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {

	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	submitform( pressbutton );
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

	<table class="admintable">
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'PRODUCT_NAME' ); ?>:</td>
		<td><?php echo $product->product_name;	?>
			<input type="hidden" name="product_id" id="product_id" value="<?php echo $this->qdetail->product_id;?>" /></td></tr>
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'QUESTION_OWNER_NAME' ); ?>:</td>
		<td><?php echo $this->qdetail->user_name;	?></td></tr>
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'QUESTION_OWNER_EMAIL' ); ?>:</td>
		<td><?php echo $this->qdetail->user_email;	?></td></tr>
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'USER_NAME' ); ?>:</td>
		<td><?php echo $this->detail->user_name;?>
			<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->detail->user_id;?>" />
			<input type="hidden" name="user_name" id="user_name" value="<?php echo $this->detail->user_name;?>" />
	<tr><td width="100" align="right" class="key"><?php echo JText::_( 'USER_EMAIL' ); ?>:</td>
		<td><?php echo $this->detail->user_email;?>
			<input type="hidden" name="user_email" id="user_email" value="<?php echo $this->detail->user_email;?>" />
	<tr><td valign="top" align="right" class="key"><?php echo JText::_( 'PUBLISHED' ); ?>:</td>
		<td><?php echo $this->lists['published']; ?></td></tr>
	</table>
	</fieldset>
</div>
<div class="col50">

</div>

<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'QUESTION' ); ?></legend>

		<table class="admintable">
		<tr><td><?php echo $this->qdetail->question;	?></td></tr>
		</table>
	</fieldset>
</div>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'ANSWERS' ); ?></legend>

		<table class="admintable">

		<tr><td><?php echo $editor->display("question",$this->detail->question,'$widthPx','$heightPx','100','20','1');	?></td></tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->question_id; ?>" />
<input type="hidden" name="parent_id" value="<?php echo $this->detail->parent_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="answer_detail" />
</form>