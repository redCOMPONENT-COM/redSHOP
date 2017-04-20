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
 * Helper for 
 *
 * @package     Redshop.Site
 * @subpackage  PlgAcymailingRedshop
 * @since       1.5.3
 */
class PlgAcymailingRedshop extends JPlugin
{
	/**
	 * PlgAcymailingRedshop description
	 * 
	 * @param   string  &$subject  subject of mail
	 * @param   array   $config    [description]
	 *
	 * @return  void
	 */
	public function PlgAcymailingRedshop(&$subject, $config)
	{
		$lang  = JFactory::getLanguage();
		$lang->load('plg_acymailing_redshop', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * acymailing_getPluginType description
	 * 
	 * @return  object
	 */
	public function acymailing_getPluginType()
	{
		$onePlugin           = new stdClass;
		$onePlugin->name     = JText::_('PLG_ACYMAILING_REDSHOP_REDSHOP');
		$onePlugin->function = 'acymailingRedshopShow';
		$onePlugin->help     = 'plugin-redSHOP';

		return $onePlugin;
	}

	/**
	 * acymailingredSHOP_show description
	 * 
	 * @return  void  display email template
	 */
	public function acymailingRedshopShow()
	{
		$app = JFactory::getApplication();
		$paramBase = ACYMAILING_COMPONENT . '.tagredshop';
		$pageInfo = new stdClass;
		$pageInfo->elements = new stdClass;
		$pageInfo->limit = new stdClass;
		$pageInfo->filter = new stdClass;
		$pageInfo->filter->order = new stdClass;
		$pageInfo->limit->value = $app->getUserStateFromRequest($paramBase . '.list_limit', 'limit', $app->get('list_limit'), 'int');
		$pageInfo->limit->start = $app->getUserStateFromRequest($paramBase . '.limitstart', 'limitstart', 0, 'int');
		$pageInfo->search = $app->getUserStateFromRequest($paramBase . '.search', 'search', '', 'string');
		$pageInfo->search = JString::strtolower(trim($pageInfo->search));
		$pageInfo->filter->order->value = $app->getUserStateFromRequest($paramBase . ".filter_order", 'filter_order', 'p.product_id', 'cmd');
		$pageInfo->filter->order->dir = $app->getUserStateFromRequest($paramBase . ".filter_order_Dir", 'filter_order_Dir', 'desc', 'word');

		if (strtolower($pageInfo->filter->order->dir) !== 'desc')
		{
			$pageInfo->filter->order->dir = 'asc';
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(['p.product_id', 'p.product_name', 'p.product_number', 'c.category_id', 'c.category_name']))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pc.product_id'))
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('pc.category_id') . ' = ' . $db->qn('c.category_id'))
			->group($db->qn('p.product_id'))
			->order($pageInfo->filter->order->value . ' ' . $pageInfo->filter->order->dir);

		if (!empty($pageInfo->search))
		{
			$searchFields = array('p.product_name', 'p.product_id', 'p.product_number');
			$searchVal = '\'%' . acymailing_getEscaped($pageInfo->search, true) . '%\'';
			$query->where(implode(" LIKE " . $db->q($searchVal) . " OR ", $searchFields) . " LIKE " . $db->q("$searchVal"));
		}

		$rs = $db->setQuery($query, $pageInfo->limit->start, $pageInfo->limit->value)->loadObjectlist();

		$query->clear('select')
			->clear('limit')
			->clear('group')
			->select('COUNT(' . $db->qn('p.product_id') . ')');
		$pageInfo->elements->total = $db->setQuery($query)->loadResult();
		$pageInfo->elements->page = count($rs);

		jimport('joomla.html.pagination');
		$pagination = new JPagination($pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value);

		require_once JPluginHelper::getLayoutPath('content', 'pagenavigation');
	}

	/**
	 * [acymailing_replaceusertagspreview description]
	 * 
	 * @param   string  &$email  description
	 * 
	 * @return  mixed
	 */
	public function acymailingReplaceUserTagsPreview(&$email)
	{
		return $this->acymailingReplaceUserTags($email);
	}

	/**
	 * [acymailing_replaceusertags description]
	 * 
	 * @param   [string]  &$email  [description]
	 * 
	 * @return  [mixed]            [description]
	 */
	public function acymailingReplaceUserTags(&$email)
	{
		$match = '#{product(?:_name|_price|_thumb_image|):?([^:]*)}#Ui';
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
					$tags[$oneTag] = $this->getProduct($allresults[1][$i], $oneTag);
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
	 * @param   int     $productId  The product ID
	 * @param   string  $tag        The product ID
	 *
	 * @return mixed  Product Main Image,Product Name,Product Formatted Price
	 */
	public function getProduct($productId, $tag)
	{
		$template      = Redtemplate::getInstance();
		$productHelper = productHelper::getInstance();
		$helper        = redhelper::getInstance();

		$templateId 	= trim($this->params->get('product_template', 1));
		$templateDetail = $template->getTemplate('product_content_template', $templateId);
		$product    	= RedshopHelperProduct::getProductById($productId);

		// Get Product Formatted price as per redshop configuration
		$productPrices = $productHelper->getProductNetPrice($productId);
		$price         = $productPrices['productPrice'] + $productPrices['productVat'];
		$price         = $productHelper->getProductFormattedPrice($price);

		$link = JRoute::_(
					JUri::root()
					. 'index.php?option=com_redshop&view=product&pid=' . $productId
					. '&Itemid=' . $helper->getItemid($productId)
				);

		// Get product Image
		$productImage = $productHelper->getProductImage(
							$productId,
							$link,
							Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
							Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT'),
							0
						);

		$productImageLink = '<a href="' . $link . '">' . $productImage . '</a>';

		$text = "<div>" . $productImageLink . "</div>"
				. "<div>" . $product->product_name . "</div>"
				. "<div>" . $price . "</div>";

		if ($templateDetail[0]->template_desc && strpos($tag, 'product:') !== false)
		{
			$text = $templateDetail[0]->template_desc;

			$text = str_replace("{product_thumb_image}", $productImageLink, $text);
			$text = str_replace("{product_name}", '<a href="' . $link . '">' . $product->product_name . '</a>', $text);
			$text = str_replace("{product_price}", $price, $text);

			$text = str_replace("{read_more}", "", $text);
			$text = str_replace("{product_desc}", "", $text);

			// Replace attribute template to null
			$attributeTagArr = explode("attribute_template:", $text);
			$attributeTagArr = explode("}", $attributeTagArr[1]);
			$attributeTag = "{attribute_template:" . $attributeTagArr[0] . "}";
			$text = str_replace($attributeTag, "", $text);

			// Replace add to cart template to null
			if (strstr($text, 'form_addtocart:'))
			{
				$cartTagArr = explode("form_addtocart:", $text);
				$cartTagArr = explode("}", $cartTagArr[1]);
				$cartTag     = "{form_addtocart:" . $cartTagArr[0] . "}";
				$text         = str_replace($cartTag, "", $text);
			}
		}
		elseif (strpos($tag, 'name:') !== false)
		{
			return $product->product_name;
		}
		elseif (strpos($tag, 'price:') !== false)
		{
			return $price;
		}
		elseif (strpos($tag, 'image:') !== false)
		{
			return $productImage;
		}

		return $text;
	}
}
