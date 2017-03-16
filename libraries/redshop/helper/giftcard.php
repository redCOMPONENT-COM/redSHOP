<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Giftcard
 *
 * @since  2.0.3
 */
class RedshopHelperGiftcard
{
	/**
	 * [getGiftcardById]
	 * 
	 * @param   [int]  $id  [Giftcard ID]
	 * 
	 * @return  [type]
	 */
	public static function getGiftcardById($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_giftcard'))
			->where($db->qn('giftcard_id') . ' = ' . (int) $id);

		$query->setLimit('1');

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * [getValidityDate]
	 * 
	 * @param   [type]  $period  [description]
	 * @param   [type]  $data    [description]
	 * 
	 * @return  [data]
	 */
	public static function getValidityDate($period, $data)
	{
		$todate = mktime(0, 0, 0, date('m'), date('d') + $period, date('Y'));
		$config = Redconfiguration::getInstance();

		$todate   = $config->convertDateFormat($todate);
		$fromdate = $config->convertDateFormat(strtotime(date('d M Y')));

		$tags = ["{giftcard_validity_from}", "{giftcard_validity_to}"];
		$replaces = [
			JText::_('COM_REDSHOP_FROM') . " " . $fromdate,
			JText::_('COM_REDSHOP_TO') . " " . $todate,
		];

		$data = str_replace($tags, $replaces, $data);

		return $data;
	}
}
