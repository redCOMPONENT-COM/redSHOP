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
          <th><?php echo JText::_('CHECK'); ?></th>
          <th><?php echo JText::_('RESULT');?></th>
          <th><?php echo JText::_('PUBLISHED');?></th>
        </tr>
      </thead>
      <tbody>
      <?php if(count($this->getinstalledplugins) >0 ){
      			foreach($this->getinstalledplugins as $getinstalledplugins){
      ?>
        <tr>
          <td><strong><?php echo $getinstalledplugins->name?></strong></td>
          <td><?php echo (JFile::exists(JPATH_PLUGINS.DS.'redshop_payment'.DS. $getinstalledplugins->element.'.php'))?JText::_('INSTALLED'):JText::_('NOT_INSTALLED');?></td>
          <td align="center"><?php echo ($getinstalledplugins->published)? "<img src='images/tick.png' />" :"<img src='images/publish_x.png' />";?></td>
        </tr>
        	<?php }
      			}?>
      </tbody>
</table>
</div>