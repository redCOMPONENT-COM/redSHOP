<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$user = JFactory::getUser();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (pressbutton == 'quotation.add') {
            form.view.value = "addquotation_detail";
            form.task.value = "add";
        }

        form.submit();
    }
</script>
<form action="index.php?option=com_redshop&view=quotations" class="admin" id="adminForm" method="post" name="adminForm">
    <div class="filterTool">
		<?php
		echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view'    => $this,
				'options' => array(
					'searchField'         => 'search',
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector'  => '#list_quotations_limit',
					'activeOrder'         => $listOrder,
					'activeDirection'     => $listDirn
				)
			)
		);
		?>
    </div>
	<?php if (empty($this->items)) : ?>
        <div class="alert alert-no-items alert-info">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
	<?php else : ?>
		<?php
		$productHelper = productHelper::getInstance();
		$config        = Redconfiguration::getInstance();
		?>
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="1">#</th>
                <th width="1">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
                </th>
                <th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_NUMBER', 'q.number', $listDirn, $listOrder) ?>
                </th>
                <th width="20%">
					<?php echo JText::_('COM_REDSHOP_FULLNAME') ?>
                </th>
                <th width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_STATUS', 'q.status', $listDirn, $listOrder) ?>
                </th>
                <th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TOTAL', 'q.total', $listDirn, $listOrder) ?>
                </th>
                <th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_DATE', 'q.quotation_cdate', $listDirn, $listOrder) ?>
                </th>
                <th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_ID', 'id', $listDirn, $listOrder); ?>
                </th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($this->items as $i => $row): ?>
                <tr>
                    <td align="center">
						<?php echo $this->pagination->getRowOffset($i) ?>
                    </td>
                    <td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id) ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_redshop&task=quotation.edit&id=' . $row->id) ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_QUOTATION') ?>">
							<?php echo $row->number ?>
                        </a>
                    </td>
                    <td>
						<?php if ($row->user_id): ?>
							<?php $userInfor = RedshopHelperUser::getUserInformation($row->user_id); ?>
							<?php if (!empty($userInfor)): ?>
								<?php echo $userInfor->firstname . ' ' . $userInfor->lastname ?>
								<?php echo ($userInfor->is_company && $userInfor->company_name) ? '<br />' . $userInfor->company_name : '' ?>
							<?php endif; ?>
						<?php else: ?>
							<?php echo $row->user_email ?>
						<?php endif; ?>
                    </td>
                    <td>
						<?php
						$status = RedshopHelperQuotation::getQuotationStatusName($row->status);
						$status .= ($row->status == 5) ? ' (' . JText::_('COM_REDSHOP_ORDER_ID') . '-' . $row->order_id . ')' : '';
						?>
						<?php echo $status ?>
                    </td>
                    <td>
						<?php echo $productHelper->getProductFormattedPrice($row->total) ?>
                    </td>
                    <td>
						<?php echo $config->convertDateFormat($row->quotation_cdate) ?>
                    </td>
                    <td align="right">
						<?php echo $row->id; ?>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
            <tfoot>
            <td colspan="8">
				<?php echo $this->pagination->getListFooter(); ?>
            </td>
            </tfoot>
        </table>
	<?php endif; ?>
    <input type="hidden" name="view" value="quotations">
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
