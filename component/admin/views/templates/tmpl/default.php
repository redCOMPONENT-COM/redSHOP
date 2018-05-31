<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Layout\LayoutHelper;

defined('_JEXEC') or die;

echo LayoutHelper::render('view.list', array('data' => $this));
