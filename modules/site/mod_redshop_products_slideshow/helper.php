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
	public static function createSmartXmlFiles($params, $moduleId = 0)
	{
		$catId  = $params->get('category_id', '0');
		$app 	= JFactory::getApplication();
		$jInput = $app->input;

		if (!is_array($catId))
		{
			$id = explode(",", trim($catId));
		}
		else
		{
			$id = $catId;
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

		$modulePath      = dirname(__FILE__) . '/';
		$xmlDataFilename = $modulePath . 'assets/data_' . $moduleId . '.xml';
		$xmlDataData     = '<?xml version="1.0" encoding="utf-8"?>
			<data>
			<channel>';
					$xmlDataDataBtns = '';
					$getCatXml       = self::writeProdudctGalleryXmlData($id, $params);

					//if ($getCatXml['flag'])
					//{
						$xmlDataDataBtns .= $getCatXml['xml_data'];
					//}

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
					$xmlDataData      .= $xmlDataDataBtns
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

		JFile::write($xmlDataFilename, $xmlDataData);
	}

	/**
	 * Write prodgallery xml data
	 *
	 * @param   array   $cats    Categories array
	 * @param   object  $params  Module params
	 *
	 * @return array
	 */
	public static function writeProdudctGalleryXmlData($cats, $params)
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
			->select('DISTINCT(' . $db->qn('p.product_id') . ')')
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'x') . ' ON ' . $db->qn('x.product_id') . ' = ' . $db->qn('p.product_id'))
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('x.category_id'))
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('c.published') . ' = 1');

		if (count($cats) > 0)
		{
			$query->where($db->qn('x.category_id') . ' IN (' . implode(',', $db->q($cats)) . ')');
		}

		switch ($loadtype)
		{
			case 'random':
				$query->order('rand()');
				break;
			case 'mostsold':
				$subQuery = $db->getQuery(true)
					->select('SUM(' . $db->qn('oi.product_quantity') . ') AS ' . $db->qn('qty') . ', ' . $db->qn('oi.product_id'))
					->from($db->qn('#__redshop_order_item', 'oi'))
					->group($db->qn('oi.product_id'));
				$query->select($db->qn('orderItems.qty'))
					->leftJoin('(' . $subQuery . ') ' . $db->qn('orderItems') . ' ON ' . $db->qn('orderItems.product_id') . ' = ' . $db->qn('p.product_id'))
					->order($db->qn('orderItems.qty') . ' DESC');
				break;
			case 'special':
				$query->where($db->qn('p.product_special') . ' = 1')
					->order('rand()');
				break;
			case 'newest':
			default:
				$query->order($db->qn('p.publish_date') . ' DESC');
				break;
		}

		$xmlData = '';
		$rows = array();

		if ($productIds = $db->setQuery($query, 0, $numbproduct)->loadColumn())
		{
			$query->clear()
				->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')')
				->order('FIELD(' . $db->qn('p.product_id') . ', ' . implode(',', $productIds) . ')');

			$user = JFactory::getUser();
			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select('CONCAT_WS(' . $db->q('.') . ', ' . $db->qn('p.product_id') . ', ' . (int) $user->id . ') AS ' . $db->qn('concat_id'));

			if ($rows = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($rows);
				$rows = array_values($rows);
			}
		}

		$productHelper = productHelper::getInstance();
		$redHelper     = redhelper::getInstance();

		for ($k = 0, $countRows = count($rows);$k < $countRows;$k++)
		{
			$retArray['flag'] = true;
			$priceTxt         = '';
			$itemData         = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $rows[$k]->product_id);

			if (count($itemData) > 0)
			{
				$itemId = $itemData->id;
			}
			else
			{
				$itemId = $redHelper->getItemid($rows[$k]->product_id);
			}

			if ($params->get('show_price') == "yes")
			{
				// Without vat price
				$productArr        = $productHelper->getProductNetPrice($rows[$k]->product_id, 0, 1);
				$productPrice      = $productArr['productPrice'];
				$productVat        = $productArr['productVat'];

				// With vat price
				$productPriceVat   = $productPrice + $productVat;
				$priceTxt         .= $params->get('price_text', ': ');
				$priceTxt         .= ' ';

				$pricetax          = $params->get('pricetax', 'yes');

				if ($pricetax == 'yes')
				{
					$absPrice = $productPriceVat;
				}
				else
				{
					$absPrice = $productPrice;
				}

				$absPrice  = $productHelper->getProductFormattedPrice($absPrice);
				$priceTxt .= $absPrice;
			}

			$curr_link = JRoute::_('index.php?option=com_redshop&amp;view=product&amp;pid=' . $rows[$k]->product_id . '&amp;Itemid=' . $itemId, true);
			$pname = $rows[$k]->product_name;

			if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $rows[$k]->product_full_image))
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

			$xmlData .= '<item>
		<link>' . $curr_link . '</link>
		<image>' . $imgpath . '</image>
		<title>' . htmlentities($pname) . $priceTxt . '</title>
		</item>';
		}

		$retArray['xml_data'] = $xmlData;

		return $retArray;
	}
}
