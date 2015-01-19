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
JLoader::load('RedshopHelperUser');

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
	 * @return  null
	 */
	public function onPrepareProduct(&$template, &$params, $product)
	{
		$app               = JFactory::getApplication();
		$user              = JFactory::getUser();
		$user_id           = $user->id;
		$rsUserhelper      = new rsUserhelper;

		$shopperGroupId    = $rsUserhelper->getShopperGroup($user_id);
		$shopperGroupdata  = $rsUserhelper->getShopperGroupList($shopperGroupId);
		$shoppergroup_name = $shopperGroupdata[0]->shopper_group_name;

		$start_shoppergroup = $this->getStringPosition($template, '{if shoppergroup::');
		$end_shoppergroup   = $this->getStringPosition($template, '{shoppergroup end if}');

		if (count($start_shoppergroup) == count($end_shoppergroup) && count($start_shoppergroup) > 0 && count($end_shoppergroup) > 0)
		{
			for ($r = 0; $r < count($start_shoppergroup); $r++)
			{
				$main_substr = substr($template, $start_shoppergroup[$r], ($end_shoppergroup[$r] - $start_shoppergroup[$r]));
				$main_substr_pos = strpos($main_substr, "::");
				$main_substr_pos_next = strpos($main_substr, "}", $main_substr_pos);
				$main_substr_blog = substr($main_substr, ($main_substr_pos + 2), ($main_substr_pos_next - ($main_substr_pos + 2)));
				$main_substr_blog_exp = explode(",", $main_substr_blog);

				if (count($main_substr_blog_exp) > 0)
				{
					$main_string1 = "{if shoppergroup::" . $main_substr_blog . "}";
					$main_string_sp1 = explode($main_string1, $main_substr);

					if (in_array($shoppergroup_name, $main_substr_blog_exp))
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
			}

			$template = str_replace("{shoppergroup end if}", "", $template);
		}

		return;
	}

	/**
	 * Get String Position
	 *
	 * @param   string  $haystack  Haystack scope
	 * @param   string  $needle    Needle to match string
	 *
	 * @return  integer  String position
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
}
