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

class RedshopControllerQuotation extends JController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function export_data()
	{
		$db = JFactory::getDbo();
		$producthelper = new producthelper;
		$quotationhelper = new quotationHelper;
		$model = $this->getModel('quotation');

		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$data = $model->export_data($cid);

		$query = $db->getQuery(true);

		$query->select('quotation_id, count(quotation_item_id) as noproduct')
				->from('#__redshop_quotation_item')
				->group('quotation_id');

		if ($cid[0] != 0)
		{
			$quotation_id = implode(',', $cid);
			$query->where("quotation_id IN (" . $quotation_id . ")");
		}

		$db->setQuery($query);
		$no_products = $db->loadObjectList();

		$product_count = array();

		for ($i = 0; $i < count($data); $i++)
		{
			$product_count [] = $no_products [$i]->noproduct;
		}

		$no_products = max($product_count);

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Quotation.csv');

		echo "Quotation ID,Name,Email, PhoneNumber, Quotation Status, Quotation Note,";

		for ($i = 1; $i <= $no_products; $i++)
		{
			echo JText::_('PRODUCT_NAME') . $i . ',';
			echo JText::_('PRODUCT') . ' ' . JText::_('PRODUCT_PRICE') . $i . ',';
			echo JText::_('PRODUCT_ATTRIBUTE') . $i . ',';
		}

		echo "Quotation Date \n";

		for ($i = 0; $i < count($data); $i++)
		{
			echo '"' . $data [$i]->quotation_id . '",';
			echo '"' . $data [$i]->firstname . " " . $data[$i]->lastname . '",';
			echo '"' . $data [$i]->user_email . '",';
			echo '"' . $data [$i]->phone . '",';

			echo '"' . $quotationhelper->getQuotationStatusName($data[$i]->quotation_status) . '",';

			echo '"' . $data [$i]->quotation_note . '",';

			$no_items = $quotationhelper->getQuotationProduct($data[$i]->quotation_id);

			for ($it = 0; $it < count($no_items); $it++)
			{
				echo '"' . $no_items [$it]->product_name . '",';
				echo '"' . REDCURRENCY_SYMBOL . $no_items[$it]->product_final_price . '",';

				$product_attribute = $producthelper->makeAttributeQuotation($no_items[$it]->quotation_item_id, 0, $no_items[$it]->product_id);
				$product_attribute = preg_replace('#<[^>]+>#', ' ', $product_attribute);

				echo '"' . trim($product_attribute) . '",';
			}

			$temp = $no_products - count($no_items);
			echo str_repeat(',', $temp * 3);

			echo '"' . date('d-m-Y H:i', $data [$i]->quotation_cdate) . '"' . "\n";
		}

		exit ();
	}
}
