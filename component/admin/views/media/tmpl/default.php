<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form
	action="index.php?option=com_redshop&view=media"
	class="admin"
	id="adminForm"
	method="post"
	name="adminForm">
	<div class="filterTool">
		<?php echo RedshopLayoutHelper::render(
			'searchtools.default',
			array(
				'view' => $this,
				'options' => array(
					'searchField' => 'search',
					'searchFieldSelector' => '#filter_search',
					'limitFieldSelector' => '#list_users_limit',
					'activeOrder' => $listOrder,
					'activeDirection' => $listDirn,
				)
			)
		) ?>
	</div>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
	<table class="adminlist table table-striped table-hover">
		<thead>
		<tr>
			<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
			<th width="10">
				<?php echo JHtml::_('redshopgrid.checkall'); ?>
			</th>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_NAME', 'name', $listDirn, $listOrder); ?>
            <th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_TITLE', 'title', $listDirn, $listOrder); ?>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_ALTERNATE_TEXT', 'alternate_text', $listDirn, $listOrder); ?>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_SECTION', 'section', $listDirn, $listOrder); ?>
			<th><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_TYPE', 'type', $listDirn, $listOrder); ?>
			<th width="1"><?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ID', 'id', $listDirn, $listOrder); ?>
		</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;

		for ($i = 0, $n = count($this->items); $i < $n; $i++)
		{
			$row  = $this->items[$i];
			$url = 'index.php?option=com_redshop&tmpl=' . $this->tmpl . '&task=medium.edit&id=' . $row->id;
			$link = JRoute::_($url . '&return=' . base64_encode($url));
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
                <td>
                    <?php switch ($row->type): case 'images': ?>
                            <a href="<?php echo $link; ?>"
                               title="<?php echo JText::_('COM_REDSHOP_EDIT_MEDIA'); ?>">
                                <img src="<?php echo JUri::root() . 'media/com_redshop/files/' . $row->section . '/' . $row->id . '/' . $row->name ?>"
                                     alt="<?php echo $row->alternate_text ?>" style="width:100px;"/>
                            </a>
                            <?php break; ?>
                        <?php case 'youtube': ?>
                            <a href="<?php echo $link; ?>"
                               title="<?php echo JText::_('COM_REDSHOP_EDIT_MEDIA'); ?>">
                                <img src="<?php echo JUri::root() . 'media/com_redshop/images/youtube.jpg'; ?>"
                                     alt="<?php echo $row->alternate_text ?>" style="width:50px;"/>
                            </a>
                            <?php break; ?>
                        <?php default: ?>
                            <a href="<?php echo $link; ?>">
                                <?php echo $row->name ?>
                            </a>
                            <?php break;?>
                    <?php endswitch; ?>
				</td>
                <td align="center"><?php echo $row->title ?></td>
				<td align="center" width="5%"><?php echo $row->alternate_text ?></td>
				<td align="center" width="10%"><?php echo $row->section ?></td>
				<td align="center" width="10%"><?php echo $row->type ?></td>
				<td align="right"><?php echo $row->id ?></td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
		<tfoot>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
		</tfoot>
	</table>
	<?php endif; ?>
    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" name="view" value="media"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
</form>


