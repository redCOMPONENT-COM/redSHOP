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
    <div class="col-sm-4">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_MAIN_PRICE'),
				'content' => $this->loadTemplate('price')
			)
		);
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_GIFTCARD_IMAGE_SETTING_TAB'),
				'content' => $this->loadTemplate('images_giftcard')
			)
		);
		?>
    </div>
    <div class="col-sm-4">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_TAX_TAB'),
				'content' => $this->loadTemplate('vat')
			)
		);
		?>
    </div>
    <div class="col-sm-4">
		<?php
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_DISCOUNT_SETTING_TAB'),
				'content' => $this->loadTemplate('discount')
			)
		);
		echo RedshopLayoutHelper::render(
			'config.group',
			array(
				'title'   => JText::_('COM_REDSHOP_DISCOUNT_MAIL'),
				'content' => $this->loadTemplate('discount_mail')
			)
		);
		?>
    </div>
</div>
