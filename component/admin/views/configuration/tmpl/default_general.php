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
<fieldset class="adminform">
    <div class="row">
        <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_STORE_SETTINGS'),
					'content' => $this->loadTemplate('settings')
				)
			);
			?>
        </div>

        <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_GENERAL_LAYOUT_SETTING'),
					'content' => $this->loadTemplate('general_layout_settings')
				)
			);

			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_MODULES_AND_FEATURES'),
					'content' => $this->loadTemplate('modules')
				)
			);
			?>
        </div>
    </div>
</fieldset>
