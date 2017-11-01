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
					'title'   => JText::_('COM_REDSHOP_MAIN_CATEGORY_SETTINGS'),
					'content' => $this->loadTemplate('category')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_NEXT_PREVIOUS'),
					'content' => $this->loadTemplate('cattab_nplinks')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_CATEGORY_SUFFIXES'),
					'content' => $this->loadTemplate('category_suffix')
				)
			);
			?>
        </div>

        <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_CATEGORY_TEMPLATE_TAB'),
					'content' => $this->loadTemplate('category_template')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_IMAGE_SETTINGS'),
					'content' => $this->loadTemplate('image_setting')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_DEFAULT_IMAGES'),
					'content' => $this->loadTemplate('procat_images')
				)
			);
			?>
        </div>
    </div>
</fieldset>
