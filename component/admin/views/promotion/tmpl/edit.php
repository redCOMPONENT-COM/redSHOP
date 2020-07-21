<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));
JPluginHelper::importPlugin('redshop_promotion');
$dispatcher = \RedshopHelperUtility::getDispatcher();
$layout = $dispatcher->trigger('onRenderBackEndLayout', [])[0];
echo $layout;

