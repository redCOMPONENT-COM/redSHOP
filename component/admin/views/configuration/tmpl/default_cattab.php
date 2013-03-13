<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<div id="config-document">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_CATEGORIES'); ?></legend>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td width="50%">
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('category');?>
					</fieldset>
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('cateory_suffix');?>
					</fieldset>
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('cattab_nplinks');?>
					</fieldset>
				</td>
				<td width="50%">

					<fieldset class="adminform">
						<?php echo $this->loadTemplate('category_template');?>
					</fieldset>
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('image_setting');?>
					</fieldset>
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('procat_images');?>
					</fieldset>
				</td>
			</tr>
		</table>
	</fieldset>
</div>