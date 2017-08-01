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
					'title'   => JText::_('COM_REDSHOP_SYSTEM_INFORMATION'),
					'content' => $this->loadTemplate('system_information')
				)
			);
			?>
        </div>
        <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_REDSHOP_MODULES'),
					'content' => $this->loadTemplate('redshop_modules')
				)
			);
			?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
	        <?php
	        echo RedshopLayoutHelper::render(
		        'config.group',
		        array(
			        'title'   => JText::_('COM_REDSHOP_REDSHOP_SHIPPING_PLUGINS'),
			        'content' => $this->loadTemplate('redshop_shipping')
		        )
	        );
	        ?>
        </div>
        <div class="col-sm-6">
	        <?php
	        echo RedshopLayoutHelper::render(
		        'config.group',
		        array(
			        'title'   => JText::_('COM_REDSHOP_REDSHOP_PAYMENT_PLUGINS'),
			        'content' => $this->loadTemplate('redshop_plugins')
		        )
	        );
	        ?>
        </div>
    </div>
</fieldset>
