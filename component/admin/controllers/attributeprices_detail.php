<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerAttributeprices_detail extends RedshopController
{
	protected $jinput;

	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
		$this->jinput = JFactory::getApplication()->input;
	}

	public function edit()
	{
		$this->jinput->set('view', 'attributeprices_detail');
		$this->jinput->set('layout', 'default');
		$this->jinput->set('hidemainmenu', 1);
		parent::display();
	}

	public function save()
	{
		$post = $this->jinput->getArray($_POST);

		$section_id = $this->jinput->get('section_id');
		$section = $this->jinput->getString('section');

		$post['product_currency'] = Redshop::getConfig()->get('CURRENCY_CODE');
		$post['cdate'] = time();
		$post['discount_start_date'] = strtotime($post ['discount_start_date']);

		if ($post['discount_end_date'])
		{
			$post ['discount_end_date'] = strtotime($post['discount_end_date']) + (23 * 59 * 59);
		}

		$cid = $this->jinput->get('cid', array(0), 'array');
		$post ['price_id'] = $cid [0];

		$model = $this->getModel('attributeprices_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_PRICE_DETAIL');
		}

		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=attributeprices&section=' . $section . '&section_id=' . $section_id, $msg);
	}

	public function remove()
	{
		$section_id = $this->jinput->get('section_id');
		$section = $this->jinput->getString('section');
		$cid = $this->jinput->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('attributeprices_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ATTRIBUTE_PRICE_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=attributeprices&section=' . $section . '&section_id=' . $section_id, $msg);
	}

	public function cancel()
	{
		$section_id = $this->jinput->get('section_id');

		$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=attributeprices&section_id=' . $section_id, $msg);
	}
}
