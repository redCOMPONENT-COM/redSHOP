<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
					'title'   => JText::_('COM_REDSHOP_REGISTRATION'),
					'content' => $this->loadTemplate('registration')
				)
			);
			?>
		</div>

		<div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_SHOPPER_GROUP_TAB'),
					'content' => $this->loadTemplate('shopper_group')
				)
			);

			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_CHECKOUT_REQUIRED_TAB'),
					'content' => $this->loadTemplate('checkout_required')
				)
			);
			?>
		</div>
	</div>
</fieldset>
