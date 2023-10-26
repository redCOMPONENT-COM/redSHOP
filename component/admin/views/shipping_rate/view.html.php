<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class RedshopViewShipping_rate extends RedshopViewAdmin
{
    public function display($tpl = null)
    {
        $context = 'shipping_rate';

        $uri = JUri::getInstance();
        $app = Factory::getApplication();

        $lists['order'] = $app->getUserStateFromRequest(
            $context . 'filter_order',
            'filter_order',
            'shipping_rate_id'
        );
        $lists['order_Dir'] = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $id = $app->getUserStateFromRequest($context . 'extension_id', 'extension_id', '0');

        if ((int) $id == 0) {
            Factory::getApplication()->enqueueMessage(Text::_('Direct Access not allowed'), 'warning');

            $app->redirect(
                Redshop\IO\Route::_(
                    JURI::base() . "index.php?option=com_redshop"
                )
            );
        }

        $shipping = RedshopHelperShipping::getShippingMethodById($id);

        $shipping_rates = $this->get('Data');
        $pagination = $this->get('Pagination');

        // Load language file of the shipping plugin
        JFactory::getLanguage()->load(
            'plg_redshop_shipping_' . strtolower($shipping->element),
            JPATH_ADMINISTRATOR
        );

        $plugin = JPluginHelper::getPlugin($shipping->folder, $shipping->element);
        $pluginParams = new JRegistry($plugin->params);

        $is_shipper = $pluginParams->get('is_shipper');
        $shipper_location = $pluginParams->get('shipper_location');

        $jtitle = ($shipper_location) ? Text::_('COM_REDSHOP_SHIPPING_LOCATION') : Text::_(
            'COM_REDSHOP_SHIPPING_RATE'
        );
        JToolBarHelper::title(
            $jtitle . ' <small><small>[ ' . Text::_($shipping->name) . ' ]</small></small>',
            'redshop_shipping_rates48'
        );
        JToolbarHelper::addNew();
        JToolbarHelper::EditList();

        if ($is_shipper) {
            JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', Text::_('COM_REDSHOP_TOOLBAR_COPY'), true);
        }

        JToolBarHelper::deleteList();
        JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));

        $this->lists = $lists;
        $this->shipping_rates = $shipping_rates;
        $this->shipping = $shipping;
        $this->pagination = $pagination;
        $this->is_shipper = $is_shipper;
        $this->shipper_location = $shipper_location;
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}