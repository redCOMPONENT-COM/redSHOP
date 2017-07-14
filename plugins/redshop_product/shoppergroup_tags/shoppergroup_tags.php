<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Plugin will manage product template and description view
 *
 * @since  1.1.20
 */
class PlgRedshop_ProductShopperGroup_Tags extends JPlugin
{
	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param   string  &$template  The Product Template Data
	 * @param   object  &$params    The product params
	 * @param   object  $product    The product object
	 *
	 * @return  void
	 */
	public function onPrepareProduct(&$template, &$params, $product)
	{
		$this->stripShopperGroupTags($template);
	}

	/**
	 * Event run before replace data in "Cart" template.
	 * Called in view "Cart" and "Checkout"
	 *
	 * @param   string  &$cartTemplate  The Cart Template Data.
	 * @param   array   $cart           Cart data.
	 *
	 * @return  void
	 */
	public function onStartCartTemplateReplace(&$cartTemplate, $cart)
	{
		// Separate html code for {product_loop_start} and {product_loop_end}
		if (strpos($cartTemplate, '{product_loop_start}') !== false && strpos($cartTemplate, '{product_loop_end}') !== true)
		{
			$tempContent = explode('{product_loop_start}', $cartTemplate);
			$preContent = (count($tempContent) > 1) ? $tempContent[0] : '';
			$tempContent = $tempContent[count($tempContent) - 1];
			$tempContent = explode('{product_loop_end}', $tempContent);
			$subTemplate = $tempContent[0];
			$postContent = (count($tempContent) > 1) ? $tempContent[count($tempContent) - 1] : '';

			// Strip tags for sub-template of Product Loops
			$this->stripShopperGroupTags($subTemplate);

			$cartTemplate = $preContent . '{product_loop_start}' . $subTemplate . '{product_loop_end}' . $postContent;
		}

		$this->stripShopperGroupTags($cartTemplate);
	}

	/**
	 * Get String Position
	 *
	 * @param   string  $haystack  Haystack scope
	 * @param   string  $needle    Needle to match string
	 *
	 * @return  array              String position
	 */
	protected function getStringPosition($haystack, $needle)
	{
		if (strlen($needle) > strlen($haystack))
		{
			trigger_error(sprintf("%s: length of argument 2 must be <= argument 1", __FUNCTION__), E_USER_WARNING);
		}

		$seeks = array();

		while ($seek = strrpos($haystack, $needle))
		{
			array_push($seeks, $seek);

			$haystack = substr($haystack, 0, $seek);
		}

		return $seeks;
	}

	/**
	 * Method for strip HTML template code on specific shopper groups
	 *
	 * @param   string  &$template  Template HTML code.
	 *
	 * @return  void
	 *
	 * @since  1.6.0
	 */
	protected function stripShopperGroupTags(&$template)
	{
		$shopperGroupId   = RedshopHelperUser::getShopperGroup(JFactory::getUser()->id);
		$shopperGroupData = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);
		$shopperGroupName = $shopperGroupData[0]->shopper_group_name;

		$startShopperGroups = $this->getStringPosition($template, '{if shoppergroup::');
		$endShopperGroups   = $this->getStringPosition($template, '{shoppergroup end if}');

		// Get ShopperGroup parents
		$shopperGroupNames = array();

		if (!empty($shopperGroupData[0]->parent_id))
		{
			$this->getShoppergroup($shopperGroupNames, $shopperGroupData[0]->parent_id);
		}

		if (empty($startShopperGroups) || empty($endShopperGroups) || count($startShopperGroups) != count($endShopperGroups))
		{
			return;
		}

		for ($r = 0, $rn = count($startShopperGroups); $r < $rn; $r++)
		{
			$main_substr = substr($template, $startShopperGroups[$r], ($endShopperGroups[$r] - $startShopperGroups[$r]));
			$main_substr_pos = strpos($main_substr, "::");
			$main_substr_pos_next = strpos($main_substr, "}", $main_substr_pos);
			$main_substr_blog = substr($main_substr, ($main_substr_pos + 2), ($main_substr_pos_next - ($main_substr_pos + 2)));
			$main_substr_blog_exp = explode(",", $main_substr_blog);
			$main_substr_blog_exp = array_map('trim', $main_substr_blog_exp);

			if (empty($main_substr_blog_exp))
			{
				continue;
			}

			$main_string1 = "{if shoppergroup::" . $main_substr_blog . "}";
			$main_string_sp1 = explode($main_string1, $main_substr);
			$hasParentShopperGroups = array_intersect($shopperGroupNames, $main_substr_blog_exp);
			$hasParentShopperGroups = !empty($hasParentShopperGroups);

			if (in_array($shopperGroupName, $main_substr_blog_exp) || $hasParentShopperGroups)
			{
				$string_main1 = "{if shoppergroup::" . $main_substr_blog . "}";
				$template = str_replace($string_main1, "", $template);
			}
			else
			{
				$string_main1 = "{if shoppergroup::" . $main_substr_blog . "}" . $main_string_sp1[1] . "{shoppergroup end if}";
				$template = str_replace($string_main1, "", $template);
			}
		}

		$template = str_replace("{shoppergroup end if}", "", $template);
	}

	/**
	 * Method for prepare an list of shopper names with parent-child relation
	 *
	 * @param   array  &$shoppergroupNames  List of shopper group names with Reference.
	 * @param   int    $shopperGroupId      ID of shopper group
	 *
	 * @return  void
	 *
	 * @since  1.6.0
	 */
	protected function getShoppergroup(&$shoppergroupNames, $shopperGroupId = 0)
	{
		if (!$shopperGroupId)
		{
			return;
		}

		$shopperGroupData = Redshop\Helper\ShopperGroup::generateList($shopperGroupId);

		if (empty($shopperGroupData))
		{
			return;
		}

		$shoppergroupNames[] = $shopperGroupData[0]->shopper_group_name;

		if (!empty($shopperGroupData[0]->parent_id))
		{
			$this->getShoppergroup($shopperGroupData[0]->parent_id, $shoppergroupNames);
		}
	}
}
