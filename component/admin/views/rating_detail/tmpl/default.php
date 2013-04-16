<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$option = JRequest::getVar('option');
$editor = JFactory::getEditor();
JHTML::_('behavior.tooltip');
$user = JFactory::getUser();
$url = JUri::base();

$product_data = JRequest::getVar('product');
$model = $this->getModel('rating_detail');
?>
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

		if (form.comment.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_RATING_COMMENT_MUST_BE_FILLED', true ); ?>");
			return false;
		}
		if (form.userid.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_RATING_MUST_SELECT_USER', true ); ?>");
			return false;
		}
		if (form.product_id.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_RATING_MUST_SELECT_PRODUCT', true ); ?>");
			return false;
		}
		else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_RATING_TITLE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="title" id="title" size="75" maxlength="250"
						       value="<?php echo $this->detail->title; ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_RATING'); ?>:
						</label>
					</td>
					<td>
						<table cellpadding="3" cellspacing="3" align="left">
							<tr>
								<td align="center">
									<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/5.gif" border="0"
									     align="absmiddle"><br/>
									<input type="radio" name="user_rating" id="user_rating5"
									       value="5" <?php if ($this->detail->user_rating == 5) echo "checked='checked'"; ?>>
								</td>
								<td align="center">
									<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/4.gif" border="0"
									     align="absmiddle"><br/>
									<input type="radio" name="user_rating" id="user_rating4"
									       value="4" <?php if ($this->detail->user_rating == 4) echo "checked='checked'"; ?>>
								</td>
								<td align="center">
									<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>/star_rating/3.gif" border="0"
									     align="absmiddle"><br/>
									<input type="radio" name="user_rating" id="user_rating3"
									       value="3" <?php if ($this->detail->user_rating == 3) echo "checked='checked'"; ?>>
								</td>
								<td align="center">
									<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/2.gif" border="0"
									     align="absmiddle"><br/>
									<input type="radio" name="user_rating" id="user_rating2"
									       value="2" <?php if ($this->detail->user_rating == 2) echo "checked='checked'"; ?>>
								</td>
								<td align="center">
									<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/1.gif" border="0"
									     align="absmiddle"><br/>
									<input type="radio" name="user_rating" id="user_rating1"
									       value="1" <?php if ($this->detail->user_rating == 1) echo "checked='checked'"; ?>>
								</td>
								<td align="center">
									<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/0.gif" border="0"
									     align="absmiddle"><br/>
									<input type="radio" name="user_rating" id="user_rating0"
									       value="0" <?php if ($this->detail->user_rating == 0) echo "checked='checked'"; ?>>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_COMMENT'); ?>:
						</label>
					</td>
					<td>
						<textarea class="text_area" name="comment" id="comment" cols="32"
						          rows="5"/><?php echo $this->detail->comment; ?></textarea>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_USER'); ?>:
					</td>
					<td>
						<input type="text" name="username" id="username"
						       value="<?php if (isset($this->detail->username)) echo $uname = $model->getuserfullname2($this->detail->userid); ?>"
						       size="75"/><input type="hidden" name="userid" id="userid"
						                         value="<?php echo $this->detail->userid; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_RATING_USER'), JText::_('COM_REDSHOP_USER'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PRODUCT'); ?>:
					</td>
					<td>
						<input type="text" size="75" name="product" id="product" value="<?php if ($product_data)
						{
							echo $product_data->product_name;
						}
						else
						{
							if ($this->detail->product_id) echo $this->detail->product_name;
						} ?>"
							<?php if ($product_data)
						{ ?>
							disabled="disabled"
						<?php }?>
							/><input type="hidden" name="product_id" id="product_id" value="<?php if ($product_data)
						{
							echo $product_data->product_id;
						}
						else
						{
							echo $this->detail->product_id;
						} ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_RATING_PRODUCT'), JText::_('COM_REDSHOP_PRODUCT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_FAVOURED'); ?>:
					</td>
					<td>
						<?php echo $this->lists['favoured']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="clr"></div>
	<?php
	if (JRequest::getVar('pid'))
		$pid = JRequest::getVar('pid');
	else
		$pid = $this->detail->product_id;
	?>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->rating_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="time" value="<?php echo time(); ?>"/>
	<input type="hidden" name="view" value="rating_detail"/>
</form>
<script type="text/javascript">

	var options = {
		script: "index.php?tmpl=component&&option=com_redshop&view=search&user=1&json=true&",
		varname: "input",
		json: true,
		shownoresults: false,
		callback: function (obj) {
			document.getElementById('userid').value = obj.id;
		}
	};
	var as_json = new bsn.AutoSuggest('username', options);

	var products = {
		script: "index.php?tmpl=component&&option=com_redshop&view=search&isproduct=1&json=true&",
		varname: "input",
		json: true,
		shownoresults: false,
		callback: function (obj) {
			document.getElementById('product_id').value = obj.id;
		}
	};
	var as_json = new bsn.AutoSuggest('product', products);

</script>
