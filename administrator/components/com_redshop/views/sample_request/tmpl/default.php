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
defined('_JEXEC') or die('Restricted access');
$config = new Redconfiguration();
$option = JRequest::getVar('option');

JHTMLBehavior::modal();
?>
<script language="javascript" type="text/javascript">
    function clearreset() {
        var form = document.adminForm;
        form.filter.value = "";
        form.submit();
    }
</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist">
            <thead>
            <tr>
                <th width="5%">
                    <?php echo JText::_('COM_REDSHOP_NUM'); ?>
                </th>
                <th width="5%">
                    <input type="checkbox" name="toggle" value=""
                           onclick="checkAll(<?php echo count($this->catalog); ?>);"/>
                </th>
                <th width="20%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NAME', 'name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="20%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_EMAIL', 'email', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="20%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_REGISTRATORDATE', 'registerDate', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="10%">
                    <?php echo JText::_('COM_REDSHOP_SAMPLE_DETAIL'); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_REMINDER_1', 'remider_1', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_REMINDER_2', 'remider_2', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_REMINDER_3', 'remider_3', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="5%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_BLOCK', 'block', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
            </tr>
            </thead>
            <?php
            $k = 0;
            for ($i = 0, $n = count($this->catalog); $i < $n; $i++)
            {
                $row            = &$this->catalog[$i];
                $row->id        = $row->request_id;
                $row->published = $row->block;
                $published      = JHtml::_('jgrid.published', $row->published, $i, '', 1);

                $reminder1 = JHtml::_('jgrid.published', $row->reminder_1, $i, '', 1);
                $reminder2 = JHtml::_('jgrid.published', $row->reminder_2, $i, '', 1);
                $reminder3 = JHtml::_('jgrid.published', $row->reminder_3, $i, '', 1);    ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
                    <td><?php echo  $row->name; ?></td>
                    <td><?php echo  $row->email; ?></td>
                    <td align="center"><?php echo $config->convertDateFormat($row->registerdate); ?></td>
                    <td align="center"><a class="modal"
                                          href="index.php?tmpl=component&option=<?php echo $option;?>&amp;view=sample_catalog&amp;cid[]=<?php echo $row->request_id;?>&amp;showbuttons=1"
                                          rel="{handler: 'iframe', size: {x: 400, y: 400}}" title="">
                        <?php echo JText::_('COM_REDSHOP_DETAIL'); ?></a>
                    </td>
                    <td align="center"><?php echo $reminder1;?></td>
                    <td align="center"><?php echo $reminder2;?></td>
                    <td align="center"><?php echo $reminder3;?></td>
                    <td align="center"><?php echo $published;?></td>
                </tr><?php
                $k = 1 - $k;
            }    ?>
            <tfoot>
            <td colspan="10">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="view" value="sample_request"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
