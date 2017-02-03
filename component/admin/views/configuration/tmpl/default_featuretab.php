<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<div class="row adminform">
    <div class="col-sm-4">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_RATING'),
				'content' => $this->loadTemplate('rating_settings')
			)
		);
		?>
    </div>
    <div class="col-sm-4">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_COMPARISON_PRODUCT_TAB'),
				'content' => $this->loadTemplate('comparison_settings')
			)
		);
		?>
    </div>
    <div class="col-sm-4">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_STOCKROOM_TAB'),
				'content' => $this->loadTemplate('stockroom_settings')
			)
		);
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_IMPORT_EXPORT_TAB'),
				'content' => $this->loadTemplate('feature_import_export')
			)
		);
		?>
    </div>
</div>
