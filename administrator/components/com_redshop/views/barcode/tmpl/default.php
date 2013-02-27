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
$option = JRequest::getVar('option');
$url    = JUri::base();

$order_id = JRequest::getInt('order_id', 0);
$log      = JRequest::getVar('log');
?>

<script language=javascript>
    window.onload = function () {
        document.getElementById('barcode').focus();
    }
</script>


<form action="<?php echo 'index.php?option=' . $option; ?>&view=barcode" method="post" name="adminForm" id="adminForm">
    <div id="editcell" style="background-color: ">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_REDSHOP_BARCODE'); ?></legend>
            <table class="adminlist">
                <tr>
                    <td align="right" width="10%"><?php echo JText::_('COM_REDSHOP_BARCODE'); ?> :</td>
                    <td width="20%"><input type="text" name="barcode" value="" id="barcode"></td>
                    <td><span class="editlinktip hasTip"
                              title="<?php echo JText::_('COM_REDSHOP_BARCODE_TOOLTIP')?>"><input type="submit"
                                                                                                  name="searchbtn"
                                                                                                  value="<?php echo JText::_('COM_REDSHOP_CHECK_BARCODE');?>">  </span>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <input type="hidden" name="view" value="barcode"/>
    <input type="hidden" name="task" value="getsearch"/>
</form>

<?php if ($order_id != 0)
{ ?>
<div>
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_REDSHOP_BARCODE_INFO'); ?></legend>
        <?php echo $this->loadTemplate('barcodeinfo');?>
    </fieldset>
</div>
<?php if ($log)
{
    ?>
<div>
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_REDSHOP_BARCODE_INFO_LOG'); ?></legend>
        <?php echo $this->loadTemplate('barcodeloginfo');?>
    </fieldset>
</div>
<?php } ?>
<div id="framediv">
    <?php $link = JUri::base() . 'index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $order_id . '&tmpl=component';?>

    <iframe src="<?php echo $link;?>" width="900" height="800" style="border: 0px;"></iframe>
</div>
<?php } ?>
