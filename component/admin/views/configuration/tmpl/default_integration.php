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
        <div class="col-sm-4">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_CONFIG_GLS'),
					'content' => $this->loadTemplate('gls')
				)
			);
			?>
        </div>
        <div class="col-sm-4">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_POST_DENMART'),
					'content' => $this->loadTemplate('postdk')
				)
			);
			?>
        </div>
        <div class="col-sm-4">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_ECONOMIC'),
					'content' => $this->loadTemplate('economic')
				)
			);
			?>
        </div>
    </div>
</fieldset>
