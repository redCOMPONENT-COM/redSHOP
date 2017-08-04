<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$dispatcher = RedshopHelperUtility::getDispatcher();
JPluginHelper::importPlugin('redshop_product');
/**
 * @var $data
 * Trigger event onAfterDisplayProduct
 * Show content return by plugin directly into product page after display product title
 */
$data = new stdClass;
$results = $dispatcher->trigger('onBackendOrderDetailFooter', array(& $this));
$data->loadhtml = trim(implode("\n", $results));

echo $data->loadhtml;
