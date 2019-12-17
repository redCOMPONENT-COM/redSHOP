<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

$producthelper = productHelper::getInstance();
$uri           = JURI::getInstance();
$url           = $uri->root();

// For Add Media Detail
$jInput        = JFactory::getApplication()->input;
$showbuttons   = $jInput->getInt('showbuttons', 0);
$media_section = $jInput->getCmd('media_section', $this->state->get('filter_media_section', 0));
$section_id    = $jInput->getInt('section_id', 0);
$model         = $this->getModel('media');
$countTd       = 8;
$ordering      = ($this->lists['order'] == 'ordering');

$sectionadata           = array();
$sectiona_primary_image = "";
$section_name           = "";
$directory              = $media_section;

if ($showbuttons == 1)
{
	switch ($media_section)
	{
		case "product";
			$sectionadata           = Redshop::product((int) $section_id);
			$section_name           = isset($sectionadata->product_name) ? $sectionadata->product_name : '';
			$sectiona_primary_image = isset($sectionadata->product_full_image) ? $sectionadata->product_full_image : '';
			$directory              = $media_section;
			break;
		case "property";
			$sectionadata           = RedshopHelperProduct_Attribute::getAttributeProperties($section_id);
			$section_name           = $sectionadata[0]->property_name;
			$sectiona_primary_image = $sectionadata[0]->property_main_image;
			$directory              = 'property';
			break;
		case "subproperty";
			$sectionadata           = RedshopHelperProduct_Attribute::getAttributeSubProperties($section_id);
			$section_name           = $sectionadata[0]->subattribute_color_name;
			$sectiona_primary_image = $sectionadata[0]->subattribute_color_main_image;
			$directory              = 'subproperty';
			break;
	}
}

if ($showbuttons == 1)
{
	?>
    <fieldset>
        <div style="float: right">
            <button type="button" class="btn btn-small" onclick="Joomla.submitbutton('add');">
				<?php echo JText::_('COM_REDSHOP_ADD'); ?>
            </button>
            <button type="button" class="btn btn-small" onclick="Joomla.submitbutton('edit');">
				<?php echo JText::_('COM_REDSHOP_EDIT'); ?>
            </button><?php
			if ($media_section == 'product' || $media_section == 'property' || $media_section == 'subproperty')
			{
				?>
                <button type="button" class="btn btn-small" onclick="Joomla.submitbutton('setDefault');">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_MEDIA'); ?>
                </button><?php
			} ?>
            <button type="button" class="btn btn-small" onclick="Joomla.submitbutton('remove');">
				<?php echo JText::_('COM_REDSHOP_DELETE'); ?>
            </button>
            <button type="button" class="btn btn-small" onclick="Joomla.submitbutton('publish');">
				<?php echo JText::_('COM_REDSHOP_PUBLISH'); ?>
            </button>
            <button type="button" class="btn btn-small" onclick="Joomla.submitbutton('unpublish');">
				<?php echo JText::_('COM_REDSHOP_UNPUBLISH'); ?>
            </button>
            <button type="button" class="btn btn-small" onclick="window.parent.location.reload();">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
            </button>
        </div>
        <div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_MEDIA'); ?></div>
    </fieldset>
	<?php

	$action = 'index.php?tmpl=component&option=com_redshop';

	// End
}
else
{
	$action = 'index.php?option=com_redshop';
}
?>
<form action="<?php echo $action; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <div class="filterTool">
			<?php if ($showbuttons != 1): ?>
                <div class="filterItem">
					<?php echo JText::_('COM_REDSHOP_MEDIA_TYPE') . ': ' . $this->lists['type']; ?>
                </div>
                <div class="filterItem">
					<?php echo JText::_('COM_REDSHOP_MEDIA_SECTION') . ': ' . $this->lists['filter_media_section']; ?>
                </div>
                <div class="filterItem">
                    <button class="btn reset"
                            onclick="this.form.getElementById('media_type').value='0';this.form.getElementById('filter_media_section').value='0';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
                </div>
			<?php endif; ?>
        </div>
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="1"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
                <th width="1"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
                <th width="auto" class="title">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_NAME', 'media_name', $this->lists ['order_Dir'], $this->lists ['order']) ?>
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_TYPE', 'media_type', $this->lists ['order_Dir'], $this->lists ['order']) ?>
                </th>
				<?php if ($showbuttons == 1): ?>
					<?php $countTd++; ?>
                    <th width="10%"><?php echo JText::_('COM_REDSHOP_ADDITIONAL_DOWNLOAD_FILES') ?></th>
				<?php endif; ?>
                <th width="15%">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_ALTERNATE_TEXT', 'media_alternate_text', $this->lists ['order_Dir'], $this->lists ['order']) ?>
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_MEDIA_SECTION', 'media_section', $this->lists ['order_Dir'], $this->lists ['order']); ?>
                </th>
				<?php if ($showbuttons == 1 && ($media_section == 'product' || $media_section == 'property' || $media_section == 'subproperty')): ?>
					<?php $countTd++; ?>
                    <th width="5%" class="title"><?php echo JText::_('COM_REDSHOP_PRIMARY_MEDIA') ?></th>
				<?php endif; ?>

				<?php if ($showbuttons == 1): ?>
					<?php $countTd++; ?>
                    <th class="order">
						<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
						<?php if ($ordering): ?>
							<?php echo JHtml::_('grid.order', $this->media); ?>
						<?php endif; ?>
                    </th>
				<?php endif; ?>
                <th width="60" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists ['order_Dir'], $this->lists ['order']); ?>
                </th>
                <th width="60" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ID', 'media_id', $this->lists ['order_Dir'], $this->lists ['order']); ?>
                </th>
            </tr>
            </thead>
			<?php $k = 0; ?>

			<?php for ($i = 0, $n = count($this->media); $i < $n; $i++): ?>
			<?php
			$row = $this->media[$i];
			$row->id = $row->media_id;
			$published = JHtml::_('grid.published', $row, $i);
			?>

            <tr class="<?php echo "row$k"; ?>">
                <td align="center">
					<?php echo $this->pagination->getRowOffset($i) ?>
                </td>
                <td align="center">
					<?php echo JHtml::_('grid.id', $i, $row->id) ?>
                </td>
                <td>
					<?php if ($row->media_type == 'images' && in_array($row->media_section, array('manufacturer', 'category'))): ?>
						<?php
						$media     = RedshopEntityMediaImage::getInstance($row->media_id);
						$mediaFile = $media->generateThumb(100, 100);
						?>
                        <a class="joom-box img-thumbnail" href="<?php echo $media->getAbsImagePath() ?>"
                           title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE'); ?>"
                           rel="{handler: 'image', size: {}}">
                            <img src="<?php echo $mediaFile['abs'] ?>" /></a>
					<?php else: ?>
						<?php $filetype = strtolower(JFile::getExt(trim($row->media_name))); ?>
						<?php if (($filetype == 'png' || $filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif') && $row->media_type == 'images'): ?>
							<?php
							$media_img = $url . 'components/com_redshop/assets/' . $row->media_type . '/' . $row->media_section . '/' . trim($row->media_name);
							?>
                            <a class="joom-box img-thumbnail" href="<?php echo $media_img; ?>"
                               title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE'); ?>"
                               rel="{handler: 'image', size: {}}">
                                <img src="<?php echo $media_img ?>" height="50" width="50"/></a>
						<?php else: ?>
							<?php echo $row->media_name; ?>
						<?php endif; ?>
					<?php endif; ?>
				</td>
				<td align="center" class="order">
					<?php echo !empty($row->media_type) ? $row->media_type : 'document'; ?>
				</td>
				<?php if ($showbuttons == 1): ?>
					<td class="order">
						<?php if ($row->media_type == 'download'): ?>
							<?php $additionalfiles = $model->getAdditionalFiles($row->id); ?>
                            <a href="index.php?tmpl=component&option=com_redshop&view=media&layout=additionalfile&media_id=<?php echo $row->id; ?>&showbuttons=1"
                               class="joom-box" rel="{handler: 'iframe', size: {x: 1000, y: 400}}"
                               title="<?php echo JText::_('COM_REDSHOP_ADDITIONAL_DOWNLOAD_FILES') . '&nbsp;(' . count($additionalfiles) . ')'; ?>">
								<?php echo JText::_('COM_REDSHOP_ADDITIONAL_DOWNLOAD_FILES') . '&nbsp;(' . count($additionalfiles) . ')'; ?>
                            </a>
						<?php endif; ?>
                    </td>
				<?php endif; ?>
                <td class="order"><?php echo $row->media_alternate_text ?></td>
                <td class="order"><?php echo $row->media_section ?></td>
				<?php if ($showbuttons == 1 && ($media_section == 'product' || $media_section == 'property' || $media_section == 'subproperty')): ?>
                <td align="center">
					<?php
					$isDefault = trim($sectiona_primary_image) == trim($row->media_name);
					echo JHtml::_('jgrid.isdefault', $isDefault, $i, '', !$isDefault);
					?>
                </td>
                <?php endif; ?>
				<?php if ($showbuttons == 1): ?>
                    <td align="order">
						<?php if ($ordering): ?>
							<?php $orderDir = strtoupper($this->lists['order_Dir']); ?>
                            <div class="input-prepend">
								<?php if ($orderDir == 'ASC' || $orderDir == ''): ?>
                                    <span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderup'); ?></span>
                                    <span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderdown'); ?></span>
								<?php elseif ($orderDir == 'DESC'): ?>
                                    <span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderdown'); ?></span>
                                    <span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderup'); ?></span>
								<?php endif; ?>
                                <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>"
                                       class="width-20 text-area-order"/>
                            </div>
						<?php else: ?>
							<?php echo $row->ordering; ?>
						<?php endif; ?>
                    </td>
				<?php endif; ?>
                <td align="center"><?php echo $published; ?></td>
                <td align="center"><?php echo $row->media_id; ?></td>
            </tr>
			<?php $k = 1 - $k; ?>
            <?php endfor; ?>
            <input type="hidden" name="showbuttons" value="<?php echo $showbuttons; ?>"/>
            <input type="hidden" name="section_id" value="<?php echo $section_id; ?>"/>
            <input type="hidden" name="section_name" value="<?php echo $section_name; ?>"/>
			<?php if ($showbuttons == 1): ?>
            <input type="hidden" name="media_section" value="<?php echo $media_section; ?>"/>
			<?php endif; ?>
            <tfoot>
            <tr>
                <td colspan="<?php echo $countTd; ?>">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
                        <div class="redShopLimitBox">
							<?php echo $this->pagination->getLimitBox(); ?>
                        </div>
					<?php endif; ?>
					<?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="view" value="media"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>"/>
</form>
<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        submitbutton(pressbutton);
    };
    submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton) {
            form.task.value = pressbutton;
        }
        if (pressbutton == 'add' || pressbutton == 'edit' || pressbutton == 'remove' || pressbutton == 'copy' || pressbutton == 'edit'
            || pressbutton == 'saveorder' || pressbutton == 'orderup' || pressbutton == 'orderdown') {
            form.view.value = "media_detail";
        }
        if (pressbutton == 'add')
        {
            form.submit();
        }
        else if (!$("input[type='checkbox'][id^='cb'][name^='cid']:checked").length)
        {
            alert("<?php echo JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST') ?>");
        }
        else {
            form.submit();
        }
    }
</script>
