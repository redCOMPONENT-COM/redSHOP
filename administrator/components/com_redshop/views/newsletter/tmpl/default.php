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
defined('_JEXEC') or die ('Restricted access');
$option = JRequest::getVar('option');
$filter = JRequest::getVar('filter');
$model  = $this->getModel('newsletter');
?>
<form action="<?php
echo 'index.php?option=' . $option;
?>" method="post"
      name="adminForm" id="adminForm">
    <div id="editcell">
        <table width="100%">
            <tr>
                <td valign="top" align="left" class="key">
                    <?php
                    echo JText::_('COM_REDSHOP_USER_FILTER');
                    ?>:
                    <input type="text" name="filter" id="filter" value="<?php echo $filter;    ?>"
                           onchange="document.adminForm.submit();">
                    <button onclick="this.form.submit();"><?php echo JText::_('COM_REDSHOP_GO');    ?></button>
                    <button
                        onclick="document.getElementById('filter').value='';this.form.submit();"><?php     echo JText::_('COM_REDSHOP_RESET');     ?></button>
                </td>
            </tr>
        </table>
        <table class="adminlist">
            <thead>
            <tr>
                <th width="5%"><?php echo JText::_('COM_REDSHOP_NUM');?></th>
                <th width="5%"><input type="checkbox" name="toggle"
                                      onclick="checkAll(<?php echo count($this->newsletters);?>);"/></th>
                <th width="25%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NEWSLETTER_NAME', 'name', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
                <th><?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUB');?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_NO_SUBSCRIBERS');?></th>
                <th width="5%"
                    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
                <th width="5%"
                    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'newsletter_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
            </tr>
            </thead>
            <?php
            $k = 0;
            for ($i = 0, $n = count($this->newsletters); $i < $n; $i++)
            {
                $row       = &$this->newsletters [$i];
                $row->id   = $row->newsletter_id;
                $link      = JRoute::_('index.php?option=' . $option . '&view=newsletter_detail&task=edit&cid[]=' . $row->newsletter_id);
                $published = JHTML::_('grid.published', $row, $i);    ?>
                <tr class="<?php echo "row$k";?>">
                    <td align="center"><?php echo $this->pagination->getRowOffset($i);?></td>
                    <td align="center"><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
                    <td><a href="<?php echo $link;?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_NEWSLETTER');?>"><?php echo $row->name;?></a>
                    </td>
                    <td><?php echo $row->subject;?></td>
                    <td align="center"><?php echo $model->noofsubscribers($row->newsletter_id);?></td>
                    <td align="center"><?php echo $published;?></td>
                    <td align="center"><?php echo $row->newsletter_id;?></td>
                </tr>
                <?php    $k = 1 - $k;
            }    ?>
            <tfoot>
            <td colspan="7"><?php echo $this->pagination->getListFooter();?></td>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="view" value="newsletter"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists ['order'];?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir'];?>"/>
</form>
