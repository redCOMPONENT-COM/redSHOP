<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

defined('_JEXEC') || die;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  3.0
 */
trait ConditionTag
{
	/**
	 * Replace conditional tag from Redshop payment Discount/charges
	 *
	 * @param   string   $template       Template html
	 * @param   integer  $amount         Amount of cart
	 * @param   integer  $cart           Is in cart?
	 * @param   string   $paymentOprand  Payment oprand
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replaceConditionTag($template = '', $amount = 0, $cart = 0, $paymentOprand = '-')
	{
		if (!$this->isTagExists('{if payment_discount}') || !$this->isTagExists('{payment_discount end if}'))
		{
			return $template;
		}

		if (($cart == 1 || $amount == 0) || $amount <= 0)
		{
			$templateData = $this->getTemplateBetweenLoop('{if payment_discount}', '{payment_discount end if}', $template);

			return $templateData['begin'] . $templateData['end'];
		}

		$replacement = [];

		$replacement['{payment_order_discount}'] = \RedshopHelperProductPrice::formattedPrice($amount);
		$payText  = ($paymentOprand == '+') ? \JText::_('COM_REDSHOP_PAYMENT_CHARGES_LBL')
            : \JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');

		$replacement['{payment_discount_lbl}']    = $payText;
		$replacement['{payment_discount end if}'] = '';
		$replacement['{if payment_discount}']     = '';

		return $this->strReplace($replacement, $template);
	}
}