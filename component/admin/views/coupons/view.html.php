<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Coupons
 *
 * @package      RedSHOP.Backend
 * @subpackage  View
 * @since        2.1.0
 */
class RedshopViewCoupons extends RedshopViewList
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
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public function onRenderColumn($config, $index, $row)
	{
		$isCheckedOut = $row->checked_out && JFactory::getUser()->id != $row->checked_out;
		$isInline     = Redshop::getConfig()->getBool('INLINE_EDITING');
		$value        = $row->{$config['dataCol']};

		switch ($config['dataCol'])
		{
			case 'type':
				return !$value ? '<span class="label label-primary">' . JText::_('COM_REDSHOP_COUPON_TYPE_OPTION_TOTAL') . '</span>'
					: '<span class="label label-success">' . JText::_('COM_REDSHOP_COUPON_TYPE_OPTION_PERCENTAGE') . '</span>';

			case 'effect':
				return !$value ? '<span class="label label-primary">' . JText::_('COM_REDSHOP_COUPON_EFFECT_OPTION_GLOBAL') . '</span>'
					: '<span class="label label-success">' . JText::_('COM_REDSHOP_COUPON_EFFECT_OPTION_USER') . '</span>';

			case 'value':
				if (!$isCheckedOut && $isInline && $this->canEdit && $config['inline'] === true)
				{
					$display = !$row->type ? RedshopHelperProductPrice::formattedPrice($value) : $value . '%';

					return JHtml::_('redshopgrid.inline', $config['dataCol'], $value, $display, $row->id, 'number');
				}

				return !$row->type ? RedshopHelperProductPrice::formattedPrice($value) : $value . '%';

			case 'start_date':
			case 'end_date':
				if ($value === '0000-00-00 00:00:00')
				{
					return '';
				}

				$tz = new \DateTimeZone(\JFactory::getConfig()->get('offset'));
				$date = date_create_from_format('Y-m-d H:i:s', $value, new \DateTimeZone('UTC'));

				return $date->setTimezone($tz)->format(Redshop::getConfig()->get('DEFAULT_DATEFORMAT', 'd-m-Y'));

			default:
				return parent::onRenderColumn($config, $index, $row);
		}
	}
}
