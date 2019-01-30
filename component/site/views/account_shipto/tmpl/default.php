<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$input     = JFactory::getApplication()->input;
$itemId    = $input->getInt('Itemid', 0);
$isEdit    = $input->getInt('is_edit', 0);
$return    = $input->getString('return', "");
$addLink   = "index.php?option=com_redshop&view=account_shipto&task=addshipping&Itemid=" . $itemId;
$backLink  = "index.php?option=com_redshop&view=account&Itemid=" . $itemId;
$pageTitle = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL');
?>

<script type="text/javascript">
	<?php if ($isEdit == 1) : ?>
		setTimeout(function(){
			window.parent.location.href = '<?php echo JRoute::_("index.php?option=com_redshop&view=" . $return . "&Itemid=" . $itemId, false); ?>';
		}, 2000);

	<?php endif; ?>
</script>

<?php if ($this->params->get('show_page_heading', 1)): ?>
	<?php if ($this->params->get('page_title') != $pageTitle): ?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $this->escape(JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL')); ?>
		</h1>
	<?php else: ?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $pageTitle; ?></h1>
	<?php endif; ?>
<?php endif; ?>

<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES'); ?></legend>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
		<?php
		if (Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS')): ?>
			<tr>
				<td>- <?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS'); ?></td>
			</tr>
		<?php endif; ?>

		<?php if(!empty($this->shippingAddresses)): ?>
			<?php if (empty($isEdit) && empty($return)): ?>
				<?php for ($i = 0; $i < count($this->shippingAddresses); $i++): ?>
					<?php $editLink = "index.php?option=com_redshop&view=account_shipto&task=addshipping&infoid=" . $this->shippingAddresses[$i]->users_info_id . "&Itemid=" . $itemId; ?>
					<?php $deleteLink = "index.php?option=com_redshop&view=account_shipto&task=remove&infoid=" . $this->shippingAddresses[$i]->users_info_id . "&Itemid=" . $itemId; ?>
                    <tr>
                        <td>
                            - <?php echo "<a href='" . JRoute::_($editLink, false) . "'>" . $this->shippingAddresses[$i]->text . "</a>"; ?>
                        </td>
                        <td>
                            <a onclick="return confirm('<?php echo JText::_('COM_REDSHOP_DO_YOU_WANT_TO_DELETE') ?>');" href="<?php echo JRoute::_($deleteLink, false) ?>"><?php echo JText::_("JGLOBAL_FIELD_REMOVE") ?></a>
                        </td>
                    </tr>
				<?php endfor; ?>
			<?php else: ?>
				<?php for ($i = 0; $i < count($this->shippingAddresses); $i++): ?>
                    <tr>
                        <td>
                            - <?php echo $this->shippingAddresses[$i]->text; ?>
                        </td>
                        <td></td>
                    </tr>
				<?php endfor; ?>
			<?php endif; ?>
		<?php endif; ?>
		<?php if (empty($isEdit) && empty($return)): ?>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo JRoute::_($addLink, false); ?>">
                        <?php echo JText::_('COM_REDSHOP_ADD_ADDRESS'); ?></a>&nbsp;
                    <a href="<?php echo JRoute::_($backLink, false); ?>">
                        <?php echo JText::_('COM_REDSHOP_BACK'); ?></a>
                </td>
            </tr>
		<?php endif; ?>
	</table>
</fieldset>
