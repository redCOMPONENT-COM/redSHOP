<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_GLS_CUSTOMER_ID_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_GLS_CUSTOMER_ID_LBL'),
		'field' => '<input type="text" name="gls_customer_id" id="gls_customer_id"
            value="' . $this->config->get('GLS_CUSTOMER_ID') . '" class="form-control" />'
	)
);
