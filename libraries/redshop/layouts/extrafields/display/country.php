<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @var  array   $displayData Layout data.
 * @var  integer $data        Extra field data
 */
extract($displayData);

echo Redshop\Entity\Country::getInstance((int)$data)->get('country_name');
