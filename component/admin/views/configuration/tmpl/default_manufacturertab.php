<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
					'title'   => JText::_('COM_REDSHOP_MANUFACTURER_SETTINGS'),
					'content' => $this->loadTemplate('manufacturer_setting')
				)
			);
			?>
        </div>

        <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_MANUFACTURER_IMAGE_SETTINGS'),
					'content' => $this->loadTemplate('manufacturer_image_setting')
				)
			);
			?>
        </div>
    </div>
</fieldset>
