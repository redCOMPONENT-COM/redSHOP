<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Views.Account
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JPluginHelper::importPlugin('redshop_payment');
$dispatcher = \RedshopHelperUtility::getDispatcher();
$cards = $dispatcher->trigger('onListCreditCards', array());

if (empty($cards)) {
    JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_PAYMENT_NO_CREDIT_CARDS_PLUGIN_LIST_FOUND'),
        'warning');
}
