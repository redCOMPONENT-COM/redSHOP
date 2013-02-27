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
JHTML::_('behavior.tooltip');
global $mainframe;
$option     = JRequest::getVar('option');
$Itemid     = JRequest::getVar('Itemid');
$loginlink  = 'index.php?option=' . $option . '&view=login&Itemid=' . $Itemid;
$mywishlist = JRequest::getVar('wishlist');
if ($mywishlist != '')
{
    $newuser_link = 'index.php?wishlist=' . $mywishlist . '&option=' . $option . '&view=registration&Itemid=' . $Itemid;
}
else
{
    $newuser_link = 'index.php?option=' . $option . '&view=registration&Itemid=' . $Itemid;
}
$forgotpwd_link = 'index.php?option=' . $option . '&view=password&Itemid=' . $Itemid;

$params       = $mainframe->getParams($option);
$returnitemid = $params->get('login', $Itemid);


?>
<form action="<?php echo JRoute::_($loginlink); ?>" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td colspan="5" height="40">
                <p><?php echo JText::_('COM_REDSHOP_LOGIN_DESCRIPTION'); ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <label for="username">
                    <?php echo JText::_('COM_REDSHOP_USERNAME'); ?>:
                </label>
            </td>
            <td>
                <input class="inputbox" type="text" id="username" name="username"/>
            </td>
            <td>
                <label for="password">
                    <?php echo JText::_('COM_REDSHOP_PASSWORD'); ?>:
                </label>
            </td>
            <td>
                <input class="inputbox" id="password" name="password" type="password"/>
            </td>

            <td><input type="submit" name="submit" class="button" value="<?php echo JText::_('COM_REDSHOP_LOGIN'); ?>">
            </td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">
                <a href="<?php echo JRoute::_($newuser_link); ?>"><?php echo JText::_('COM_REDSHOP_CREATE_USER_LINK'); ?></a>&nbsp;/&nbsp;<a
                href="<?php echo JRoute::_($forgotpwd_link); ?>"><?php echo JText::_('COM_REDSHOP_FORGOT_PWD_LINK'); ?></a>
            </td>
        </tr>
    </table>
    <input type="hidden" name="task" id="task" value="setlogin">
    <input type="hidden" name="mywishlist" id="mywishlist" value="<?php echo JRequest::getVar('wishlist');?>">
    <input type="hidden" name="returnitemid" id="returnitemid" value="<?php echo $returnitemid;?>">
    <input type="hidden" name="option" id="option" value="<?php echo $option;?>"/>
</form>
