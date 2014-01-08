<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Including redshop product helper file and configuration file
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

class plgAcymailingRedshop extends JPlugin
{
	public function plgAcymailingRedshop(&$subject, $config)
	{
		parent::__construct($subject, $config);

		if (!isset($this->params))
		{
			$plugin =& JPluginHelper::getPlugin('acymailing', 'redshop');
			$this->params = new JRegistry($plugin->params);
		}
	}

	public function acymailing_getPluginType()
	{
		$onePlugin = null;
		$onePlugin->name = JText::_('COM_REDSHOP_redSHOP');
		$onePlugin->function = 'acymailingredSHOP_show';
		$onePlugin->help = 'plugin-redSHOP';

		return $onePlugin;
	}

	public function acymailingredSHOP_show()
	{
		$db = JFactory::getDbo();
		$query = "SELECT p.product_id,p.product_name,c.category_id,c.category_name FROM "
			. " #__redshop_product AS p,#__redshop_category AS c,#__redshop_product_category_xref AS pc"
			. " WHERE pc.category_id=c.category_id AND p.product_id=pc.product_id";
		$db->setQuery($query);

		$rs = $db->loadObjectlist();
		$text = '<table class="adminlist" cellpadding="1">';
		$text .= '<tr style="cursor:pointer" ><th>' . JText::_('COM_REDSHOP_PRODUCT_NAME') . '</th><th>'
			. JText::_('COM_REDSHOP_CATEGORY_NAME') . '</th></tr>';
		$k = 0;

		for ($i = 0; $i < count($rs); $i++)
		{
			$row = & $rs[$i];
			$text .= '<tr style="cursor:pointer" class="row' . $k . '" onclick="setTag(\'{product:'
				. $row->product_id . '}\');insertTag();" ><td>' . $row->product_name . '</td><td>'
				. $row->category_name . '</td></tr>';
			$k = 1 - $k;
		}

		$text .= '</table>';
		echo $text;
	}

	public function acymailing_replaceusertagspreview(&$email)
	{
		return $this->acymailing_replaceusertags($email);
	}

	public function acymailing_replaceusertags(&$email)
	{
		$match = '#{product:?([^:]*)}#Ui';
		$variables = array('subject', 'body', 'altbody');
		$found = false;
		$results = array();

		foreach ($variables as $var)
		{
			if (empty($email->$var))
			{
				continue;
			}

			$found = preg_match_all($match, $email->$var, $results[$var]) || $found;

			if (empty($results[$var][0]))
			{
				unset($results[$var]);
			}
		}

		if (!$found)
		{
			return;
		}

		$tags = array();

		foreach ($results as $var => $allresults)
		{
			foreach ($allresults[0] as $i => $oneTag)
			{
				if (isset($tags[$oneTag]))
				{
					continue;
				}

				if (is_numeric($allresults[1][$i]))
				{
					$tags[$oneTag] = $this->getProduct($allresults[1][$i]);
				}
			}
		}

		foreach (array_keys($results) as $var)
		{
			$email->$var = str_replace(array_keys($tags), $tags, $email->$var);
		}
	}

	/**
	 * Get redSHOP product information for Tag Replacement.
	 *
	 * @param   int  $product_id  The product ID
	 *
	 * @return mixed  Product Main Image,Product Name,Product Formatted Price
	 */
	public function getProduct($product_id)
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/template.php';
		$redTemplate = new Redtemplate;

		$prtemplate_id = trim($this->params->get('product_template', 1));
		$prtemplate = $redTemplate->getTemplate('product_content_template', $prtemplate_id);

		// Get Product Data
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__redshop_product WHERE product_id=" . $product_id;
		$db->setQuery($query);
		$rs = $db->loadObject();

		// Product helper Object
		$producthelper = new producthelper;

		// Get Product Formatted price as per redshop configuration
		$productArr = $producthelper->getProductNetPrice($product_id);
		$price = $productArr['productPrice'] + $productArr['productVat'];
		$price = $producthelper->getProductFormattedPrice($price);

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id);

		// Get product Image
		$productImage = $producthelper->getProductImage($product_id, $link, PRODUCT_MAIN_IMAGE, PRODUCT_MAIN_IMAGE_HEIGHT, PRODUCT_DETAIL_IS_LIGHTBOX);

		$text = "<div>" . $productImage . "</div><div>" . $rs->product_name . "</div><div>" . $price . "</div>";

		if ($prtemplate[0]->template_desc)
		{
			$text = $prtemplate[0]->template_desc;

			$text = str_replace("{product_thumb_image}", $productImage, $text);
			$text = str_replace("{product_name}", $rs->product_name, $text);
			$text = str_replace("{product_price}", $price, $text);

			$text = str_replace("{read_more}", "", $text);
			$text = str_replace("{product_desc}", "", $text);

			// Replace attribute template to null
			$attribute_tag_arr = explode("attribute_template:", $text);
			$attribute_tag_arr = explode("}", $attribute_tag_arr[1]);
			$attribute_tag = "{attribute_template:" . $attribute_tag_arr[0] . "}";
			$text = str_replace($attribute_tag, "", $text);

			// Replace add to cart template to null
			$cart_tag_arr = explode("form_addtocart:", $text);
			$cart_tag_arr = explode("}", $cart_tag_arr[1]);
			$cart_tag = "{form_addtocart:" . $cart_tag_arr[0] . "}";
			$text = str_replace($cart_tag, "", $text);
		}

		return $text;
	}
}
