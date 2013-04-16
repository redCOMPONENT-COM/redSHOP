<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

$uri = JURI::getInstance();
$url = $uri->root();
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<strong><?php echo JText::_('REDSHOP_CURRENT_VERSION'); ?></strong>
		</td>
		<td>
			<blink><?php echo $this->current_version;?></blink>
		</td>
	</tr>
	<tr valign="top">
		<td>
			<strong><?php echo JText::_('REDSHOP_LATEST_VERSION'); ?></strong>
		</td>
		<td>
			<blink><?php echo file_get_contents(REMOTE_UPDATE_DOMAIN_URL . 'version_checker/version_checker.txt', true);?></blink>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="2" align="right">
			<strong><?php echo JText::_('CLICK_HERE_TO_UPDATE'); ?></strong>
		</td>

	</tr>
</table>
