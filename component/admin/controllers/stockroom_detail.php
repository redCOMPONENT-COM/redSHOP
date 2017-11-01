<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\Economic;


class RedshopControllerStockroom_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'stockroom_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function preview()
	{
		$this->input->set('view', 'stockroom_detail');
		$this->input->set('layout', 'default_product');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post                   = $this->input->post->getArray();
		$stockroom_desc         = $this->input->post->get('stockroom_desc', '', 'raw');
		$post["stockroom_desc"] = $stockroom_desc;

		if ($post["delivery_time"] == 'Weeks')
		{
			$post["min_del_time"] = $post["min_del_time"] * 7;
			$post["max_del_time"] = $post["max_del_time"] * 7;
		}

		$cid                    = $this->input->post->get('cid', array(0), 'array');
		$post ['stockroom_id']  = $cid [0];
		$post ['creation_date'] = strtotime($post ['creation_date']);
		$model                  = $this->getModel('stockroom_detail');
		$post['stockroom_name'] = htmlspecialchars($post['stockroom_name']);

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_STOCKROOM_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKROOM_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=stockroom_detail&task=edit&cid[]=' . $row->stockroom_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=stockroom', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('stockroom_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=stockroom', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=stockroom', $msg);
	}

	public function copy()
	{
		$cid   = $this->input->post->get('cid', array(0), 'array');
		$model = $this->getModel('stockroom_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_STOCKROOM_DETAIL');
		}

		$this->setRedirect('index.php?option=com_redshop&view=stockroom', $msg);
	}

	public function importStockFromEconomic()
	{
		// Add product stock from economic
		$cnt          = $this->input->getInt('cnt', 0);
		$stockroom_id = $this->input->getInt('stockroom_id', 0);
		$totalprd     = 0;
		$msg          = '';

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$economic = economic::getInstance();
			$db = JFactory::getDbo();
			$incNo = $cnt;
			$query = 'SELECT p.* FROM #__redshop_product AS p '
				. 'LIMIT ' . $cnt . ', 10 ';
			$db->setQuery($query);
			$prd = $db->loadObjectlist();
			$totalprd = count($prd);
			$responcemsg = '';

			for ($i = 0, $in = count($prd); $i < $in; $i++)
			{
				$incNo++;
				$ecoProductNumber = Economic::importStockFromEconomic($prd[$i]);
				$responcemsg .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . " " . $prd[$i]->product_number . " -> ";

				if (count($ecoProductNumber) > 0 && isset($ecoProductNumber[0]))
				{
					$query = "UPDATE #__redshop_product_stockroom_xref "
						. "SET quantity='" . $ecoProductNumber[0] . "' "
						. "WHERE product_id='" . $prd[$i]->product_id . "' "
						. "AND stockroom_id='" . $stockroom_id . "' ";
					$db->setQuery($query);
					$db->execute();
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC_SUCCESS') . "</span>";
				}
				else
				{
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_STOCK_FROM_ECONOMIC');

					if (JError::isError(JError::getError()))
					{
						$error = JError::getError();
						$errmsg = $error->getMessage();
					}

					$responcemsg .= "<span style='color: #ff0000'>" . $errmsg . "</span>";
				}

				$responcemsg .= "</div>";
			}

			if ($totalprd > 0)
			{
				$msg = $responcemsg;
			}
			else
			{
				$msg = JText::_("COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";
		die();
	}
}
