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
defined('_JEXEC') or die ('restricted access');
$user   = JFactory::getUser();
$option = JRequest::getVar('option');
$start  = $this->pagination->limitstart;
$end    = $this->pagination->limit;
?>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table width="100%">
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_FILTER') . ": " . $this->lists['filteroption'];?></td>
            </tr>
        </table>
        <!--<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_STATISTIC'); ?></legend>

	-->
        <table class="adminlist">
            <thead>
            <tr>
                <th width="60%" align="center"><?php if ($this->filteroption)
                {
                    echo JText::_('COM_REDSHOP_DATE');
                }
                else
                {
                    echo JText::_('COM_REDSHOP_HASH');
                }?></th>
                <th width="40%" align="center"><?php echo JText::_('COM_REDSHOP_TOTAL_VISITORS'); ?></th>
            </tr>
            </thead>
            <?php   for ($i = $start, $j = 0; $i < ($start + $end); $i++, $j++)
        {
            $row = &$this->redshopviewer[$i];
            if (!is_object($row))
            {
                break;
            }?>
            <tr>
                <td align="center"><?php if ($this->filteroption)
                {
                    echo $row->viewdate;
                }
                else
                {
                    echo JText::_('COM_REDSHOP_HASH');
                }?></td>
                <td align="center"><?php echo $row->viewer;?></td>
            </tr>
            <?php }?>
            <tfoot>
            <td colspan="2"><?php echo $this->pagination->getListFooter(); ?></td>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="view" value="statistic"/>
    <input type="hidden" name="layout" value="<?php echo $this->layout;?>"/>
    <input type="hidden" name="boxchecked" value="0"/>
</form>
