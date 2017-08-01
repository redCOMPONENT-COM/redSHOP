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


class RedshopControllerProduct extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function ins_product()
	{
		$this->input->set('layout', 'ins_product');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function importeconomic()
	{
		// Add product to economic
		$cnt = $this->input->getInt('cnt', 0);
		$totalprd = 0;
		$msg = '';

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
				$ecoProductNumber = Economic::createProductInEconomic($prd[$i]);
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
				$msg = JText::_("COM_REDSHOP_IMPORT_PRODUCT_TO_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";
		die();
	}

	public function importatteco()
	{
		// Add product attribute to economic
		$cnt = $this->input->getInt('cnt', 0);
		$totalprd = 0;
		$msg = '';

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 1)
		{
			$economic = economic::getInstance();

			$db = JFactory::getDbo();
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

			for ($i = 0, $in = count($list); $i < $in; $i++)
			{
				$incNo++;
				$prdrow = new stdClass;
				$prdrow->product_id = $list[$i]->product_id;
				$prdrow->accountgroup_id = $list[$i]->accountgroup_id;
				$ecoProductNumber = Economic::createPropertyInEconomic($prdrow, $list[$i]);
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
						$errmsg = $error->getMessage();
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

			for ($i = 0, $in = count($list); $i < $in; $i++)
			{
				$incNo++;
				$prdrow = new stdClass;
				$prdrow->product_id = $list[$i]->product_id;
				$prdrow->accountgroup_id = $list[$i]->accountgroup_id;
				$ecoProductNumber = Economic::createSubpropertyInEconomic($prdrow, $list[$i]);
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
				$msg = JText::_("COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";
		die();
	}

	public function saveprice()
	{
		JSession::checkToken() or die();

		$productIds     = $this->input->post->get('pid', array(), 'array');
		$discountPrices = $this->input->post->get('price', array(), 'array');

		/** @var RedshopModelProduct $model */
		$model = $this->getModel('Product');
		$model->savePrices($productIds, $discountPrices);

		$this->setRedirect('index.php?option=com_redshop&view=product&layout=listing');
	}

	/**
	 * Save all discount price
	 *
	 * @return void
	 */
	public function savediscountprice()
	{
		JSession::checkToken() or die();

		$productIds     = $this->input->post->get('pid', array(), 'array');
		$discountPrices = $this->input->post->get('discount_price', array(), 'array');

		/** @var RedshopModelProduct $model */
		$model = $this->getModel('Product');
		$model->saveDiscountPrices($productIds, $discountPrices);

		$this->setRedirect('index.php?option=com_redshop&view=product&layout=listing');
	}

	public function template()
	{
		$template_id = $this->input->get('template_id', '');
		$product_id = $this->input->get('product_id', '');
		$section = $this->input->get('section', '');
		$model = $this->getModel('product');

		$data_product = $model->product_template($template_id, $product_id, $section);

		if (is_array($data_product))
		{
			for ($i = 0, $in = count($data_product); $i < $in; $i++)
			{
				echo $data_product[$i];
			}
		}

		else
		{
			echo $data_product;
		}

		JFactory::getApplication()->close();
	}

	public function assignTemplate()
	{
		$post = $this->input->post->getArray();

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

	public function saveorder()
	{


		$cid = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('product');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=product', $msg);
	}

	/**
	 * Check in of one or more records.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   12.2
	 */
	public function checkin()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids = $this->input->post->get('cid', array(), 'array');

		$model = $this->getModel('product_detail');
		$return = $model->checkin($ids);

		if ($return === false)
		{
			// Checkin failed.
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=product', false), $message, 'error');

			return false;
		}
		else
		{
			// Checkin succeeded.
			$message = JText::plural('COM_REDSHOP_PRODUCT_N_ITEMS_CHECKED_IN', count($ids));
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=product', false), $message);

			return true;
		}
	}
}
