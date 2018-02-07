<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Utility helper
 *
 * @since  2.0.7
 */
class Utility
{
	/**
	 * This function is for check captcha code
	 *
	 * @param   string   $data            The answer
	 * @param   boolean  $displayWarning  Display warning or not.
	 *
	 * @return  boolean
	 * @throws  \Exception
	 *
	 * @since   2.0.7
	 */
	public static function checkCaptcha($data, $displayWarning = true)
	{
		$default = \JFactory::getConfig()->get('captcha');

		if (\JFactory::getApplication()->isSite())
		{
			$default = \JFactory::getApplication()->getParams()->get('captcha', \JFactory::getConfig()->get('captcha'));
		}

		if (empty($default))
		{
			return true;
		}

		$captcha = \JCaptcha::getInstance($default, array('namespace' => 'redshop'));

		if ($captcha != null && !$captcha->checkAnswer($data))
		{
			if ($displayWarning)
			{
				\JFactory::getApplication()->enqueueMessage(\JText::_('COM_REDSHOP_INVALID_SECURITY'), 'error');
			}

			return false;
		}

		return true;
	}

	/**
	 * Function which will return product tag array form  given template
	 *
	 * @param   integer $section      Display warning or not.
	 * @param   string  $templateHtml Display warning or not.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getProductTags($section = \RedshopHelperExtrafields::SECTION_PRODUCT, $templateHtml = '')
	{
		if (empty($templateHtml))
		{
			return array();
		}

		$db     = \JFactory::getDbo();
		$query  = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . (int) $section);
		$fields = $db->setQuery($query)->loadColumn();

		if (empty($fields))
		{
			return array();
		}

		$templateHtml = explode("{", $templateHtml);

		if (empty($templateHtml))
		{
			return array();
		}

		$results = array();

		foreach ($templateHtml as $tmp)
		{
			$word = explode('}', $tmp);

			if (in_array($word[0], $fields))
			{
				$results[] = $word[0];
			}
		}

		return $results;
	}
}
