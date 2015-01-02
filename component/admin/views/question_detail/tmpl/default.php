<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
JLoader::load('RedshopHelperProduct');

$document = JFactory::getDocument();
$producthelper = new producthelper;
$editor        = JFactory::getEditor();

?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {

		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.product_id.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT_NAME', true); ?>");
		} else {
			submitform(pressbutton);
		}
	}

	function deleteanswer() {
		submitform('removeanswer');
	}

	function sendanswer() {
		submitform('sendanswer');
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>:</td>
					<td>
						<?php
							$producthelper = new producthelper;
							$product       = $producthelper->getProductByID($this->detail->product_id);

							$productname   = "";

							if (count($product) > 0)
							{
								$productname = $product->product_name;
							}
						?>
						<?php
						$productObject = new stdClass;

						if ($this->detail->product_id && ($productData = $producthelper->getProductById($this->detail->product_id)))
						{
							$productObject->value = $this->detail->product_id;
							$productObject->text = $productData->product_name;
						}

						echo JHTML::_('redshopselect.search', $productObject, 'product_id',
							array(
								'select2.options' => array(
									'placeholder' => JText::_('COM_REDSHOP_PRODUCT_NAME')
								)
							)
						);
						?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?>:</td>
					<td><?php echo $this->detail->user_name; ?>
						<input type="hidden" name="user_name" id="user_name"
						       value="<?php echo $this->detail->user_name; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?>:</td>
					<td><?php echo $this->detail->user_email; ?>
						<input type="hidden" name="user_email" id="user_email"
						       value="<?php echo $this->detail->user_email; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_PHONE_NO'); ?>:</td>
					<td><input type="text" name="telephone" id="telephone" value="<?php echo $this->detail->telephone; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USER_ADRESS'); ?>:</td>
					<td><input type="text" name="address" id="address" value="<?php echo $this->detail->address; ?>"/>
					</td>
				</tr>

				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
					<td><?php echo $this->lists['published']; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_QUESTION'); ?></legend>
			<table class="admintable">
				<tr>
					<td><?php echo $editor->display("question", $this->detail->question, '$widthPx', '$heightPx', '100', '20', '1'); ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php
		$k = 0;
		$i = 0;
	?>
	<div class="col50" id='answerlists'>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_PREVIOUS_ANSWERS'); ?></legend>
			<table class="adminlist table table-striped">
				<thead>
				<tr class="row<?php echo $k; ?>">
					<th class="title">#</th>
					<th class="title"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_USER_NAME'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_USER_EMAIL'); ?></th>
					<th class="title"><?php echo JText::_('COM_REDSHOP_TIME') ?></th>
				</tr>
				</thead>
				<?php if (count($this->answers) > 0) : ?>

			<?php foreach ($this->answers as $answer) : ?>
				<tr class="row<?php echo $k; ?>">
					<td align="center"><?php echo $i + 1; ?></td>
					<td class="order"
					    width="5%"><?php echo JHTML::_('grid.id', $i, $answer->question_id, false, 'aid'); ?></td>
					<td><?php echo $answer->question; ?></td>
					<td><?php echo $answer->user_name; ?></td>
					<td><?php echo $answer->user_email; ?></td>
					<td align="center"><?php echo date("M d Y, h:i:s A", $answer->question_date); ?></td>
				</tr>
				<?php
					$i++;
					$k = 1 - $k;
				?>
			<?php endforeach; ?>
				<tr>
					<td colspan="6">
						<input type="button" name="btn_delete" id="btn_delete"
						       value="<?php echo JText::_('COM_REDSHOP_DELETE') ?>" onclick="deleteanswer();"/>
						<input type="button" name="btn_send" id="btn_send"
						       value="<?php echo JText::_('COM_REDSHOP_SEND') ?>" onclick="sendanswer();"/></td>
				</tr>
			<?php endif; ?>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_ANSWERS'); ?></legend>
			<table class="admintable">
				<tr>
					<td>
						<?php
							echo $editor->display("answer", '', '$widthPx', '$heightPx', '100', '20', '1');
						?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->question_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="question_detail"/>
</form>
