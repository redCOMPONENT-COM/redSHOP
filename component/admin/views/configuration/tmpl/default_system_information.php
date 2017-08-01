<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<div id="config-document">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo JText::_('COM_REDSHOP_CHECK'); ?></th>
                <th><?php echo JText::_('COM_REDSHOP_RESULT'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_WEB_SERVER'); ?></strong></td>
                <td><?php echo $this->server; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_PHP_VERSION'); ?></strong></td>
                <td><?php echo $this->php_version; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_MYSQL_VERSION'); ?></strong></td>
                <td><?php echo $this->db_version; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_GD_IMAGE_LIBRARY'); ?></strong></td>
                <td><?php if ($this->gd_check)
                    {
                        $gdinfo = gd_info();
                        echo $gdinfo["GD Version"];
                    }
                    else echo JText::_('COM_REDSHOP_Disabled'); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_MULTIBYTE_STRING_SUPPORT'); ?></strong></td>
                <td><?php if ($this->mb_check) echo JText::_('COM_REDSHOP_Enabled');
                    else echo JText::_('COM_REDSHOP_Disabled'); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_UPLOAD_LIMIT'); ?></strong></td>
                <td><?php echo ini_get('upload_max_filesize'); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_MEMORY_LIMIT'); ?></strong></td>
                <td><?php echo ini_get('memory_limit'); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_OPEN_REMOTE_FILES'); ?></strong></td>
                <td><?php echo (ini_get('allow_url_fopen')) ? JText::_('COM_REDSHOP_Yes') : JText::_('COM_REDSHOP_No'); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo JText::_('COM_REDSHOP_EXECUTION_TIME'); ?></strong></td>
                <td><?php echo(ini_get('max_execution_time')) ?></td>
            </tr>
        </tbody>
    </table>
</div>
