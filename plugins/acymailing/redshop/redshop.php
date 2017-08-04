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

class plgAcymailingRedshop extends JPlugin
{
	public function plgAcymailingRedshop(&$subject, $config)
	{
		$lang  = JFactory::getLanguage();
		$lang->load('plg_acymailing_redshop', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	public function acymailing_getPluginType()
	{
		$onePlugin           = new stdClass;
		$onePlugin->name     = JText::_('PLG_ACYMAILING_REDSHOP_REDSHOP');
		$onePlugin->function = 'acymailingredSHOP_show';
		$onePlugin->help     = 'plugin-redSHOP';

		return $onePlugin;
	}

	public function acymailingredSHOP_show()
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
			->select('p.product_id, p.product_name, p.product_number')
			->select($db->qn('c.id', 'category_id'))
			->select($db->qn('c.name', 'category_name'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON p.product_id = pc.product_id')
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON pc.category_id = c.id')
			->group('p.product_id')
			->order($pageInfo->filter->order->value . ' ' . $pageInfo->filter->order->dir);

		if (!empty($pageInfo->search))
		{
			$searchFields = array('p.product_name', 'p.product_id', 'p.product_number');
			$searchVal = '\'%' . acymailing_getEscaped($pageInfo->search, true) . '%\'';
			$query->where(implode(" LIKE $searchVal OR ", $searchFields) . " LIKE $searchVal");
		}

		$rs = $db->setQuery($query, $pageInfo->limit->start, $pageInfo->limit->value)->loadObjectlist();

		$query->clear('select')
			->clear('limit')
			->clear('group')
			->select('COUNT(p.product_id)');
		$pageInfo->elements->total = $db->setQuery($query)->loadResult();
		$pageInfo->elements->page = count($rs);

		jimport('joomla.html.pagination');
		$pagination = new JPagination($pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value);
		?>
		<?php acymailing_listingsearch($pageInfo->search); ?>
		<table class="adminlist table table-striped table-hover" cellpadding="1">
			<thead>
			<tr>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('PLG_ACYMAILING_REDSHOP_PRODUCT_NAME'), 'p.product_name', $pageInfo->filter->order->dir, $pageInfo->filter->order->value); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('PLG_ACYMAILING_REDSHOP_PRODUCT_NUMBER'), 'p.product_number', $pageInfo->filter->order->dir, $pageInfo->filter->order->value); ?>
				</th>
				<th class="title"><?php echo JText::_('PLG_ACYMAILING_REDSHOP_CATEGORY_NAME'); ?></th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', JText::_('PLG_ACYMAILING_REDSHOP_PRODUCT_ID'), 'p.product_id', $pageInfo->filter->order->dir, $pageInfo->filter->order->value); ?>
				</th>
			</tr>
			</thead>
			<tfoot>
			<tr style="cursor:pointer">
				<td colspan="4">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
						<div style="float: left; margin: 0 10px 0 0;">
							<?php echo $pagination->getLimitBox(); ?>
						</div>
					<?php endif; ?>
					<?php echo $pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		<?php
		$k = 0;

		for ($i = 0, $countProducts = count($rs); $i < $countProducts; $i++):
			$row = $rs[$i];
			?>
			<tr style="cursor:pointer" class="row<?php echo $k; ?>" onclick="setTag('{product:<?php
			echo $row->product_id; ?>}');insertTag();">
				<td><?php echo $row->product_name; ?></td>
				<td><?php echo $row->product_number; ?></td>
				<td><?php echo $row->category_name; ?></td>
				<td><?php echo $row->product_id; ?></td>
			</tr>
		<?php
			$k = 1 - $k;
		endfor; ?>
		</table>
		<input type="hidden" name="filter_order" value="<?php echo $pageInfo->filter->order->value; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $pageInfo->filter->order->dir; ?>" />
		<?php
	}

	public function acymailing_replaceusertagspreview(&$email)
	{
		return $this->acymailing_replaceusertags($email);
	}

	public function acymailing_replaceusertags(&$email)
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
	 * @param   int  $product_id  The product ID
	 *
	 * @return mixed  Product Main Image,Product Name,Product Formatted Price
	 */
	public function getProduct($productId, $tag)
	{
		$template      = Redtemplate::getInstance();
		$productHelper = productHelper::getInstance();
		$helper        = redhelper::getInstance();

		$templateId = trim($this->params->get('product_template', 1));
		$templateDetail = $template->getTemplate('product_content_template', $templateId);
		$product    = RedshopHelperProduct::getProductById($productId);

		// Get Product Formatted price as per redshop configuration
		$productPrices = $productHelper->getProductNetPrice($productId);
		$price         = $productPrices['productPrice'] + $productPrices['productVat'];
		$price         = $productHelper->getProductFormattedPrice($price);

		$link = JUri::root()
			. 'index.php?option=com_redshop&view=product&pid=' . $productId
			. '&Itemid=' . RedshopHelperUtility::getItemId($productId);

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
			$attribute_tag_arr = explode("attribute_template:", $text);
			$attribute_tag_arr = explode("}", $attribute_tag_arr[1]);
			$attribute_tag = "{attribute_template:" . $attribute_tag_arr[0] . "}";
			$text = str_replace($attribute_tag, "", $text);

			// Replace add to cart template to null
			if (strstr($text, 'form_addtocart:'))
			{
				$cart_tag_arr = explode("form_addtocart:", $text);
				$cart_tag_arr = explode("}", $cart_tag_arr[1]);
				$cart_tag     = "{form_addtocart:" . $cart_tag_arr[0] . "}";
				$text         = str_replace($cart_tag, "", $text);
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
