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

jimport('joomla.application.module.helper');

global $mainframe;
JHTML::_('behavior.tooltip');
$option = JRequest::getVar('option');

$user   = JFactory::getUser();
$params = $mainframe->getParams($option);

$Itemid = JRequest::getInt('Itemid');

$returnitemid = $params->get('logout', $Itemid);

// get redshop login module
$module = JModuleHelper::getModule('redshop_login');

// get redshop login module params
if ($module->params != "")
{
    $moduleparams = new JRegistry($module->params);

    // set return Itemid for logout
    $returnitemid = $moduleparams->get('logout', $returnitemid);
}

?>
<form action="<?php echo JRoute::_('index.php?option=' . $option . '&view=login'); ?>" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td>
                <h1><?php echo $this->ShopperGroupDetail[0]->shopper_group_name; ?></h1>
            </td>
        </tr>
        <tr>
            <td>
                <strong><?php echo $this->ShopperGroupDetail[0]->shopper_group_introtext; ?></strong>
            </td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" class="button" value="<?php echo JText::_('COM_REDSHOP_LOGOUT'); ?>">
            </td>
        </tr>
    </table>
    <input type="hidden" name="task" id="task" value="logout">
    <input type="hidden" name="logout" id="logout" value="<?php echo $returnitemid;?>">
</form>
