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
	 * @var   array
	 *
	 * @since  2.0.6
	 */
	protected static $menuItems;

	/**
	 * @var   array
	 *
	 * @since  2.0.6
	 */
	protected static $menuItemAssociation = array();

	/**
	 * The dispatcher.
	 *
	 * @var  JEventDispatcher
	 */
	public static $dispatcher = null;

	/**
	 * @var  boolean
	 */
	protected static $isRedProductFinder;

	/**
	 * Get SSL link for backend or applied for ssl link
	 *
	 * @param   string  $link     Link to be converted into ssl
	 * @param   integer $applySSL SSL should be apply or not
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
	 * @param   array $values The values.
	 *
	 * @return  array  The quoted values
	 */
	public static function quote(array $values)
	{
		$db = JFactory::getDbo();

		return array_map(
			function ($value) use ($db)
			{
				return $db->quote($value);
			},
			$values
		);
	}

	/**
	 * Quote name an array of values.
	 *
	 * @param   array $values The values.
	 *
	 * @return  array           The quoted values
	 *
	 * @since   2.0.6
	 */
	public static function quoteName(array $values)
	{
		$db = JFactory::getDbo();

		return array_map(
			function ($value) use ($db)
			{
				return $db->qn($value);
			},
			$values
		);
	}

	/**
	 * Method for convert utf8 string with special chars to normal ASCII char.
	 *
	 * @param   string  $text        String for convert
	 * @param   boolean $isUrlEncode Target for convert. True for url alias, False for normal.
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
	 * @param   integer $id       Id of the menu item
	 * @param   string  $indent   The indentation string
	 * @param   array   $list     The list to process
	 * @param   array   &$childs  The children of the current item
	 * @param   integer $maxLevel The maximum number of levels in the tree
	 * @param   integer $level    The starting level
	 * @param   string  $key      The name of primary key.
	 * @param   string  $nameKey  The name of key for item title.
	 * @param   string  $spacer   Spacer for sub-item.
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
			$nextId     = $item->{$key};
			$itemIndent = ($item->parent_id > 0) ? str_repeat($spacer, $level) . $indent : '';

			$list[$nextId]           = $item;
			$list[$nextId]->treename = $itemIndent . $item->{$nameKey};
			$list[$nextId]->indent   = $itemIndent;
			$list[$nextId]->children = count(@$childs[$nextId]);
			$list                    = static::createTree($nextId, $indent, $list, $childs, $maxLevel, $level + 1, $key, $nameKey, $spacer);
		}

		return $list;
	}

	/**
	 * Convert associative array into attributes.
	 * Example:
	 *        array('size' => '50', 'name' => 'myfield')
	 *    would be:
	 *        size="50" name="myfield"
	 *
	 * @param   array $array Associative array to convert
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
	 * @return  integer
	 */
	protected static function showPrice()
	{
		$user           = JFactory::getUser();
		$userHelper     = rsUserHelper::getInstance();
		$shopperGroupId = RedshopHelperUser::getShopperGroup($user->id);
		$shopperGroups  = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);

		if (empty($shopperGroups))
		{
			return Redshop::getConfig()->get('SHOW_PRICE_PRE');
		}

		$shopperGroups = $shopperGroups[0];

		if (($shopperGroups->show_price == "yes") || ($shopperGroups->show_price == "global" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1)
			|| ($shopperGroups->show_price == "" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1)
		)
		{
			return 1;
		}

		return 0;
	}

	/**
	 * Define catalog variables
	 *
	 * @return  integer
	 */
	protected static function getCatalog()
	{
		$user           = JFactory::getUser();
		$shopperGroupId = RedshopHelperUser::getShopperGroup($user->id);
		$shopperGroup   = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);

		if (empty($shopperGroups))
		{
			return Redshop::getConfig()->get('PRE_USE_AS_CATALOG');
		}

		$shopperGroup = $shopperGroup[0];

		if ($shopperGroup->use_as_catalog == "yes"
			|| ($shopperGroup->use_as_catalog == "global" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1)
			|| ($shopperGroup->use_as_catalog == "" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1)
		)
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
	 * @param   string $desc     Description
	 * @param   int    $maxChars Maximum chars
	 * @param   string $suffix   Suffix
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
	 * @param   string  $text         Text for sub-string
	 * @param   int     $length       Maximum chars
	 * @param   string  $ending       Ending text
	 * @param   boolean $exact        Exact
	 * @param   boolean $considerHtml Consider HTML
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
	 * @param   string $country Country code
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

	/**
	 * Set Operand For Values
	 *
	 * @param   float  $leftValue  Left value
	 * @param   string $operand    Operand
	 * @param   float  $rightValue Right value
	 *
	 * @return  float
	 *
	 * @since   2.0.6
	 */
	public static function setOperandForValues($leftValue, $operand, $rightValue)
	{
		switch ($operand)
		{
			case '+':
				$leftValue += $rightValue;
				break;
			case '-':
				$leftValue -= $rightValue;
				break;
			case '*':
				$leftValue *= $rightValue;
				break;
			case '/':
				$leftValue /= $rightValue;
				break;
		}

		return $leftValue;
	}

	/**
	 * Get Redshop Menu Items
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public static function getRedshopMenuItems()
	{
		if (is_null(self::$menuItems))
		{
			self::$menuItems = JFactory::getApplication()->getMenu()->getItems('component', 'com_redshop');
		}

		return self::$menuItems;
	}

	/**
	 * Add item to cart from db ...
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function databaseToCart()
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$user    = JFactory::getUser();

		if ($user->id && !isset($cart['idx']))
		{
			RedshopHelperCart::databaseToCart();
		}
	}

	/**
	 * Get plugins
	 *
	 * @param   string $folder  Group of plugins
	 * @param   string $enabled -1: All, 0: not enable, 1: enabled
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public static function getPlugins($folder = 'redshop', $enabled = null)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from('#__extensions')
			->where('LOWER(' . $db->qn('folder') . ') = ' . $db->quote(strtolower($folder)))
			->order($db->qn('ordering') . ' ASC');

		if (!is_null($enabled))
		{
			$query->where($db->qn('enabled') . ' = ' . $db->quote($enabled));
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Method for get modules
	 *
	 * @param   string $enabled [-1: All, 0: not enable, 1: enabled]
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public static function getModules($enabled = '1')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$oldStyleName = array(
			'mod_redcategoryscroller', 'mod_redmasscart', 'mod_redfeaturedproduct', 'mod_redproducts3d', 'mod_redproductscroller',
			'mod_redproducttab', 'mod_redmanufacturer'
		);

		$query->select('*')
			->from('#__extensions')
			->where($db->qn('type') . ' = ' . $db->quote('module'))
			->where(
				'LOWER(' . $db->qn('element') . ') LIKE ' . $db->quote('mod_redshop%')
				. ' OR LOWER(' . $db->qn('element') . ') IN (' . implode(',', self::quote($oldStyleName)) . ')'
			)
			->order($db->qn('ordering') . ' ASC');

		if ($enabled > 0)
		{
			$query->where($db->qn('enabled') . ' = ' . $db->q($enabled));
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Check Menu Query
	 *
	 * @param   object $oneMenuItem Values current menu item
	 * @param   array  $queryItems  Name query check
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public static function checkMenuQuery($oneMenuItem, $queryItems)
	{
		if (empty($oneMenuItem) || empty($queryItems))
		{
			return false;
		}

		foreach ($queryItems as $key => $value)
		{
			if (!isset($oneMenuItem->query[$key])
				|| (is_array($value) && !in_array($oneMenuItem->query[$key], $value))
				|| (!is_array($value) && $oneMenuItem->query[$key] != $value)
			)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Get RedShop Menu Item
	 *
	 * @param   array $queryItems Values query
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	public static function getRedShopMenuItem($queryItems)
	{
		$serializeItem = md5(serialize($queryItems));

		if (!array_key_exists($serializeItem, self::$menuItemAssociation))
		{
			self::$menuItemAssociation[$serializeItem] = false;

			foreach (self::getRedshopMenuItems() as $oneMenuItem)
			{
				if (self::checkMenuQuery($oneMenuItem, $queryItems))
				{
					self::$menuItemAssociation[$serializeItem] = $oneMenuItem->id;
					break;
				}
			}
		}

		return self::$menuItemAssociation[$serializeItem];
	}

	/**
	 * Get Item Id
	 *
	 * @param   int $productId  Product Id
	 * @param   int $categoryId Category Id
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	public static function getItemId($productId = 0, $categoryId = 0)
	{
		// Get Itemid from Product detail
		if ($productId)
		{
			$result = self::getRedShopMenuItem(
				array('option' => 'com_redshop', 'view' => 'product', 'pid' => (int) $productId)
			);

			if ($result)
			{
				return $result;
			}
		}

		// Get Itemid from Category detail
		if ($categoryId)
		{
			$result = self::getCategoryItemid($categoryId);

			if ($result)
			{
				return $result;
			}
		}

		$input = JFactory::getApplication()->input;

		if ($input->getCmd('option', '') != 'com_redshop')
		{
			$result = self::getRedShopMenuItem(array('option' => 'com_redshop', 'view' => 'category'));

			if ($result)
			{
				return $result;
			}

			$result = self::getRedShopMenuItem(array('option' => 'com_redshop'));

			if ($result)
			{
				return $result;
			}
		}

		return $input->getInt('Itemid', 0);
	}

	/**
	 * Get Category Itemid
	 *
	 * @param   int $categoryId Category id
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	public static function getCategoryItemid($categoryId = 0)
	{
		if ($categoryId)
		{
			$categories = explode(',', $categoryId);

			if ($categories)
			{
				foreach ($categories as $category)
				{
					$result = self::getRedShopMenuItem(
						array('option' => 'com_redshop', 'view' => 'category', 'layout' => 'detail', 'cid' => (int) $category)
					);

					if ($result)
					{
						return $result;
					}
				}
			}

			//Get from Parents
			$categories = RedshopHelperCategory::getCategoryListReverseArray($categoryId);

			if ($categories)
			{
				foreach ($categories as $category)
				{
					self::getCategoryItemid($category->id);

					if ($result)
					{
						return $result;
					}
				}
			}
		}
		else
		{
			$result = self::getRedShopMenuItem(array('option' => 'com_redshop', 'view' => 'category'));

			if ($result)
			{
				return $result;
			}
		}

		return null;
	}

	/**
	 * Method for convert array of string
	 *
	 * @param   array $data Language array
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	public static function convertLanguageString($data)
	{
		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$txt   = $data[$i]->text;
			$ltext = JText::_($txt);

			if ($ltext != $txt)
			{
				$data[$i]->text = $ltext;
			}
			elseif ($data[$i]->country_jtext != "")
			{
				$data[$i]->text = $data[$i]->country_jtext;
			}
		}

		$tmpArray = array();

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$txt            = $data[$i]->text;
			$val            = $data[$i]->value;
			$tmpArray[$val] = $txt;
		}

		asort($tmpArray);
		$x = 0;

		foreach ($tmpArray AS $val => $txt)
		{
			$data[$x]->text  = $txt;
			$data[$x]->value = $val;
			$x++;
		}

		return $data;
	}

	/**
	 * Method for get order by list
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public static function getOrderByList()
	{
		return array(
			JHtml::_('select.option', 'name', JText::_('COM_REDSHOP_PRODUCT_NAME_ASC')),
			JHtml::_('select.option', 'name_desc', JText::_('COM_REDSHOP_PRODUCT_NAME_DESC')),
			JHtml::_('select.option', 'price', JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC')),
			JHtml::_('select.option', 'price_desc', JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC')),
			JHtml::_('select.option', 'number', JText::_('COM_REDSHOP_PRODUCT_NUMBER_ASC')),
			JHtml::_('select.option', 'number_desc', JText::_('COM_REDSHOP_PRODUCT_NUMBER_DESC')),
			JHtml::_('select.option', 'id', JText::_('COM_REDSHOP_NEWEST')),
			JHtml::_('select.option', 'ordering', JText::_('COM_REDSHOP_ORDERING_ASC')),
			JHtml::_('select.option', 'ordering_desc', JText::_('COM_REDSHOP_ORDERING_DESC'))
		);
	}

	/**
	 * Prepare order by object for ordering from string.
	 *
	 * @param   string $case Order By string generated in getOrderByList method
	 *
	 * @return  object         Parsed strings in ordering and direction object key.
	 *
	 * @since   2.0.6
	 */
	public static function prepareOrderBy($case)
	{
		$orderBy = new stdClass;

		switch ($case)
		{
			case 'name':
			default:
				$orderBy->ordering  = 'p.product_name';
				$orderBy->direction = 'ASC';

				break;
			case 'name_desc':
				$orderBy->ordering  = 'p.product_name';
				$orderBy->direction = 'DESC';

				break;
			case 'price':
				$orderBy->ordering  = 'p.product_price';
				$orderBy->direction = 'ASC';

				break;
			case 'price_desc':
				$orderBy->ordering  = 'p.product_price';
				$orderBy->direction = 'DESC';

				break;
			case 'number':
				$orderBy->ordering  = 'p.product_number';
				$orderBy->direction = 'ASC';

				break;
			case 'number_desc':
				$orderBy->ordering  = 'p.product_number';
				$orderBy->direction = 'DESC';

				break;
			case 'id':
				$orderBy->ordering  = 'p.product_id';
				$orderBy->direction = 'DESC';

				break;
			case 'ordering':
				$orderBy->ordering  = 'pc.ordering';
				$orderBy->direction = 'ASC';

				break;
			case 'ordering_desc':
				$orderBy->ordering  = 'pc.ordering';
				$orderBy->direction = 'DESC';

				break;
		}

		return $orderBy;
	}

	/**
	 * Method for get manufacturer order by list
	 *
	 * @return  array   List of order
	 *
	 * @since   2.0.6
	 */
	public static function getManufacturerOrderByList()
	{
		$order = array();

		$order[0]        = new stdClass;
		$order[0]->value = "mn.manufacturer_name ASC";
		$order[0]->text  = JText::_('COM_REDSHOP_ALPHABETICALLY');

		$order[1]        = new stdClass;
		$order[1]->value = "mn.manufacturer_id DESC";
		$order[1]->text  = JText::_('COM_REDSHOP_NEWEST');

		$order[2]        = new stdClass;
		$order[2]->value = "mn.ordering ASC";
		$order[2]->text  = JText::_('COM_REDSHOP_ORDERING');

		return $order;
	}

	/**
	 * Method for get product related order by list
	 *
	 * @return  array   List of order
	 *
	 * @since   2.0.6
	 */
	public static function getRelatedOrderByList()
	{
		$order = array();

		$order[0]        = new stdClass;
		$order[0]->value = "p.product_name ASC";
		$order[0]->text  = JText::_('COM_REDSHOP_PRODUCT_NAME_ASC');

		$order[1]        = new stdClass;
		$order[1]->value = "p.product_name DESC";
		$order[1]->text  = JText::_('COM_REDSHOP_PRODUCT_NAME_DESC');

		$order[2]        = new stdClass;
		$order[2]->value = "p.product_price ASC";
		$order[2]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC');

		$order[3]        = new stdClass;
		$order[3]->value = "p.product_price DESC";
		$order[3]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC');

		$order[4]        = new stdClass;
		$order[4]->value = "p.product_number ASC";
		$order[4]->text  = JText::_('COM_REDSHOP_PRODUCT_NUMBER_ASC');

		$order[5]        = new stdClass;
		$order[5]->value = "p.product_number DESC";
		$order[5]->text  = JText::_('COM_REDSHOP_PRODUCT_NUMBER_DESC');

		$order[6]        = new stdClass;
		$order[6]->value = "r.ordering ASC";
		$order[6]->text  = JText::_('COM_REDSHOP_ORDERING_ASC');

		$order[7]        = new stdClass;
		$order[7]->value = "r.ordering DESC";
		$order[7]->text  = JText::_('COM_REDSHOP_ORDERING_DESC');

		if (self::isRedProductFinder())
		{
			$order[8]        = new stdClass;
			$order[8]->value = "e.data_txt ASC";
			$order[8]->text  = JText::_('COM_REDSHOP_DATEPICKER_ASC');

			$order[9]        = new stdClass;
			$order[9]->value = "e.data_txt DESC";
			$order[9]->text  = JText::_('COM_REDSHOP_DATEPICKER_DESC');
		}

		return $order;
	}

	/**
	 * Method for get accessory order by list
	 *
	 * @return  array   List of order
	 *
	 * @since   2.0.6
	 */
	public static function getAccessoryOrderByList()
	{
		$order = array();

		$order[0]        = new stdClass;
		$order[0]->value = "child_product_id ASC";
		$order[0]->text  = JText::_('COM_REDSHOP_PRODUCT_ID_ASC');

		$order[1]        = new stdClass;
		$order[1]->value = "child_product_id DESC";
		$order[1]->text  = JText::_('COM_REDSHOP_PRODUCT_ID_DESC');

		$order[2]        = new stdClass;
		$order[2]->value = "accessory_id ASC";
		$order[2]->text  = JText::_('COM_REDSHOP_ACCESSORY_ID_ASC');

		$order[3]        = new stdClass;
		$order[3]->value = "accessory_id DESC";
		$order[3]->text  = JText::_('COM_REDSHOP_ACCESSORY_ID_DESC');

		$order[4]        = new stdClass;
		$order[4]->value = "newaccessory_price ASC";
		$order[4]->text  = JText::_('COM_REDSHOP_ACCESSORY_PRICE_ASC');

		$order[5]        = new stdClass;
		$order[5]->value = "newaccessory_price DESC";
		$order[5]->text  = JText::_('COM_REDSHOP_ACCESSORY_PRICE_DESC');

		$order[6]        = new stdClass;
		$order[6]->value = "ordering ASC";
		$order[6]->text  = JText::_('COM_REDSHOP_ORDERING_ASC');

		$order[7]        = new stdClass;
		$order[7]->value = "ordering DESC";
		$order[7]->text  = JText::_('COM_REDSHOP_ORDERING_DESC');

		return $order;
	}

	/**
	 * Method for get pre-order by list
	 *
	 * @return  array   List of order
	 *
	 * @since   2.0.6
	 */
	public static function getPreOrderByList()
	{
		$preOrder = array();

		$preOrder[0]        = new stdClass;
		$preOrder[0]->value = "global";
		$preOrder[0]->text  = JText::_('COM_REDSHOP_GLOBAL');

		$preOrder[1]        = new stdClass;
		$preOrder[1]->value = "yes";
		$preOrder[1]->text  = JText::_('COM_REDSHOP_YES');

		$preOrder[2]        = new stdClass;
		$preOrder[2]->value = "no";
		$preOrder[2]->text  = JText::_('COM_REDSHOP_NO');

		return $preOrder;
	}

	/**
	 * Method for get child product order by list
	 *
	 * @return  array   List of order
	 *
	 * @since   2.0.6
	 */
	public static function getChildProductOption()
	{
		$childProduct = array();

		$childProduct[0]        = new stdClass;
		$childProduct[0]->value = "product_name";
		$childProduct[0]->text  = JText::_('COM_REDSHOP_CHILD_PRODUCT_NAME');

		$childProduct[1]        = new stdClass;
		$childProduct[1]->value = "product_number";
		$childProduct[1]->text  = JText::_('COM_REDSHOP_CHILD_PRODUCT_NUMBER');

		return $childProduct;
	}

	/**
	 * Method for get child product order by list
	 *
	 * @return  array   List of order
	 *
	 * @since   2.0.6
	 */
	public static function getStateAbbreviationsByList()
	{
		$stateData = array();

		$stateData[0]        = new stdClass;
		$stateData[0]->value = "2";
		$stateData[0]->text  = JText::_('COM_REDSHOP_TWO_LETTER_ABBRIVATION');

		$stateData[1]        = new stdClass;
		$stateData[1]->value = "3";
		$stateData[1]->text  = JText::_('COM_REDSHOP_THREE_LETTER_ABBRIVATION');

		return $stateData;
	}

	/**
	 * Method for get menu item id of checkout page
	 *
	 * @return  integer
	 *
	 * @since   2.0.6
	 */
	public static function getCheckoutItemId()
	{
		$itemId       = Redshop::getConfig()->get('DEFAULT_CART_CHECKOUT_ITEMID');
		$shopperGroup = RedshopHelperUser::getShopperGroupData();

		if (count($shopperGroup) > 0 && $shopperGroup->shopper_group_cart_checkout_itemid != 0)
		{
			$itemId = $shopperGroup->shopper_group_cart_checkout_itemid;
		}

		if ($itemId == 0)
		{
			$itemId = JFactory::getApplication()->input->getInt('Itemid');
		}

		return $itemId;
	}

	/**
	 * Method for get menu item id of cart page
	 *
	 * @return  integer
	 *
	 * @since   2.0.6
	 */
	public static function getCartItemId()
	{
		$itemId           = Redshop::getConfig()->get('DEFAULT_CART_CHECKOUT_ITEMID');
		$shopperGroupData = RedshopHelperUser::getShopperGroupData();

		if (count($shopperGroupData) > 0 && $shopperGroupData->shopper_group_cart_itemid != 0)
		{
			$itemId = $shopperGroupData->shopper_group_cart_itemid;
		}

		return $itemId;
	}

	/**
	 * Method for check if ProductFinder is available or not.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public static function isRedProductFinder()
	{
		if (self::$isRedProductFinder === null)
		{
			// Get redshop from joomla component table
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->qn('enabled'))
				->from($db->qn('#__extensions'))
				->where('element = ' . $db->q('com_redproductfinder'));

			$redProductFinderPath = JPATH_ADMINISTRATOR . '/components/com_redproductfinder';

			if (!is_dir($redProductFinderPath) || $db->setQuery($query)->loadResult() == 0)
			{
				self::$isRedProductFinder = false;
			}
			else
			{
				self::$isRedProductFinder = true;
			}
		}

		return self::$isRedProductFinder;
	}

	/**
	 * Method for get Economic Account Group
	 *
	 * @param   integer  $accountGroupId  Account group ID
	 * @param   integer  $front           Is front or not
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public static function getEconomicAccountGroup($accountGroupId = 0, $front = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('ea.*')
			->select($db->qn('ea.accountgroup_id', 'value'))
			->select($db->qn('ea.accountgroup_name', 'text'))
			->from($db->qn('#__redshop_economic_accountgroup', 'ea'));

		if ($accountGroupId)
		{
			$query->where($db->qn('ea.accountgroup_id') . ' = ' . (int) $accountGroupId);
		}

		if ($front)
		{
			$query->where($db->qn('ea.published') . ' = 1');
		}

		return $db->setQuery($query)->loadObjectList();
	}
}
