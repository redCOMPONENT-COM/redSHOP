<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Order List View
 *
 * @package     RedShop.Backend
 * @subpackage  Views
 * @since       1.0
 */
class RedshopViewOrder extends RedshopViewAdmin
{
	/**
	 * @var  array
	 */
	public $filter;

	/**
	 * @var  array
	 */
	public $lists;

	/**
	 * @var  JUser
	 */
	public $user;

	/**
	 * @var  array
	 */
	public $orders;

	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  string
	 */
	public $request_url;

	/**
	 * @var  boolean
	 *
	 * @since  2.0.6
	 */
	protected $useUserPermission = true;

	/**
	 * @var  boolean
	 */
	public $canView;

	/**
	 * @var  boolean
	 */
	public $canEdit;

	/**
	 * @var  boolean
	 */
	public $canDelete;

	/**
	 * @var  boolean
	 */
	public $canCreate;


	/**
	 * Display method
	 *
	 * @param   string  $tpl  The template name
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->generatePermission();
		$this->checkPermission();
		$this->addToolbar();

		$state                 = $this->get('State');
		$this->filter          = $state->get('filter');
		$filter_by             = $state->get('filter_by');
		$filter_status         = $state->get('filter_status');
		$filter_payment_status = $state->get('filter_payment_status');

		$lists['order']     = $state->get('list.ordering', 'o.order_id');
		$lists['order_Dir'] = $state->get('list.direction', 'desc');

		$lists['filter_by'] = RedshopHelperOrder::getFilterByList('filter_by', $filter_by,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"'
		);

		$lists['filter_status']         = RedshopHelperOrder::getStatusList('filter_status', $filter_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();"'
		);
		$lists['filter_payment_status'] = RedshopHelperOrder::getPaymentStatusList('filter_payment_status', $filter_payment_status,
			'class="inputbox" size="1" onchange="document.adminForm.submit();" '
		);

		$this->user        = JFactory::getUser();
		$this->lists       = $lists;
		$this->orders      = $this->get('Data');
		$this->pagination  = $this->get('Pagination');
		$this->request_url = JUri::getInstance()->toString();

		parent::display($tpl);
	}

	/**
	 * Method for generate 4 normal permission.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function generatePermission()
	{
		if (!$this->useUserPermission)
		{
			return;
		}

		$this->canCreate = \RedshopHelperAccess::canCreate('order');
		$this->canView   = \RedshopHelperAccess::canView('order');
		$this->canEdit   = \RedshopHelperAccess::canEdit('order');
		$this->canDelete = \RedshopHelperAccess::canDelete('order');
	}

	/**
	 * Method for check permission of current user on view
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 *
	 * @throws  Exception
	 */
	protected function checkPermission()
	{
		if (!$this->useUserPermission)
		{
			return;
		}

		// Check permission on create new
		if (!$this->canView)
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_REDSHOP_ACCESS_ERROR_NOT_HAVE_PERMISSION'), 'error');
			$app->redirect('index.php?option=com_redshop');
		}
	}

	protected function addToolbar()
	{
		JFactory::getDocument()->setTitle(JText::_('COM_REDSHOP_ORDER'));
		$layout = JFactory::getApplication()->input->getCmd('layout');

		if ($layout == 'previewlog')
		{
			$this->setLayout($layout);
		}
		elseif ($layout == 'labellisting')
		{
			RedshopToolbarHelper::title(JText::_('COM_REDSHOP_DOWNLOAD_LABEL'), 'redshop_order48');
			$this->setLayout('labellisting');
			RedshopToolbarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}
		elseif ($layout == 'batch')
		{
			RedshopToolbarHelper::title(JText::_('COM_REDSHOP_ORDER_MANAGEMENT'), 'stack redshop_order48');
		}
		else
		{
			RedshopToolbarHelper::title(JText::_('COM_REDSHOP_ORDER_MANAGEMENT'), 'stack redshop_order48');

			if ($this->canCreate)
			{
				RedshopToolbarHelper::addNew();
			}

			RedshopToolbarHelper::custom(
				'multiprint_order',
				'print_f2.png',
				'print_f2.png',
				'COM_REDSHOP_MULTI_PRINT_ORDER_LBL',
				true
			);

			if ($this->canEdit)
			{
				RedshopToolbarHelper::modal('massOrderStatusChange', 'fa fa-gears', 'COM_REDSHOP_CHANGE_STATUS_TO_ALL_LBL');

				if (Redshop::getConfig()->get('POSTDK_INTEGRATION'))
				{
					RedshopToolbarHelper::modal('massOrderStatusPacsoft', 'fa fa-gears', 'COM_REDSHOP_CHANGE_STATUS_TO_ALL_WITH_PACSOFT_LBL');
				}
			}

			$group = RedshopToolbarHelper::createGroup('export', 'COM_REDSHOP_EXPORT_DATA_LBL');

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_DATA_LBL',
				'export_data',
				true
			);

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_FULL_DATA_LBL',
				'export_fullorder_data',
				false
			);

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_GLS_LBL',
				'gls_export',
				true
			);

			$group->appendButton('Standard',
				'',
				'COM_REDSHOP_EXPORT_GLS_BUSINESS_LBL',
				'business_gls_export',
				true
			);

			$group->renderGroup();

			if ($this->canDelete)
			{
				RedshopToolbarHelper::deleteList();
			}

			// Check PDF plugin
			if (!RedshopHelperPdf::isAvailablePdfPlugins())
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_WARNING_MISSING_PDF_PLUGIN'), 'warning');
			}
		}
	}
}
