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

class productController extends JController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/*
	 * select A Product Element
	 */
	public function element()
	{
		JRequest::setVar('layout', 'element');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function ins_product()
	{
		JRequest::setVar('layout', 'ins_product');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function listing()
	{
		JRequest::setVar('layout', 'listing');

		parent::display();
	}

	public function importeconomic()
	{
		// Add product to economic
		$cnt = JRequest::getInt('cnt', 0);
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
				$ecoProductNumber = $economic->createProductInEconomic($prd[$i]);
				$responcemsg .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . " " . $prd[$i]->product_number . " -> ";

				if (count($ecoProductNumber) > 0 && is_object($ecoProductNumber[0]) && isset($ecoProductNumber[0]->Number))
				{
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC_SUCCESS') . "</span>";
				}
				else
				{
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_PRODUCT_TO_ECONOMIC');

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
				$msg = JText::_("COM_REDSHOP_IMPORT_PRODUCT_TO_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";
		die();
	}

	public function importatteco()
	{
		// Add product attribute to economic
		$cnt = JRequest::getInt('cnt', 0);
		$totalprd = 0;
		$msg = '';

		if (ECONOMIC_INTEGRATION == 1 && ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC == 1)
		{
			$economic = new economic;

			$db = JFactory::getDBO();
			$incNo = $cnt;
			$query = "SELECT ap.*, a.attribute_name, p.product_id, p.accountgroup_id "
				. "FROM #__redshop_product_attribute_property AS ap "
				. "LEFT JOIN #__redshop_product_attribute AS a ON a.attribute_id=ap.attribute_id "
				. "LEFT JOIN #__redshop_product AS p ON p.product_id=a.product_id "
				. "WHERE p.published=1 "
				. "AND p.product_id!='' "
				. "AND ap.property_number!='' "
				. "LIMIT " . $cnt . ", 10 ";
			$db->setQuery($query);
			$list = $db->loadObjectlist();
			$totalprd = count($list);
			$responcemsg = '';

			for ($i = 0; $i < count($list); $i++)
			{
				$incNo++;
				$prdrow = new stdClass;
				$prdrow->product_id = $list[$i]->product_id;
				$prdrow->accountgroup_id = $list[$i]->accountgroup_id;
				$ecoProductNumber = $economic->createPropertyInEconomic($prdrow, $list[$i]);
				$responcemsg .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_PROPERTY_NUMBER') . " " . $list[$i]->property_number . " -> ";

				if (count($ecoProductNumber) > 0 && is_object($ecoProductNumber[0]) && isset($ecoProductNumber[0]->Number))
				{
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC_SUCCESS') . "</span>";
				}
				else
				{
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_ATTRIBUTES_TO_ECONOMIC');

					if (JError::isError(JError::getError()))
					{
						$error = JError::getError();
						$errmsg = $error->message;
					}

					$responcemsg .= "<span style='color: #ff0000'>" . $errmsg . "</span>";
				}

				$responcemsg .= "</div>";
			}

			$query = "SELECT sp.*, ap.property_id, ap.property_name, p.product_id, p.accountgroup_id  FROM #__redshop_product_subattribute_color AS sp "
				. "LEFT JOIN #__redshop_product_attribute_property AS ap ON ap.property_id=sp.subattribute_id "
				. "LEFT JOIN #__redshop_product_attribute AS a ON a.attribute_id=ap.attribute_id "
				. "LEFT JOIN #__redshop_product AS p ON p.product_id=a.product_id "
				. "WHERE p.published=1 "
				. "AND p.product_id!='' "
				. "AND sp.subattribute_color_number!='' "
				. "LIMIT " . $cnt . ", 10 ";
			$db->setQuery($query);
			$list = $db->loadObjectlist();
			$totalprd = $totalprd + count($list);

			for ($i = 0; $i < count($list); $i++)
			{
				$incNo++;
				$prdrow = new stdClass;
				$prdrow->product_id = $list[$i]->product_id;
				$prdrow->accountgroup_id = $list[$i]->accountgroup_id;
				$ecoProductNumber = $economic->createSubpropertyInEconomic($prdrow, $list[$i]);
				$responcemsg .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_SUBPROPERTY_NUMBER') . " "
					. $list[$i]->subattribute_color_number . " -> ";

				if (count($ecoProductNumber) > 0 && is_object($ecoProductNumber[0]) && isset($ecoProductNumber[0]->Number))
				{
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC_SUCCESS') . "</span>";
				}
				else
				{
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_ATTRIBUTES_TO_ECONOMIC');

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
				$msg = JText::_("COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";
		die();
	}

	public function saveprice()
	{
		$db = JFactory::getDBO();
		$pid = JRequest::getVar('pid', array(), 'post', 'array');
		$price = JRequest::getVar('price', array(), 'post', 'array');

		for ($i = 0; $i < count($pid); $i++)
		{
			$sql = "UPDATE #__redshop_product  SET product_price='" . $price[$i] . "' WHERE product_id='" . $pid[$i] . "'  ";

			$db->setQuery($sql);
			$db->Query();
		}

		$this->setRedirect('index.php?option=com_redshop&view=product&task=listing');
	}

	public function savediscountprice()
	{
		$db = JFactory::getDBO();
		$pid = JRequest::getVar('pid', array(), 'post', 'array');
		$discount_price = JRequest::getVar('discount_price', array(), 'post', 'array');

		for ($i = 0; $i < count($pid); $i++)
		{
			$sql = "UPDATE #__redshop_product  SET discount_price='" . $discount_price[$i] . "' WHERE product_id='" . $pid[$i] . "'  ";

			$db->setQuery($sql);
			$db->Query();
		}

		$this->setRedirect('index.php?option=com_redshop&view=product&task=listing');
	}

	public function template()
	{
		$template_id = JRequest::getVar('template_id', '');
		$product_id = JRequest::getVar('product_id', '');
		$section = JRequest::getVar('section', '');
		$model = $this->getModel('product');

		$data_product = $model->product_template($template_id, $product_id, $section);

		if (is_array($data_product))
		{
			for ($i = 0; $i < count($data_product); $i++)
			{
				echo $data_product[$i];
			}
		}

		else
		{
			echo $data_product;
		}

		exit;
	}

	public function assignTemplate()
	{
		$post = JRequest::get('post');

		$model = $this->getModel('product');

		if ($model->assignTemplate($post))
		{
			$msg = JText::_('COM_REDSHOP_TEMPLATE_ASSIGN_SUCESS');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_ASSIGNING_TEMPLATE');
		}

		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	public function gbasefeed()
	{
		$post = JRequest::get('post');
		$model = $this->getModel('product');

		if ($model->gbasefeed($post))
		{
			$msg = JText::_('COM_REDSHOP_GBASE_XML_IS_GENERATED_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_IN_GENERATING_GBASE_XML');
		}

		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	public function saveorder()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('product');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=' . $option . '&view=product', $msg);
	}
}
