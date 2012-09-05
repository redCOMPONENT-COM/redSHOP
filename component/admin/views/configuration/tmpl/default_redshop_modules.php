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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<div id="config-document">
<table class="adminlist">
      <thead>
        <tr>
          <th><?php echo JText::_('COM_REDSHOP_CHECK'); ?></th>
          <th><?php echo JText::_('COM_REDSHOP_RESULT');?></th>
          <th><?php echo JText::_('COM_REDSHOP_PUBLISHED');?></th>
        </tr>
      </thead>
      <tbody>
      <?php if(count($this->getinstalledmodule) > 0 ){
      			foreach($this->getinstalledmodule as $getinstalledmodule){

      ?>
        <tr>
          <td><strong><?php echo $getinstalledmodule->element?></strong></td>
          <td><?php echo (is_null(JModuleHelper::getModule($getinstalledmodule->element)))?JText::_('COM_REDSHOP_NOT_INSTALLED'):JText::_('COM_REDSHOP_INSTALLED');?></td>

          <td align="center"><?php echo ($getinstalledmodule->enabled)? "<img src='../administrator/components/com_redshop/assets/images/tick.png' />" :"<img src='../administrator/components/com_redshop/assets//images/publish_x.png' />";?></td>

        </tr>
        	<?php }
      			}?>
      </tbody>
</table>
</div>

