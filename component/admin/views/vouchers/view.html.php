<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Config\App;

defined('_JEXEC') or die;

/**
 * View Vouchers
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewVouchers extends RedshopViewList
{
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
	 */
	public function onRenderColumn($config, $index, $row)
	{
		$isCheckedOut = $row->checked_out && JFactory::getUser()->id != $row->checked_out;
		$isInline     = Redshop::getConfig()->getBool('INLINE_EDITING');
		$value        = $row->{$config['dataCol']};

		switch ($config['dataCol'])
		{
			case 'amount':
				if (!$isCheckedOut && $isInline && $this->canEdit && $config['inline'] === true)
				{
					$display = RedshopHelperProductPrice::formattedPrice($value);

					return JHtml::_('redshopgrid.inline', $config['dataCol'], $value, $display, $row->id, 'number');
				}

				return RedshopHelperProductPrice::formattedPrice($value);

			case 'free_ship':
				if ($value)
				{
					return '<i class="text-success fa fa-check"></i>';
				}

				return '<i class="text-danger fa fa-remove"></i>';

			case 'voucher_left':
				return RedshopHelperProductPrice::formattedPrice($value);

			case 'start_date':
			case 'end_date':
				if ($value === '0000-00-00 00:00:00')
				{
					return '';
				}

				return JFactory::getDate($value)->format(Redshop::getConfig()->get('DEFAULT_DATEFORMAT', 'Y-m-d'));

			default:
				return parent::onRenderColumn($config, $index, $row);
		}
	}
}
