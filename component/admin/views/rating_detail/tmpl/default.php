<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


$editor = JFactory::getEditor();
JHTML::_('behavior.tooltip');
$user = JFactory::getUser();
$url = JURI::base();

$product_data = JRequest::getVar('product');
$model = $this->getModel('rating_detail');
$productHelper = productHelper::getInstance();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.comment.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_RATING_COMMENT_MUST_BE_FILLED', true ); ?>");
			return false;
		}

		if (form.product_id.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_RATING_MUST_SELECT_PRODUCT', true ); ?>");
			return false;
		}
		else
		{
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable table">
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
						<?php
						$uname = new stdClass;

						if (isset($this->detail->username))
						{
							if ($this->detail->userid == 0)
							{
								$uname->text = $this->detail->username;
							}
							else
							{
								$uname->text = $model->getuserfullname2($this->detail->userid);
							}

							$uname->value = $this->detail->userid;
						}

						echo JHTML::_('redshopselect.search', $uname, 'userid',
							array(
								'select2.ajaxOptions' => array('typeField' => ', user:1'),
							)
						);
						?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_RATING_USER'), JText::_('COM_REDSHOP_USER'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PRODUCT'); ?>:
					</td>
					<td><?php
						$productObject = new stdClass;
						$listAttributes = array();

						if ($product_data)
						{
							$productObject->text = $product_data->product_name;
							$productObject->value = $product_data->product_id;
							$listAttributes = array('disabled' => 'disabled');
						}
						elseif (isset($this->detail->product_id) && ($productInfo = $productHelper->getProductById($this->detail->product_id)))
						{
							$productObject->text = $productInfo->product_name;
							$productObject->value = $this->detail->product_id;
						}

						echo JHTML::_('redshopselect.search', $productObject, 'product_id',
							array(
								'select2.ajaxOptions' => array('typeField' => ', isproduct:1'),
								'list.attr' => $listAttributes
							)
						);
						?>
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
