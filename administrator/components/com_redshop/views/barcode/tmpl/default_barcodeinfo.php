<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
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
$url = JUri::base();

$order_id= JRequest::getVar('order_id');

?>
<div>
 <table class="adminlist">
 <tr>
    <td width="15%"><?php echo JText::_('COM_REDSHOP_NUMBER_OF_VIEWS'); ?> : </td>
    <td><a href='index.php?option=<?php echo $option?>&view=barcode&order_id=<?php echo $order_id?>&log=log'><?php echo $this->logData->log?></a></td>

 </tr>
 </table>

</div>
