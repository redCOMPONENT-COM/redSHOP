<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Behat\Transliterator\Transliterator;

/**
 * Utility functions for redSHOP
 *
 * @since  1.5
 */
class RedshopHelperUtility
{
	/**
	 * The dispatcher.
	 *
	 * @var  JEventDispatcher
	 */
	public static $dispatcher = null;

	/**
	 * Get SSL link for backend or applied for ssl link
	 *
	 * @param   string   $link      Link to be converted into ssl
	 * @param   integer  $applySSL  SSL should be apply or not
	 *
	 * @return  string   Return converted
	 */
	public static function getSSLLink($link, $applySSL = 1)
	{
		$link = JUri::getInstance(JUri::base() . $link);

		if (Redshop::getConfig()->get('SSL_ENABLE_IN_BACKEND') && $applySSL)
		{
			$link->setScheme('https');
		}
		else
		{
			$link->setScheme('http');
		}

		return $link;
	}

	/**
	 * Get the event dispatcher
	 *
	 * @return  JEventDispatcher
	 */
	public static function getDispatcher()
	{
		if (!self::$dispatcher)
		{
			self::$dispatcher = version_compare(JVERSION, '3.0', 'lt') ? JDispatcher::getInstance() : JEventDispatcher::getInstance();
		}

		return self::$dispatcher;
	}

	/**
	 * Quote an array of values.
	 *
	 * @param   array  $values  The values.
	 *
	 * @return  array  The quoted values
	 */
	public static function quote(array $values)
	{
		$db = JFactory::getDbo();

		return array_map(
			function ($value) use ($db) {
				return $db->quote($value);
			},
			$values
		);
	}

	/**
	 * Method for convert utf8 string with special chars to normal ASCII char.
	 *
	 * @param   string   $text         String for convert
	 * @param   boolean  $isUrlEncode  Target for convert. True for url alias, False for normal.
	 *
	 * @return  string         Normal ASCI string.
	 *
	 * @since  2.0.3
	 */
	public static function convertToNonSymbol($text = '', $isUrlEncode = true)
	{
		if (empty($text))
		{
			return '';
		}

		if ($isUrlEncode === false)
		{
			return Transliterator::utf8ToAscii($text);
		}

		return Transliterator::transliterate($text);
	}

	/**
	 * Build the list representing the menu tree
	 *
	 * @param   integer  $id        Id of the menu item
	 * @param   string   $indent    The indentation string
	 * @param   array    $list      The list to process
	 * @param   array    &$childs   The children of the current item
	 * @param   integer  $maxLevel  The maximum number of levels in the tree
	 * @param   integer  $level     The starting level
	 * @param   string   $key       The name of primary key.
	 * @param   string   $nameKey   The name of key for item title.
	 * @param   string   $spacer    Spacer for sub-item.
	 *
	 * @return  array
	 *
	 * @since   1.5
	 */
	public static function createTree($id, $indent, $list, &$childs, $maxLevel = 9999, $level = 0, $key = 'id', $nameKey = 'title',
		$spacer = '&#160;&#160;&#160;&#160;&#160;&#160;')
	{
		if (empty($childs[$id]) || $level > $maxLevel)
		{
			return $list;
		}

		foreach ($childs[$id] as $item)
		{
			$nextId = $item->{$key};
			$itemIndent = ($item->parent_id > 0) ? str_repeat($spacer, $level) . $indent : '';

			$list[$nextId] = $item;
			$list[$nextId]->treename = $itemIndent . $item->{$nameKey};
			$list[$nextId]->indent   = $itemIndent;
			$list[$nextId]->children = count(@$childs[$nextId]);
			$list = static::createTree($nextId, $indent, $list, $childs, $maxLevel, $level + 1, $key, $nameKey, $spacer);
		}

		return $list;
	}

	/**
	 * Convert associative array into attributes.
	 * Example:
	 * 		array('size' => '50', 'name' => 'myfield')
	 * 	would be:
	 * 		size="50" name="myfield"
	 *
	 * @param   array  $array  Associative array to convert
	 *
	 * @return  string
	 */
	public static function toAttributes(array $array)
	{
		$attributes = '';

		foreach ($array as $attribute => $value)
		{
			if (null !== $value)
			{
				$attributes .= ' ' . $attribute . '="' . (string) $value . '"';
			}
		}

		return trim($attributes);
	}

	/**
	 * We are using file for saving configuration variables
	 * We need some variables that can be uses as dynamically
	 * Here is the logic to define that variables
	 *
	 * IMPORTANT: we need to call this function in plugin or module manually to see the effect of this variables
	 *
	 * @return  void
	 */
	public static function defineDynamicVariables()
	{
		$config = Redshop::getConfig();

		$config->set('SHOW_PRICE', self::showPrice());
		$config->set('USE_AS_CATALOG', self::getCatalog());

		$quotationModePre = (int) $config->get('DEFAULT_QUOTATION_MODE_PRE');

		$config->set('DEFAULT_QUOTATION_MODE', $quotationModePre);

		if ($quotationModePre == 1)
		{
			$config->set('DEFAULT_QUOTATION_MODE', (int) self::setQuotationMode());
		}
	}

	/**
	 * Define "Show Price" dynamic vars
	 *
	 * @return  int
	 */
	protected static function showPrice()
	{
		$user           = JFactory::getUser();
		$userHelper     = rsUserHelper::getInstance();
		$shopperGroupId = RedshopHelperUser::getShopperGroup($user->id);
		$shopperGroups  = $userHelper->getShopperGroupList($shopperGroupId);

		if (empty($shopperGroups))
		{
			return Redshop::getConfig()->get('SHOW_PRICE_PRE');
		}

		$shopperGroups = $shopperGroups[0];

		if (($shopperGroups->show_price == "yes") || ($shopperGroups->show_price == "global" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1)
			|| ($shopperGroups->show_price == "" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1))
		{
			return 1;
		}

		return 0;
	}

	/**
	 * Define catalog variables
	 *
	 * @return  int
	 */
	protected static function getCatalog()
	{
		$user           = JFactory::getUser();
		$userHelper     = rsUserHelper::getInstance();
		$shopperGroupId = RedshopHelperUser::getShopperGroup($user->id);
		$shopperGroup   = $userHelper->getShopperGroupList($shopperGroupId);

		if (empty($shopperGroups))
		{
			return Redshop::getConfig()->get('PRE_USE_AS_CATALOG');
		}

		$shopperGroup = $shopperGroup[0];

		if ($shopperGroup->use_as_catalog == "yes"
			|| ($shopperGroup->use_as_catalog == "global" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1)
			|| ($shopperGroup->use_as_catalog == "" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1))
		{
			return 1;
		}

		return 0;
	}

	/**
	 * Method for get quotation mode.
	 *
	 * @return  bool
	 *
	 * @since   2.0.6
	 */
	protected static function setQuotationMode()
	{
		$db             = JFactory::getDbo();
		$user           = JFactory::getUser();
		$shopperGroupId = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

		if ($user->id)
		{
			$userShopperGroupId = RedshopHelperUser::getShopperGroup($user->id);

			if ($userShopperGroupId)
			{
				$shopperGroupId = $userShopperGroupId;
			}
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('shopper_group_id') . ' = ' . $shopperGroupId);

		$shopperGroupData = $db->setQuery($query)->loadObject();

		if ($shopperGroupData)
		{
			if ($shopperGroupData->shopper_group_quotation_mode)
			{
				return true;
			}

			return false;
		}

		return Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE_PRE');
	}

	/**
	 * Method for limit chars
	 *
	 * @param   string  $desc      Description
	 * @param   int     $maxChars  Maximum chars
	 * @param   string  $suffix    Suffix
	 *
	 * @return string
	 *
	 * @since   2.0.6
	 */
	public static function maxChars($desc = '', $maxChars = 0, $suffix = '')
	{
		$maxChars = (int) $maxChars;

		if (!$maxChars)
		{
			return $desc;
		}

		return self::limitText($desc, $maxChars, $suffix);
	}

	/**
	 * Method for sub-string with length.
	 *
	 * @param   string   $text          Text for sub-string
	 * @param   int      $length        Maximum chars
	 * @param   string   $ending        Ending text
	 * @param   boolean  $exact         Exact
	 * @param   boolean  $considerHtml  Consider HTML
	 *
	 * @return string
	 *
	 * @since   2.0.6
	 */
	public static function limitText($text, $length = 50, $ending = '...', $exact = false, $considerHtml = true)
	{
		$openTags = array();

		if ($considerHtml)
		{
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
			{
				return $text;
			}

			$totalLength = strlen(strip_tags($ending));
			$truncate    = '';

			preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);

			foreach ($tags as $tag)
			{
				if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2]))
				{
					if (preg_match('/<[\w]+[^>]*>/s', $tag[0]))
					{
						array_unshift($openTags, $tag[2]);
					}

					elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag))
					{
						$pos = array_search($closeTag[1], $openTags);

						if ($pos !== false)
						{
							array_splice($openTags, $pos, 1);
						}
					}
				}

				$truncate .= $tag[1];

				$contentLength = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));

				if ($contentLength + $totalLength > $length)
				{
					$left           = $length - $totalLength;
					$entitiesLength = 0;

					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE))
					{
						foreach ($entities[0] as $entity)
						{
							if ($entity[1] + 1 - $entitiesLength <= $left)
							{
								$left--;
								$entitiesLength += strlen($entity[0]);
							}
							else
							{
								break;
							}
						}
					}

					$truncate .= substr($tag[3], 0, $left + $entitiesLength);
					break;
				}
				else
				{
					$truncate    .= $tag[3];
					$totalLength = $contentLength;
				}

				if ($totalLength >= $length)
				{
					break;
				}
			}
		}
		else
		{
			if (strlen($text) <= $length)
			{
				return $text;
			}
			else
			{
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}

		if (!$exact)
		{
			$spacePosition = strrpos($truncate, ' ');

			if ($spacePosition > -1)
			{
				if ($considerHtml)
				{
					$bits = substr($truncate, $spacePosition);
					preg_match_all('/<\/([a-z])>/', $bits, $droppedTags, PREG_SET_ORDER);

					if (!empty($droppedTags))
					{
						foreach ($droppedTags as $closingTag)
						{
							if (!in_array($closingTag[1], $openTags))
							{
								array_unshift($openTags, $closingTag[1]);
							}
						}
					}
				}

				$truncate = substr($truncate, 0, $spacePosition);
			}
		}

		$truncate .= $ending;

		if ($considerHtml)
		{
			foreach ($openTags as $tag)
			{
				$truncate .= '</' . $tag . '>';
			}
		}

		return $truncate;
	}

	/**
	 * Method for check country in EU area or not
	 *
	 * @param   string  $country  Country code
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public static function isCountryInEurope($country)
	{
		$euCountries = array('AUT', 'BGR', 'BEL', 'CYP', 'CZE', 'DEU', 'DNK', 'ESP', 'EST',
			'FIN', 'FRA', 'FXX', 'GBR', 'GRC', 'HUN', 'IRL', 'ITA', 'LVA', 'LTU',
			'LUX', 'MLT', 'NLD', 'POL', 'PRT', 'ROM', 'SVK', 'SVN', 'SWE');

		return in_array($country, $euCountries);
	}
}
