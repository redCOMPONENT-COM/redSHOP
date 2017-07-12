<?php
/**
 * RedShops defines file.
 * Declared required defines for redSHOP
 *
 * @package    RedShopb.Library
 * @copyright  Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

// Define redSHOP Library Folder Path
define('JPATH_REDSHOP_LIBRARY', __DIR__);

// Define redSHOP Constant
define('JPATH_REDSHOP_TEMPLATE', JPATH_SITE . "/components/com_redshop/templates");
define('JSYSTEM_IMAGES_PATH', JUri::root() . 'media/system/images/');
define('REDSHOP_ADMIN_IMAGES_ABSPATH', JUri::root() . 'administrator/components/com_redshop/assets/images/');
define('REDSHOP_FRONT_IMAGES_ABSPATH', JUri::root() . 'components/com_redshop/assets/images/');
define('REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/images/');
define('REDSHOP_FRONT_DOCUMENT_ABSPATH', JUri::root() . 'components/com_redshop/assets/document/');
define('REDSHOP_FRONT_DOCUMENT_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/document/');

// Define order status
define('REDSHOP_ORDER_STATUS_PAID', 'P');

// Define order payment status
define('REDSHOP_ORDER_PAYMENT_STATUS_UNPAID', 'Unpaid');
