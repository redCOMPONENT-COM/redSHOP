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

JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url = $uri->root();
jimport('joomla.html.pane');
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        submitbutton(pressbutton);
    }

    submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'tax') {
            form.view.value = "tax";
        }
        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        if (form.tax_group_name.value == "") {
            alert("<?php echo JText::_('COM_REDSHOP_TAX_GROUP_MUST_HAVE_VALUE', true); ?>");
        } else {
            submitform(pressbutton);
        }
    }
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">

    <div class="col50">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

            <table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="name">
                            <?php echo JText::_('COM_REDSHOP_TAX_GROUP_NAME_LBL'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="tax_group_name" id="tax_group_name" size="10"
                               maxlength="100" value="<?php echo $this->detail->tax_group_name;?>"/>
                        <?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_GROUP_NAME_LBL'), JText::_('COM_REDSHOP_TAX_GROUP_NAME_LBL'), 'tooltip.png', '', '', false); ?>
                    </td>

                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
                            <?php echo JText::_('COM_REDSHOP_TAX_GROUP_PUBLISH_LBL'); ?>:
                        </label>
                    </td>
                    <td>
                        <?php echo $this->lists['published']; ?>
                        <?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_GROUP_PUBLISH_LBL'), JText::_('COM_REDSHOP_TAX_GROUP_PUBLISH_LBL'), 'tooltip.png', '', '', false); ?>
                    </td>
                </tr>

            </table>
        </fieldset>

    </div>

    <div class="clr"></div>
    <input type="hidden" name="tax_group_id" value="<?php echo $this->detail->tax_group_id; ?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="view" value="tax_group_detail"/>
</form>
