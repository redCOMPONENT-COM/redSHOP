<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class stockroom_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'stockroom_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function preview()
	{
		JRequest::setVar('view', 'stockroom_detail');
		JRequest::setVar('layout', 'default_product');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post');
		$stockroom_desc = JRequest::getVar('stockroom_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["stockroom_desc"] = $stockroom_desc;

		if ($post["delivery_time"] == 'Weeks')
		{
			$post["min_del_time"] = $post["min_del_time"] * 7;
			$post["max_del_time"] = $post["max_del_time"] * 7;
		}

		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['stockroom_id'] = $cid [0];
		$post ['creation_date'] = strtotime($post ['creation_date']);
		$model = $this->getModel('stockroom_detail');
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
			$this->setRedirect('index.php?option=' . $option . '&view=stockroom_detail&task=edit&cid[]=' . $row->stockroom_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
		}
	}

	public function remove()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('stockroom_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('stockroom_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('stockroom_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
	}

	public function frontpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('stockroom_detail');

		if (!$model->frontpublish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
	}

	public function frontunpublish()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('stockroom_detail');

		if (!$model->frontpublish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
	}

	public function copy()
	{
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$model = $this->getModel('stockroom_detail');

		if ($model->copy($cid))
		{
			$msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_COPIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_COPING_STOCKROOM_DETAIL');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
	}

	public function export_data()
	{
		$model = $this->getModel('stockroom_detail');

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=StockroomProduct.csv');

		echo "Stockroom,Container,Product SKU,Product Name,Product Volume,Quantity\n\n";

		$data = $model->stock_container(0);

		for ($i = 0; $i < count($data); $i++)
		{
			$product = $model->stock_product($data[$i]->container_id);

			echo $data[$i]->stockroom_name . ",";
			echo $data[$i]->container_name . ",";

			for ($p = 0; $p < count($product); $p++)
			{
				if ($p > 0)
				{
					echo ",,";
				}

				echo $product[$p]->product_number . ",";
				echo $product[$p]->product_name . ",";
				echo $product[$p]->product_volume . ",";
				echo $product[$p]->quantity . "\n";
			}

			echo "\n";
		}

		exit;
	}

	public function importStockFromEconomic()
	{
		// Add product stock from economic
		$cnt = JRequest::getInt('cnt', 0);
		$stockroom_id = JRequest::getInt('stockroom_id', 0);
		$totalprd = 0;
		$msg = '';

		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic;
			$db = JFactory::getDBO();
			$incNo = $cnt;
			$query = 'SELECT p.* FROM #__redshop_product AS p '
				. 'LIMIT ' . $cnt . ', 10 ';
			$db->setQuery($query);
			$prd = $db->loadObjectlist();
			$totalprd = count($prd);
			$responcemsg = '';

			for ($i = 0; $i < count($prd); $i++)
			{
				$incNo++;
				$ecoProductNumber = $economic->importStockFromEconomic($prd[$i]);
				$responcemsg .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . " " . $prd[$i]->product_number . " -> ";

				if (count($ecoProductNumber) > 0 && isset($ecoProductNumber[0]))
				{
					$query = "UPDATE #__redshop_product_stockroom_xref "
						. "SET quantity='" . $ecoProductNumber[0] . "' "
						. "WHERE product_id='" . $prd[$i]->product_id . "' "
						. "AND stockroom_id='" . $stockroom_id . "' ";
					$db->setQuery($query);
					$db->Query();
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC_SUCCESS') . "</span>";
				}
				else
				{
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_STOCK_FROM_ECONOMIC');

					if (JError::isError(JError::getError()))
					{
						$error = JError::getError();
						$errmsg = $error->message;
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
