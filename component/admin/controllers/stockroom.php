<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Stockroom controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       __DEPLPOY_VERSION__
 */
class RedshopControllerStockroom extends RedshopControllerForm
{
	public function importStockFromEconomic()
	{
		// Add product stock from economic
		$cnt          = $this->input->getInt('cnt', 0);
		$stockroom_id = $this->input->getInt('stockroom_id', 0);
		$totalprd     = 0;
		$msg          = '';

		if (Redshop::getConfig()->getInt('ECONOMIC_INTEGRATION') == 1) {
			$db    = JFactory::getDbo();
			$incNo = $cnt;
			$query = 'SELECT p.* FROM #__redshop_product AS p LIMIT ' . $cnt . ', 10 ';

			$db->setQuery($query);
			$prd         = $db->loadObjectlist();
			$totalprd    = count($prd);
			$responcemsg = '';

			for ($i = 0, $in = count($prd); $i < $in; $i++) {
				$incNo++;
				$ecoProductNumber = RedshopEconomic::importStockFromEconomic($prd[$i]);
				$responcemsg      .= "<div>" . $incNo . ": " . JText::_(
						'COM_REDSHOP_PRODUCT_NUMBER'
					) . " " . $prd[$i]->product_number . " -> ";

				if (count($ecoProductNumber) > 0 && isset($ecoProductNumber[0])) {
					$query = "UPDATE #__redshop_product_stockroom_xref "
						. "SET quantity='" . $ecoProductNumber[0] . "' "
						. "WHERE product_id='" . $prd[$i]->product_id . "' "
						. "AND stockroom_id='" . $stockroom_id . "' ";
					$db->setQuery($query);
					$db->execute();
					$responcemsg .= "<span style='color: #00ff00'>" . JText::_(
							'COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC_SUCCESS'
						) . "</span>";
				} else {
					$errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_STOCK_FROM_ECONOMIC');

					if (JError::isError(JError::getError())) {
						$error  = JError::getError();
						$errmsg = $error->getMessage();
					}

					$responcemsg .= "<span style='color: #ff0000'>" . $errmsg . "</span>";
				}

				$responcemsg .= "</div>";
			}

			if ($totalprd > 0) {
				$msg = $responcemsg;
			} else {
				$msg = JText::_("COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC_IS_COMPLETED");
			}
		}

		echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";

		JFactory::getApplication()->close();
	}
}
