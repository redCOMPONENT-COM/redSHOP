<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$url = JURI::base();

$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
?>
<script type="text/javascript" language="javascript">
	function validate() {
		var form = document.adminForm;
		var flag = 0;

		var selection = document.adminForm.user_rating;

		for (i = 0; i < selection.length; i++) {
			if (selection[i].checked == true) {
				flag = 1;
			}
		}

		if (flag == 0) {
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_RATE_THE_PRODUCT'); ?>');
			return false;
		}
		else if (form.comment.value == "") {
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_COMMENT_ON_PRODUCT'); ?>');
			return false;
		}
		else
		{
			return true;
		}

	}
</script>
<?php
if ($this->params->get('show_page_heading', 1))
{
	?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php echo $this->escape($this->productinfo->product_name); ?>
	</div>
<?php
}
?>
<form action="<?php echo JRoute::_('index.php?option=' . $option) ?>" method="post" name="adminForm" id="adminForm">
	<table cellpadding="3" cellspacing="3" border="0" width="100%">
		<tr>
			<td colspan="2"><?php echo JText::_('COM_REDSHOP_WRITE_REVIEWFORM_HEADER_TEXT'); ?></td>
		</tr>
		<tr>
			<td valign="top" align="left" width="100">
				<label for="rating">
					<?php echo JText::_('COM_REDSHOP_RATING'); ?>:
				</label>
			</td>
			<td>
				<table cellpadding="3" cellspacing="0" border="0">
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_GOOD'); ?></td>
						<td align="center">
							<input type="radio" name="user_rating" id="user_rating0" value="0">
						</td>
						<td align="center">
							<input type="radio" name="user_rating" id="user_rating1" value="1">
						</td>
						<td align="center">
							<input type="radio" name="user_rating" id="user_rating2" value="2">
						</td>
						<td align="center">
							<input type="radio" name="user_rating" id="user_rating3" value="3">
						</td>
						<td align="center">
							<input type="radio" name="user_rating" id="user_rating4" value="4">
						</td>
						<td align="center">
							<input type="radio" name="user_rating" id="user_rating5" value="5">
						</td>
						<td><?php echo JText::_('COM_REDSHOP_EXCELLENT'); ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<label for="username">
					<?php echo JText::_('COM_REDSHOP_USER_FULLNAME'); ?>:
				</label>
			</td>
			<?php

			if ($this->userinfo->firstname != "")
			{
				$fullname = $this->userinfo->firstname . " " . $this->userinfo->lastname;
			}
			?>
		   <td colspan="8">
		   		<input type="text" class="inputbox" name="username" id="username" value="<?php echo $fullname ?>" readonly="readonly">
		   </td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<label for="rating_title">
					<?php echo JText::_('COM_REDSHOP_RATING_TITLE'); ?>:
				</label>
			</td>
			<td colspan="8">
				<input type="text" class="inputbox" name="title" id="title" value="" size="48">
			</td>
		</tr>
		<tr>
			<td valign="top" align="left">
				<label for="comment">
					<?php echo JText::_('COM_REDSHOP_COMMENT'); ?>:
				</label>
			</td>
			<td colspan="8">
				<textarea class="text_area" name="comment" id="comment" cols="60" rows="7"/></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="8"><input type="submit" name="submit"
			                       value="<?php echo JText::_('COM_REDSHOP_SEND_REVIEW'); ?>"
			                       onclick="return validate();"></td>
		</tr>
		<tr>
			<td colspan="2"><?php echo JText::_('COM_REDSHOP_WRITE_REVIEWFORM_FOOTER_TEXT'); ?></td>
		</tr>
	</table>
	<input type="hidden" name="view" value="product_rating"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="product_id" value="<?php echo $this->product_id ?>"/>
	<input type="hidden" name="category_id" value="<?php echo $this->category_id ?>"/>
	<input type="hidden" name="userid" value="<?php echo $this->user->id ?>"/>
	<input type="hidden" name="published" value="0"/>
	<input type="hidden" name="rate" value="<?php echo $this->rate ?>"/>
	<input type="hidden" name="time" value="<?php echo time() ?>"/>
	<input type="hidden" name="option" value="<?php echo $option ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid ?>"/>
</form>