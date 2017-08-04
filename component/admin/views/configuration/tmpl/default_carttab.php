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

<div class="row adminform">
    <div class="col-sm-6">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_CART_SETTINGS'),
				'content' => $this->loadTemplate('cart_settings')
			)
		);
		?>
    </div>

    <div class="col-sm-6">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_OTHER_INFORMATION'),
				'content' => $this->loadTemplate('payment_ship_secure')
			)
		);
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_CART_IMAGE_SETTINGS'),
				'content' => $this->loadTemplate('cart_template_image_setting')
			)
		);
		?>
    </div>
</div>

