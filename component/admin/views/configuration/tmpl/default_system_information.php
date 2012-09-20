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

        </tr>
      </thead>
      <tbody>
		        <tr>
		          <td><strong><?php echo JText::_('WEB_SERVER');?></strong></td>
		          <td><?php echo $this->server; ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('PHP_VERSION');?></strong></td>
		          <td><?php echo $this->php_version; ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('MYSQL_VERSION');?></strong></td>
		          <td><?php echo $this->db_version; ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('GD_IMAGE_LIBRARY');?></strong></td>
		          <td><?php if ($this->gd_check) {$gdinfo=gd_info(); echo $gdinfo["GD Version"];} else echo JText::_('Disabled'); ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('MULTIBYTE_STRING_SUPPORT');?></strong></td>
		          <td><?php if ($this->mb_check) echo JText::_('Enabled'); else echo JText::_('Disabled'); ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('UPLOAD_LIMIT');?></strong></td>
		          <td><?php echo ini_get('upload_max_filesize'); ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('MEMORY_LIMIT');?></strong></td>
		          <td><?php echo ini_get('memory_limit'); ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('OPEN_REMOTE_FILES');?></strong></td>
		          <td><?php echo (ini_get('allow_url_fopen'))? JText::_('Yes'):JText::_('No'); ?></td>
		        </tr>
		        <tr>
		          <td><strong><?php echo JText::_('EXECUTION_TIME');?></strong></td>
		          <td><?php echo (ini_get('max_execution_time')) ?></td>
		        </tr>
		      </tbody>
</table>
</div>