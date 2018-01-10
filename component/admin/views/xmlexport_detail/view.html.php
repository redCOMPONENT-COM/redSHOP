<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

class RedshopViewXmlexport_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $lists       = array();

	/**
	 * @var object
	 */
	public $detail      = null;

	/**
	 * @var array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $columns     = null;

	/**
	 * @var array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $colvalue    = array();

	/**
	 * @var string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $childname   = '';

	/**
	 * @var array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $iparray     = null;

	/**
	 * @var string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $request_url = null;

	public function display($tpl = null)
	{
		global $context;
		$jinput       = JFactory::getApplication()->input;
		$context      = 'xmlexport_id';
		$layout       = $jinput->getCmd('layout', '');
		$xmlhelper    = new xmlHelper;
		$session      = JFactory::getSession();
		$childelement = $session->get('childelement');
		$document     = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_xmlexport'));
		$document->addScript('components/com_redshop/assets/js/xmlfunc.js');

		$model                      = $this->getModel();
		$this->detail               = $this->get('data');
		$parentsection              = $jinput->get('parentsection', '');
		$this->detail->section_type = $jinput->get('section_type', $this->detail->section_type);
		$isNew                      = ($this->detail->xmlexport_id < 1);
		$text                       = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(
			JText::_('COM_REDSHOP_XML_EXPORT_MANAGEMENT') . ': <small><small>[ ' . $text . ' ]</small></small>',
			'redshop_export48'
		);
		JToolBarHelper::custom(
			'xmlexport', 'redshop_export32.png',
			JText::_('COM_REDSHOP_XML_EXPORT'),
			JText::_('COM_REDSHOP_XML_EXPORT'), false
		);
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$sectionTypeList    = $xmlhelper->getSectionTypeList();
		$autoSyncInterval = $xmlhelper->getSynchIntervalList();
		$this->columns      = $xmlhelper->getSectionColumnList($this->detail->section_type, $parentsection);
		$this->iparray      = $xmlhelper->getXMLExportIpAddress($this->detail->xmlexport_id);
		$dbfield            = "";

		switch ($parentsection)
		{
			case "orderdetail":
			case "productdetail":
				if (isset($childelement[$parentsection]))
				{
					$this->detail->element_name      = $childelement[$parentsection][0];
					$this->detail->xmlexport_filetag = $childelement[$parentsection][1];
				}

				$dbfield         = $this->detail->xmlexport_filetag;
				$this->childname = $this->detail->element_name;
				break;
			case "stockdetail":
				if (isset($childelement[$parentsection]))
				{
					$this->detail->stock_element_name = $childelement[$parentsection][0];
					$this->detail->xmlexport_stocktag = $childelement[$parentsection][1];
				}

				$dbfield         = $this->detail->xmlexport_stocktag;
				$this->childname = $this->detail->stock_element_name;
				break;
			case "billingdetail":
				if (isset($childelement[$parentsection]))
				{
					$this->detail->billing_element_name = $childelement[$parentsection][0];
					$this->detail->xmlexport_billingtag = $childelement[$parentsection][1];
				}

				$dbfield         = $this->detail->xmlexport_billingtag;
				$this->childname = $this->detail->billing_element_name;
				break;
			case "shippingdetail":
				if (isset($childelement[$parentsection]))
				{
					$this->detail->shipping_element_name = $childelement[$parentsection][0];
					$this->detail->xmlexport_shippingtag = $childelement[$parentsection][1];
				}

				$dbfield         = $this->detail->xmlexport_shippingtag;
				$this->childname = $this->detail->shipping_element_name;
				break;
			case "orderitem":
				if (isset($childelement[$parentsection]))
				{
					$this->detail->orderitem_element_name = $childelement[$parentsection][0];
					$this->detail->xmlexport_orderitemtag = $childelement[$parentsection][1];
				}

				$dbfield         = $this->detail->xmlexport_orderitemtag;
				$this->childname = $this->detail->orderitem_element_name;
				break;
			case "prdextrafield":
				if (isset($childelement[$parentsection]))
				{
					$this->detail->prdextrafield_element_name = $childelement[$parentsection][0];
					$this->detail->xmlexport_prdextrafieldtag = $childelement[$parentsection][1];
				}

				$dbfield         = $this->detail->xmlexport_prdextrafieldtag;
				$this->childname = $this->detail->prdextrafield_element_name;
				break;
		}

		foreach ($this->columns as $column)
		{
			$tmpVal           = $xmlhelper->getXMLFileTag($column->Field, $dbfield);
			$this->colvalue[] = $tmpVal[0];
		}

		$this->lists['auto_sync']             = JHTML::_('select.booleanlist', 'auto_sync', 'class="inputbox" size="1"', $this->detail->auto_sync);
		$this->lists['sync_on_request']       = JHTML::_('select.booleanlist', 'sync_on_request', 'class="inputbox" size="1"', $this->detail->sync_on_request);
		$this->lists['section_type']          = JHTML::_('select.genericlist', $sectionTypeList, 'section_type',
			'class="inputbox" size="1" onchange="setExportSectionType();" ', 'value', 'text', $this->detail->section_type
		);
		$this->lists['auto_sync_interval']    = JHTML::_('select.genericlist', $autoSyncInterval, 'auto_sync_interval',
			'class="inputbox" size="1" ', 'value', 'text', $this->detail->auto_sync_interval
		);
		$this->lists['published']             = JHTML::_('select.booleanlist', 'xmlpublished', 'class="inputbox"', $this->detail->published);
		$this->lists['use_to_all_users']      = JHTML::_('select.booleanlist', 'use_to_all_users', 'class="inputbox"', $this->detail->use_to_all_users);
		$categoryData                         = $model->getCategoryList();
		$this->detail->xmlexport_on_category  = explode(',', $this->detail->xmlexport_on_category);
		$this->lists['xmlexport_on_category'] = JHTML::_('select.genericlist', $categoryData, 'xmlexport_on_category[]',
			'class="inputbox" multiple="multiple" ', 'value', 'text', $this->detail->xmlexport_on_category
		);

		if ($layout != "")
		{
			$this->setlayout($layout);
		}
		else
		{
			$this->setlayout("default");
		}

		$this->request_url = JUri::getInstance()->toString();

		parent::display($tpl);
	}
}
