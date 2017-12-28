<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Discount Products
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */

class RedshopViewDiscount_Products extends RedshopViewList
{
	/**
	 * Display check-in button or not.
	 *
	 * @var   boolean
	 * @since  __DEPLOY_VERSION__
	 */
	protected $checkIn = false;

	/**
	 * Method for render columns
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 *
	 * @throws  Exception
	 */
	public function onRenderColumn($config, $index, $row)
	{
		$isInline = Redshop::getConfig()->getBool('INLINE_EDITING');
		$value    = $row->{$config['dataCol']};

		switch ($config['dataCol'])
		{
			case 'amount':
				if ($isInline && $this->canEdit && $config['inline'] === true)
				{
					$display = RedshopHelperProductPrice::formattedPrice($value);

					return JHtml::_('redshopgrid.inline', $config['dataCol'], $value, $display, $row->{$this->getPrimaryKey()}, 'number');
				}

				return RedshopHelperProductPrice::formattedPrice($value);

			case 'discount_amount':
				if ($isInline && $this->canEdit && $config['inline'] === true)
				{
					$display = !$row->discount_type ? RedshopHelperProductPrice::formattedPrice($value) : $value . ' %';

					return JHtml::_('redshopgrid.inline', $config['dataCol'], $value, $display, $row->{$this->getPrimaryKey()}, 'number');
				}

				return !$row->discount_type ? RedshopHelperProductPrice::formattedPrice($value) : $value . ' %';

			case 'condition':
				if ($value == 1)
				{
					return '<strong class="text-primary">' . JText::_('COM_REDSHOP_DISCOUNT_CONDITION_OPTION_LOWER') . '</strong>';
				}
				elseif ($value == 2)
				{
					return '<strong class="text-primary">' . JText::_('COM_REDSHOP_DISCOUNT_CONDITION_OPTION_EQUAL') . '</strong>';
				}

				return '<strong class="text-primary">' . JText::_('COM_REDSHOP_DISCOUNT_CONDITION_OPTION_HIGHER') . '</strong>';

			case 'discount_type':
				return !$value ? '<span class="label label-primary">' . JText::_('COM_REDSHOP_DISCOUNT_DISCOUNT_TYPE_OPTION_TOTAL') . '</span>'
					: '<span class="label label-success">' . JText::_('COM_REDSHOP_DISCOUNT_DISCOUNT_TYPE_OPTION_PERCENTAGE') . '</span>';

			case 'start_date':
			case 'end_date':
				return $value > 0 ? JFactory::getDate($value)->format(Redshop::getConfig()->getString('DEFAULT_DATEFORMAT', 'Y-m-d')) : '';

			default:
				return parent::onRenderColumn($config, $index, $row);
		}
	}
}
