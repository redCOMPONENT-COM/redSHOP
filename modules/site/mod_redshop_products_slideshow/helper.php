<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products_slideshow
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopProductSlideshow
 *
 * @since  1.5
 */
class RedshopProductSlideshow
{
	/**
	 * Create smart xml files
	 *
	 * @param   object  $params    Module params
	 * @param   int     $moduleId  Module id
	 *
	 * @throws Exception
	 *
	 * @return  void
	 */
	public static function create_smart_xml_files($params, $moduleId = 0)
	{
		$cat_id    = $params->get('category_id', '0');
		$app = JFactory::getApplication();
		$jInput = $app->input;

		if (!is_array($cat_id))
		{
			$id = explode(",", trim($cat_id));
		}
		else
		{
			$id = $cat_id;
		}

		$load_curr = trim($params->get('load_curr', '1'));

		if ($load_curr == 1 && $jInput->getWord('option', '') == 'com_redshop')
		{
			$urlCategoryId = $jInput->getInt('cid', $app->getParams('com_redshop')->get('cid', ''));

			if ($urlCategoryId)
			{
				unset($id);
				$id = array(0 => $urlCategoryId);
			}
		}

		$module_path       = dirname(__FILE__) . '/';
		$xml_data_filename = $module_path . 'assets/data_' . $moduleId . '.xml';
		$xml_data_data     = '<?xml version="1.0" encoding="utf-8"?>
<data>
<channel>';
		$xml_data_data_btns = '';
		$get_catxml         = self::write_prodgallery_xml_data($id, $params);

		if ($get_catxml['flag'])
		{
			$xml_data_data_btns .= $get_catxml['xml_data'];
		}

		$roundCorner       = trim($params->get('roundCorner', ''));
		$autoPlayTime      = trim($params->get('autoPlayTime', ''));
		$isHeightQuality   = trim($params->get('isHeightQuality', 'no'));
		$isHeightQuality   = ($isHeightQuality == "yes") ? 'true' : 'false';
		$blendMode         = trim($params->get('blendMode', ''));
		$transDuration     = trim($params->get('transDuration', ''));
		$windowOpen        = trim($params->get('windowOpen', ''));
		$btnSetMargin      = trim($params->get('btnSetMargin', ''));
		$btnDistance       = trim($params->get('btnDistance', ''));
		$titleBgColor      = trim($params->get('titleBgColor', ''));
		$titleTextColor    = trim($params->get('titleTextColor', ''));
		$titleBgAlpha      = trim($params->get('titleBgAlpha', ''));
		$titleMoveDuration = trim($params->get('titleMoveDuration', ''));
		$btnAlpha          = trim($params->get('btnAlpha', ''));
		$btnTextColor      = trim($params->get('btnTextColor', ''));
		$btnDefaultColor   = trim($params->get('btnDefaultColor', ''));
		$btnHoverColor     = trim($params->get('btnHoverColor', ''));
		$btnFocusColor     = trim($params->get('btnFocusColor', ''));
		$changImageMode    = trim($params->get('changImageMode', ''));
		$isShowBtn         = trim($params->get('isShowBtn', ''));
		$isShowBtn         = ($isShowBtn == "yes") ? 'true' : 'false';
		$isShowTitle       = trim($params->get('isShowTitle', ''));
		$isShowTitle       = ($isShowTitle == "yes") ? 'true' : 'false';
		$scaleMode         = trim($params->get('scaleMode', ''));
		$transform         = trim($params->get('transform', ''));
		$isShowAbout       = trim($params->get('isShowAbout', ''));
		$isShowAbout       = ($isShowAbout == "yes") ? 'true' : 'false';
		$titleFont         = trim($params->get('titleFont', ''));
		$xml_data_data     .= $xml_data_data_btns
			. '
</channel>
<config>
	<roundCorner>' . $roundCorner . '</roundCorner>
	<autoPlayTime>' . $autoPlayTime . '</autoPlayTime>
	<isHeightQuality>' . $isHeightQuality . '</isHeightQuality>
	<blendMode>' . $blendMode . '</blendMode>
	<transDuration>' . $transDuration . '</transDuration>
	<windowOpen>' . $windowOpen . '</windowOpen>
	<btnSetMargin>' . $btnSetMargin . '</btnSetMargin>
	<btnDistance>' . $btnDistance . '</btnDistance>
	<titleBgColor>' . $titleBgColor . '</titleBgColor>
	<titleTextColor>' . $titleTextColor . '</titleTextColor>
	<titleBgAlpha>' . $titleBgAlpha . '</titleBgAlpha>
	<titleMoveDuration>' . $titleMoveDuration . '</titleMoveDuration>
	<btnAlpha>' . $btnAlpha . '</btnAlpha>
	<btnTextColor>' . $btnTextColor . '</btnTextColor>
	<btnDefaultColor>' . $btnDefaultColor . '</btnDefaultColor>
	<btnHoverColor>' . $btnHoverColor . '</btnHoverColor>
	<btnFocusColor>' . $btnFocusColor . '</btnFocusColor>
	<changImageMode>' . $changImageMode . '</changImageMode>
	<isShowBtn>' . $isShowBtn . '</isShowBtn>
	<isShowTitle>' . $isShowTitle . '</isShowTitle>
	<scaleMode>' . $scaleMode . '</scaleMode>
	<transform>' . $transform . '</transform>
	<isShowAbout>' . $isShowAbout . '</isShowAbout>
	<titleFont>' . $titleFont . '</titleFont>
</config>
</data>';

		JFile::write($xml_data_filename, $xml_data_data);
	}

	/**
	 * Write prodgallery xml data
	 *
	 * @param   array   $cat_arr  Categories array
	 * @param   object  $params   Module params
	 *
	 * @return array
	 */
	public static function write_prodgallery_xml_data($cat_arr, $params)
	{
		$db = JFactory::getDbo();

		$ret_array = array(
			'flag' => false,
			'xml_data' => ''
		);

		$imageWidth  = intval($params->get('imageWidth'));
		$imageHeight = intval($params->get('imageHeight'));
		$numbproduct = intval($params->get('numbproduct'));
		$loadtype    = trim($params->get('loadtype', 'random'));

		$query = $db->getQuery(true)
			->select('DISTINCT(p.product_id)')
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'x') . ' ON x.product_id = p.product_id')
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON c.id = x.category_id')
			->where('p.published = 1')
			->where('c.published = 1');

		if (count($cat_arr) > 0)
		{
			$query->where('x.category_id IN (' . implode(',', $cat_arr) . ')');
		}

		switch ($loadtype)
		{
			case 'random':
				$query->order('rand()');
				break;
			case 'mostsold':
				$subQuery = $db->getQuery(true)
					->select('SUM(' . $db->qn('oi.product_quantity') . ') AS qty, oi.product_id')
					->from($db->qn('#__redshop_order_item', 'oi'))
					->group('oi.product_id');
				$query->select('orderItems.qty')
					->leftJoin('(' . $subQuery . ') orderItems ON orderItems.product_id = p.product_id')
					->order($db->qn('orderItems.qty') . ' DESC');
				break;
			case 'special':
				$query->where('p.product_special = 1')
					->order('rand()');
				break;
			case 'newest':
			default:
				$query->order('p.publish_date DESC');
				break;
		}

		$xml_data = '';
		$rows = array();

		if ($productIds = $db->setQuery($query, 0, $numbproduct)->loadColumn())
		{
			$query->clear()
				->where('p.product_id IN (' . implode(',', $productIds) . ')')
				->order('FIELD(p.product_id, ' . implode(',', $productIds) . ')');

			$user = JFactory::getUser();
			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id');

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		$producthelper = productHelper::getInstance();
		$redhelper     = redhelper::getInstance();

		for ($k = 0, $countRows = count($rows);$k < $countRows;$k++)
		{
			$ret_array['flag'] = true;
			$price_txt         = '';
			$ItemData          = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $rows[$k]->product_id);

			if (count($ItemData) > 0)
			{
				$Itemid = $ItemData->id;
			}
			else
			{
				$Itemid = RedshopHelperUtility::getItemId($rows[$k]->product_id);
			}

			if ($params->get('show_price') == "yes")
			{
				// Without vat price
				$productArr        = $producthelper->getProductNetPrice($rows[$k]->product_id, 0, 1);
				$product_price     = $productArr['productPrice'];
				$productVat        = $productArr['productVat'];

				// With vat price
				$product_price_vat = $product_price + $productVat;
				$price_txt         .= $params->get('price_text', ': ');
				$price_txt         .= ' ';

				$pricetax          = $params->get('pricetax', 'yes');

				if ($pricetax == 'yes')
				{
					$abs_price = $product_price_vat;
				}
				else
				{
					$abs_price = $product_price;
				}

				$abs_price = $producthelper->getProductFormattedPrice($abs_price);
				$price_txt .= $abs_price;
			}

			$curr_link = JRoute::_('index.php?option=com_redshop&amp;view=product&amp;pid=' . $rows[$k]->product_id . '&amp;Itemid=' . $Itemid, true);
			$pname = $rows[$k]->product_name;

			if (!JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $rows[$k]->product_full_image))
			{
				$imgpath = REDSHOP_FRONT_IMAGES_ABSPATH . 'noimage.jpg';
			}
			else
			{
				$imgpath = RedShopHelperImages::getImagePath(
					$rows[$k]->product_full_image,
					'',
					'thumb',
					'product',
					$imageWidth,
					$imageHeight,
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
			}

			$xml_data .= '<item>
		<link>' . $curr_link . '</link>
		<image>' . $imgpath . '</image>
		<title>' . htmlentities($pname) . $price_txt . '</title>
		</item>';
		}

		$ret_array['xml_data'] = $xml_data;

		return $ret_array;
	}
}
