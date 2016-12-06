<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerCatalog extends RedshopController
{
	protected $jinput;

	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->jinput = JFactory::getApplication()->input;
	}

	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function publish()
	{
		$cid = $this->jinput->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('catalog_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=catalog', $msg);
	}

	public function unpublish()
	{
		$cid = $this->jinput->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('catalog_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=catalog', $msg);
	}
}
