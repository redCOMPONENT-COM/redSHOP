<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;



class RedshopViewXmlimport_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$xmlhelper = new xmlHelper;
		$document  = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_xmlimport'));
		$document->addScript('components/com_redshop/assets/js/xmlfunc.js');

		$uri                   = JFactory::getURI();
		$columns               = array();
		$lists                 = array();
		$updateshippingtag     = array();
		$resultarray           = array();
		$xmlfiletag            = array();
		$xmlbillingtag         = array();
		$xmlshippingtag        = array();
		$xmlitemtag            = array();
		$xmlstocktag           = array();
		$xmlprdextrafieldtag   = array();
		$updatefiletag         = array();
		$updatebillingtag      = array();
		$updateprdexttag       = array();
		$updateitemtag         = array();
		$updatestocktag        = array();
		$model                 = $this->getModel();

		$detail                = $this->get('data');
		$detail->section_type  = JFactory::getApplication()->input->get('section_type', $detail->section_type);

		$xmlimport_url         = $model->updateFile();

		$detail->xmlimport_url = $model->getXMLImporturl();

		$isNew                 = ($detail->xmlimport_id < 1);
		$text                  = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_XML_IMPORT_MANAGEMENT') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_import48');
		JToolBarHelper::custom('xmlimport', 'redshop_import_import32.png', JText::_('COM_REDSHOP_XML_IMPORT'),
			JText::_('COM_REDSHOP_XML_IMPORT'), false, false
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

		$section_type                = $xmlhelper->getSectionTypeList();
		$auto_sync_interval          = $xmlhelper->getSynchIntervalList();
		$lists['auto_sync']          = JHTML::_('select.booleanlist', 'auto_sync', 'class="inputbox" size="1"', $detail->auto_sync);
		$lists['sync_on_request']    = JHTML::_('select.booleanlist', 'sync_on_request', 'class="inputbox" size="1"', $detail->sync_on_request);
		$lists['auto_sync_interval'] = JHTML::_('select.genericlist', $auto_sync_interval,
			'auto_sync_interval', 'class="inputbox" size="1" ', 'value', 'text', $detail->auto_sync_interval
		);
		$lists['override_existing'] = JHTML::_('select.booleanlist', 'override_existing', 'class="inputbox" size="1"', $detail->override_existing);
		$lists['xmlpublished']      = JHTML::_('select.booleanlist', 'xmlpublished', 'class="inputbox"', $detail->published);

		if ($xmlimport_url != "")
		{
			$filedetail          = $xmlhelper->readXMLImportFile($xmlimport_url, $detail);
			$xmlfiletag          = $filedetail['xmlsectionarray'];
			$xmlbillingtag       = $filedetail['xmlbillingarray'];
			$xmlshippingtag      = $filedetail['xmlshippingarray'];
			$xmlitemtag          = $filedetail['xmlorderitemarray'];
			$xmlstocktag         = $filedetail['xmlstockarray'];
			$xmlprdextrafieldtag = $filedetail['xmlprdextarray'];
		}

		$lists['section_type'] = JHTML::_('select.genericlist', $section_type, 'section_type',
			'class="inputbox" size="1" onchange="setExportSectionType();" ', 'value', 'text', $detail->section_type
		);

		if ($detail->section_type != "")
		{
			$cols    = array();
			$columns = $xmlhelper->getSectionColumnList($detail->section_type);

			for ($i = 0, $in = count($columns); $i < $in; $i++)
			{
				$cols[$i] = new stdClass();
				$cols[$i]->value = $columns[$i]->Field;
				$cols[$i]->text  = $columns[$i]->Field;
			}

			$op           = array();
			$op[0]        = new stdClass();
			$op[0]->value = '';
			$op[0]->text  = JText::_('COM_REDSHOP_SELECT');
			$columns      = array_merge($op, $cols);

			for ($i = 0, $in = count($xmlfiletag); $i < $in; $i++)
			{
				$colvalue               = $xmlhelper->getXMLFileTag($xmlfiletag[$i], $detail->xmlimport_filetag);
				$updatefiletag[$i]      = $colvalue[1];
				$lists[$xmlfiletag[$i]] = JHTML::_('select.genericlist', $columns, $xmlfiletag[$i], 'class="inputbox" size="1" ', 'value', 'text', $colvalue[0]);
			}

			if (count($xmlbillingtag) > 0)
			{
				$cols    = array();
				$columns = $xmlhelper->getSectionColumnList($detail->section_type, "billingdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$cols[$i] = new stdClass();
					$cols[$i]->value = $columns[$i]->Field;
					$cols[$i]->text  = $columns[$i]->Field;
				}

				$columns = array_merge($op, $cols);

				for ($i = 0, $in = count($xmlbillingtag); $i < $in; $i++)
				{
					$colvalue                            = $xmlhelper->getXMLFileTag($xmlbillingtag[$i], $detail->xmlimport_billingtag);
					$updatebillingtag[$i]                = $colvalue[1];
					$lists["bill_" . $xmlbillingtag[$i]] = JHTML::_('select.genericlist', $columns, "bill_" . $xmlbillingtag[$i],
						'class="inputbox" size="1" ', 'value', 'text', $colvalue[0]
					);
				}
			}

			if (count($xmlshippingtag) > 0)
			{
				$cols    = array();
				$columns = $xmlhelper->getSectionColumnList($detail->section_type, "shippingdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$cols[$i] = new stdClass();
					$cols[$i]->value = $columns[$i]->Field;
					$cols[$i]->text  = $columns[$i]->Field;
				}

				$columns = array_merge($op, $cols);

				for ($i = 0, $in = count($xmlshippingtag); $i < $in; $i++)
				{
					$colvalue                              = $xmlhelper->getXMLFileTag($xmlshippingtag[$i], $detail->xmlimport_shippingtag);
					$updateshippingtag[$i]                 = $colvalue[1];
					$lists["shipp_" . $xmlshippingtag[$i]] = JHTML::_('select.genericlist', $columns, "shipp_" . $xmlshippingtag[$i],
						'class="inputbox" size="1" ', 'value', 'text', $colvalue[0]
					);
				}
			}

			if (count($xmlitemtag) > 0)
			{
				$cols    = array();
				$columns = $xmlhelper->getSectionColumnList($detail->section_type, "orderitem");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$cols[$i] = new stdClass();
					$cols[$i]->value = $columns[$i]->Field;
					$cols[$i]->text  = $columns[$i]->Field;
				}

				$columns = array_merge($op, $cols);

				for ($i = 0, $in = count($xmlitemtag); $i < $in; $i++)
				{
					$colvalue                         = $xmlhelper->getXMLFileTag($xmlitemtag[$i], $detail->xmlimport_orderitemtag);
					$updateitemtag[$i]                = $colvalue[1];
					$lists["item_" . $xmlitemtag[$i]] = JHTML::_('select.genericlist', $columns, "item_" . $xmlitemtag[$i],
						'class="inputbox" size="1" ', 'value', 'text', $colvalue[0]
					);
				}
			}

			if (count($xmlstocktag) > 0)
			{
				$cols    = array();
				$columns = $xmlhelper->getSectionColumnList($detail->section_type, "stockdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$cols[$i]->value = $columns[$i]->Field;
					$cols[$i]->text  = $columns[$i]->Field;
				}

				$columns = array_merge($op, $cols);

				for ($i = 0, $in = count($xmlstocktag); $i < $in; $i++)
				{
					$colvalue                           = $xmlhelper->getXMLFileTag($xmlstocktag[$i], $detail->xmlimport_stocktag);
					$updatestocktag[$i]                 = $colvalue[1];
					$lists["stock_" . $xmlstocktag[$i]] = JHTML::_('select.genericlist', $columns, "stock_" . $xmlstocktag[$i],
						'class="inputbox" size="1" ', 'value', 'text', $colvalue[0]
					);
				}
			}

			if (count($xmlprdextrafieldtag) > 0)
			{
				$cols    = array();
				$columns = $xmlhelper->getSectionColumnList($detail->section_type, "prdextrafield");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$cols[$i]->value = $columns[$i]->Field;
					$cols[$i]->text  = $columns[$i]->Field;
				}

				$columns = array_merge($op, $cols);

				for ($i = 0, $in = count($xmlprdextrafieldtag); $i < $in; $i++)
				{
					$colvalue                                    = $xmlhelper->getXMLFileTag($xmlprdextrafieldtag[$i], $detail->xmlimport_prdextrafieldtag);
					$updateprdexttag[$i]                         = $colvalue[1];
					$lists["prdext_" . $xmlprdextrafieldtag[$i]] = JHTML::_('select.genericlist', $columns, "prdext_" . $xmlprdextrafieldtag[$i],
						'class="inputbox" size="1" ', 'value', 'text', $colvalue[0]
					);
				}
			}
		}

		$this->resultarray         = $resultarray;
		$this->lists               = $lists;
		$this->detail              = $detail;
		$this->columns             = $columns;
		$this->xmlfiletag          = $xmlfiletag;
		$this->xmlbillingtag       = $xmlbillingtag;
		$this->xmlshippingtag      = $xmlshippingtag;
		$this->xmlitemtag          = $xmlitemtag;
		$this->xmlstocktag         = $xmlstocktag;
		$this->xmlprdextrafieldtag = $xmlprdextrafieldtag;
		$this->updatefiletag       = $updatefiletag;
		$this->updatebillingtag    = $updatebillingtag;
		$this->updateshippingtag   = $updateshippingtag;
		$this->updateitemtag       = $updateitemtag;
		$this->updatestocktag      = $updatestocktag;
		$this->updateprdexttag     = $updateprdexttag;
		$this->tmpxmlimport_url    = $xmlimport_url;
		$this->request_url         = $uri->toString();

		parent::display($tpl);
	}
}
