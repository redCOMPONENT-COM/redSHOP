<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Statistic Order view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.2
 */
class RedshopViewStatistic_Order extends RedshopViewAdmin
{
    /**
     * @var  array
     */
    public $lists;

    /**
     * Display the Statistic Customer view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        $document = JFactory::getDocument();
        $document->setTitle(Text::_('COM_REDSHOP_STATISTIC_ORDER'));

        $model = $this->getModel();

        $this->orders     = $model->getItems();
        $this->pagination = $model->getPagination();
        $this->state      = $model->getState();
        $this->filterForm = $model->getForm();

        $this->addToolbar();

        $app = JFactory::getApplication()->input;

        $filterOrderStatus   = $app->getString('filter_order_status', '');
        $filterPaymentStatus = $app->getString('filter_payment_status', '');
        $lists               = array();

        $lists['filter_order_status']   = RedshopHelperOrder::getStatusList(
            'filter_order_status',
            $filterOrderStatus,
            'class="inputbox" size="1" onchange="document.adminForm.submit();"'
        );
        $lists['filter_payment_status'] = RedshopHelperOrder::getPaymentStatusList(
            'filter_payment_status',
            $filterPaymentStatus,
            'class="inputbox" size="1" onchange="document.adminForm.submit();" '
        );

        $this->lists = $lists;

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $toolbar = JToolbar::getInstance();

        JToolBarHelper::title(Text::_('COM_REDSHOP_STATISTIC_ORDER'), 'statistic redshop_statistic48');

        RedshopToolbarHelper::custom(
            'exportOrder',
            'save.png',
            'save_f2.png',
            'COM_REDSHOP_EXPORT_DATA_LBL',
            false
        );

        $toolbar->linkButton('', 'COM_REDSHOP_PRINT')
            ->icon('fas fa-print')
            ->attributes(['target' => '_blank'])
            ->url(JRoute::_('index.php?tmpl=component&option=com_redshop&view=statistic_order'));
    }
}