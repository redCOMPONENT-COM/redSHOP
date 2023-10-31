<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('bootstrap.tooltip');
$uri = \Joomla\CMS\Uri\Uri::getInstance();
$url = $uri->root();

echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));