<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => JText::_('COM_REDSHOP_DEFAULT_CHECKOUT_REQUIRED_POSTAL_CODE_LBL'),
        'desc'  => '',
        'field' => $this->lists['required_postal_code']
    )
);

echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => JText::_('COM_REDSHOP_DEFAULT_CHECKOUT_REQUIRED_EAN_NUMBER_LBL'),
        'desc'  => '',
        'field' => $this->lists['required_ean_number']
    )
);

echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => JText::_('COM_REDSHOP_DEFAULT_CHECKOUT_REQUIRED_ADDRESS_LBL'),
        'desc'  => '',
        'field' => $this->lists['required_address']
    )
);

echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => JText::_('COM_REDSHOP_DEFAULT_CHECKOUT_REQUIRED_COUNTRY_CODE_LBL'),
        'desc'  => '',
        'field' => $this->lists['required_country_code']
    )
);

echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => JText::_('COM_REDSHOP_DEFAULT_CHECKOUT_REQUIRED_PHONE_LBL'),
        'desc'  => '',
        'field' => $this->lists['required_phone']
    )
);