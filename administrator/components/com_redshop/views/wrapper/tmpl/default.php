<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
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
JHTMLBehavior::modal();
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');
$producthelper = new producthelper();

$showall = JRequest::getVar('showall', '0');
$page    = "";
$option  = JRequest::getVar('option', '', 'request', 'string');
$uri     = JURI::getInstance();
$url     = $uri->root();?>
<?php if ($showall)
{
    $page = "3";?>
<fieldset>
    <div style="float: right">
        <button type="button" onclick="Joomla.submitbutton('add');">
            <?php echo JText::_('COM_REDSHOP_ADD'); ?>
        </button>
        <button type="button" onclick="Joomla.submitbutton('edit');">
            <?php echo JText::_('COM_REDSHOP_EDIT'); ?>
        </button>
        <button type="button" onclick="Joomla.submitbutton('remove');">
            <?php echo JText::_('COM_REDSHOP_DELETE'); ?>
        </button>
        <button type="button" onclick="window.parent.location.reload();">
            <?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
        </button>
    </div>
    <div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_WRAPPER'); ?></div>
</fieldset>
<?php } ?>
<form action="<?php echo 'index' . $page . '.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist" width="100%">
            <thead>
            <tr>
                <th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
                <th width="5%"><input type="checkbox" name="toggle"
                                      onclick="checkAll(<?php echo count($this->data); ?>);"/></th>
                <th width="20%"><?php echo JText::_('COM_REDSHOP_WRAPPER_NAME'); ?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_WRAPPER_IMAGE'); ?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_WRAPPER_PRICE'); ?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_USE_TO_ALL_PRODUCT'); ?></th>
                <th width="5%"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?></th>
                <th width="5%"><?php echo JText::_('COM_REDSHOP_ID'); ?></th>
            </tr>
            </thead>
            <?php    $k = 0;
            for ($i = 0; $i < count($this->data); $i++)
            {
                $row            = &$this->data[$i];
                $row->id        = $row->wrapper_id;
                $published      = JHtml::_('jgrid.published', $row->published, $i, '', 1);
                $row->published = $row->wrapper_use_to_all;
                $enable_default = JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'enable_default');

                $link = JRoute::_('index' . $page . '.php?option=' . $option . '&view=wrapper_detail&task=edit&product_id=' . $this->product_id . '&cid[]=' . $row->wrapper_id . '&showall=' . $showall);?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td align="center"><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
                    <td><a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_WRAPPER'); ?>"><?php echo $row->wrapper_name;?></a>
                    </td>
                    <td>
                        <?php $wimage_path = 'wrapper/' . $row->wrapper_image;
                        if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $wimage_path))
                        {
                            ?>
                            <a class="modal" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $wimage_path;?>"
                               title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE');?>"
                               rel="{handler: 'image', size: {}}">
                                <?php echo $row->wrapper_image;?></a>
                            <?php }    ?>
                    </td>
                    <td align="center"><?php echo $producthelper->getProductFormattedPrice($row->wrapper_price);//CURRENCY_SYMBOL.number_format($row->wrapper_price,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR); ?></td>
                    <td align="center"><?php echo $enable_default; ?></td>
                    <td align="center"><?php echo $published;?></td>
                    <td align="center"><?php echo $row->id; ?></td>
                </tr>
                <?php        $k = 1 - $k;
            }    ?>
            <tfoot>
            <td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="view" value="wrapper"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="product_id" value="<?php echo $this->product_id;?>"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="showall" value="<?php echo $showall;?>"/>
</form>
