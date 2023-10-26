<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => Text::_('COM_REDSHOP_DEFAULT_CATEGORY_LBL'),
        'desc'  => Text::_('COM_REDSHOP_TOOLTIP_DEFAULT_CATEGORY_LBL'),
        'field' => $this->lists['product_default_category']
    )
);

echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => Text::_('COM_REDSHOP_SHOW_DISCONTINUED_PRODUCTS_LBL'),
        'desc'  => Text::_('COM_REDSHOP_TOOLTIP_SHOW_DISCONTINUED_PRODUCTS_LBL'),
        'field' => $this->lists['show_discontinued_products']
    )
);