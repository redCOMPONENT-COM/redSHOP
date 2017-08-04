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

		$uri                  = JFactory::getURI();
		$lists                = array();
		$colvalue             = array();
		$model                = $this->getModel();
		$detail               = $this->get('data');
		$parentsection        = $jinput->get('parentsection', '');
		$detail->section_type = $jinput->get('section_type', $detail->section_type);
		$isNew                = ($detail->xmlexport_id < 1);
		$text                 = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_XML_EXPORT_MANAGEMENT') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_export48');
		JToolBarHelper::custom('xmlexport', 'redshop_export32.png', JText::_('COM_REDSHOP_XML_EXPORT'), JText::_('COM_REDSHOP_XML_EXPORT'), false, false);
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$section_typelist   = $xmlhelper->getSectionTypeList();
		$auto_sync_interval = $xmlhelper->getSynchIntervalList();
		$columns            = $xmlhelper->getSectionColumnList($detail->section_type, $parentsection);
		$iparray            = $xmlhelper->getXMLExportIpAddress($detail->xmlexport_id);
		$dbfield            = "";
		$dbchildname        = "";

		switch ($parentsection)
		{
			case "orderdetail":
			case "productdetail":
				if (isset($childelement[$parentsection]))
				{
					$detail->element_name = $childelement[$parentsection][0];
					$detail->xmlexport_filetag = $childelement[$parentsection][1];
				}

				$dbfield = $detail->xmlexport_filetag;
				$dbchildname = $detail->element_name;
				break;
			case "stockdetail":
				if (isset($childelement[$parentsection]))
				{
					$detail->stock_element_name = $childelement[$parentsection][0];
					$detail->xmlexport_stocktag = $childelement[$parentsection][1];
				}

				$dbfield = $detail->xmlexport_stocktag;
				$dbchildname = $detail->stock_element_name;
				break;
			case "billingdetail":
				if (isset($childelement[$parentsection]))
				{
					$detail->billing_element_name = $childelement[$parentsection][0];
					$detail->xmlexport_billingtag = $childelement[$parentsection][1];
				}

				$dbfield = $detail->xmlexport_billingtag;
				$dbchildname = $detail->billing_element_name;
				break;
			case "shippingdetail":
				if (isset($childelement[$parentsection]))
				{
					$detail->shipping_element_name = $childelement[$parentsection][0];
					$detail->xmlexport_shippingtag = $childelement[$parentsection][1];
				}

				$dbfield = $detail->xmlexport_shippingtag;
				$dbchildname = $detail->shipping_element_name;
				break;
			case "orderitem":
				if (isset($childelement[$parentsection]))
				{
					$detail->orderitem_element_name = $childelement[$parentsection][0];
					$detail->xmlexport_orderitemtag = $childelement[$parentsection][1];
				}

				$dbfield = $detail->xmlexport_orderitemtag;
				$dbchildname = $detail->orderitem_element_name;
				break;
			case "prdextrafield":
				if (isset($childelement[$parentsection]))
				{
					$detail->prdextrafield_element_name = $childelement[$parentsection][0];
					$detail->xmlexport_prdextrafieldtag = $childelement[$parentsection][1];
				}

				$dbfield = $detail->xmlexport_prdextrafieldtag;
				$dbchildname = $detail->prdextrafield_element_name;
				break;
		}

		for ($i = 0, $in = count($columns); $i < $in; $i++)
		{
			$tmpVal = $xmlhelper->getXMLFileTag($columns[$i]->Field, $dbfield);
			$colvalue[] = $tmpVal[0];
		}

		$lists['auto_sync']             = JHTML::_('select.booleanlist', 'auto_sync', 'class="inputbox" size="1"', $detail->auto_sync);
		$lists['sync_on_request']       = JHTML::_('select.booleanlist', 'sync_on_request', 'class="inputbox" size="1"', $detail->sync_on_request);
		$lists['section_type']          = JHTML::_('select.genericlist', $section_typelist, 'section_type',
			'class="inputbox" size="1" onchange="setExportSectionType();" ', 'value', 'text', $detail->section_type
		);
		$lists['auto_sync_interval']    = JHTML::_('select.genericlist', $auto_sync_interval, 'auto_sync_interval',
			'class="inputbox" size="1" ', 'value', 'text', $detail->auto_sync_interval
		);
		$lists['published']             = JHTML::_('select.booleanlist', 'xmlpublished', 'class="inputbox"', $detail->published);
		$lists['use_to_all_users']      = JHTML::_('select.booleanlist', 'use_to_all_users', 'class="inputbox"', $detail->use_to_all_users);
		$categoryData                   = $model->getCategoryList();
		$detail->xmlexport_on_category  = explode(',', $detail->xmlexport_on_category);
		$lists['xmlexport_on_category'] = JHTML::_('select.genericlist', $categoryData, 'xmlexport_on_category[]',
			'class="inputbox" multiple="multiple" ', 'value', 'text', $detail->xmlexport_on_category
		);

		if ($layout != "")
		{
			$this->setlayout($layout);
		}
		else
		{
			$this->setlayout("default");
		}

		$this->lists       = $lists;
		$this->detail      = $detail;
		$this->columns     = $columns;
		$this->colvalue    = $colvalue;
		$this->childname   = $dbchildname;
		$this->iparray     = $iparray;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
