<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
require_once JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'user.php';
class plgredshop_productshoppergroup_tags extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgredshop_productshoppergroup_tags(&$subject)
	{
		parent::__construct($subject);

		// Load plugin parameters
		$this->_plugin = JPluginHelper::getPlugin('redshop_product', 'onPrepareProduct');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param    object        The Product Template Data
	 * @param    object        The product params
	 * @param    object        The product object
	 */
	function onPrepareProduct(&$template, &$params, $product)
	{
		$app = & JFactory::getApplication();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$rsUserhelper = new rsUserhelper;

		$shopperGroupId = $rsUserhelper->getShopperGroup($user_id);
		$shopperGroupdata = $rsUserhelper->getShopperGroupList($shopperGroupId);
		$shoppergroup_name = $shopperGroupdata[0]->shopper_group_name;

		$start_shoppergroup = $this->strpos_r($template, '{if shoppergroup::');
		$end_shoppergroup = $this->strpos_r($template, '{shoppergroup end if}');

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

		// Plugin code goes here.
		return;
	}

	function strpos_r($haystack, $needle)
	{
		if (strlen($needle) > strlen($haystack))
			trigger_error(sprintf("%s: length of argument 2 must be <= argument 1", __FUNCTION__), E_USER_WARNING);

		$seeks = array();
		while ($seek = strrpos($haystack, $needle))
		{
			array_push($seeks, $seek);
			$haystack = substr($haystack, 0, $seek);
		}

		return $seeks;
	}

	/**
	 * Example after display title method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The product params
	 * @param    int            The product object
	 *
	 * @return    string
	 */
	function onAfterDisplayProductTitle(&$template, &$params, $product)
	{
		$string = "";

		return $string;
	}

	/**
	 * Example before display redSHOP Product method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The product params
	 * @param    int            The product object
	 *
	 * @return    string
	 */
	function onBeforeDisplayProduct(&$template, &$params, $product)
	{
		$string = "";

		return $string;
	}

	/**
	 * Example after display redSHOP Product method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param    object         The Product Template Data
	 * @param    object         The Product params
	 * @param    int            The product object
	 *
	 * @return    string
	 */
	function onAfterDisplayProduct(&$template, &$params, $product)
	{
		$string = "";

		return $string;
	}

	/**
	 * Example before save Product method
	 *
	 * Method is called right before product is saved into the database.
	 * Product object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 *    You can set the error by calling $product->setError($message)
	 *
	 * @param    object        A JTableproduct_detail object
	 * @param    bool          If the product is just about to be created
	 *
	 * @return    bool        If false, abort the save
	 */
	function onBeforeProductSave(&$product, $isnew)
	{
		return true;
	}

	/**
	 * Example after save product method
	 * Product is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the product is saved
	 *
	 *
	 * @param    object        A JTableproduct_detail object
	 * @param    bool          If the product is just about to be created
	 *
	 * @return    void
	 */
	function onAfterProductSave(&$product, $isnew)
	{
		return;
	}
}

?>