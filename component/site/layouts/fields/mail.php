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
 * @var   array $displayData       A JForm object
 * @var   int   $extra_field_label Id current product
 * @var   int   $extra_field_value Flag use form in modal
 */
extract($displayData);

echo $extraFieldValue;
