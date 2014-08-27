<?php
/**
 * $ModDesc
 *
 * @version        $Id: helper.php $Revision
 * @package        modules
 * @subpackage     $Subpackage
 * @copyright      Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website    htt://landofcoder.com
 * @license        GNU General Public License version 2
 */
if (!class_exists('LofSliderGroupRedshop'))
{
	class LofSliderGroupRedshop extends LofSliderGroupBase
	{
		/**
		 * @var string $__name
		 *
		 * @access private;
		 */
		var $__name = 'redshop';

		/**
		 * override get List of Item by the module's parameters
		 */
		public function getListByParameters($params)
		{
			if (!LofSliderGroupredshop::isredshopExisted())
			{
				return array();
			}

			return $this->__getList($params);
		}

		/**
		 * check redshop is installed or not ?
		 */
		public function isredshopExisted()
		{
			return is_dir(JPATH_ADMINISTRATOR . '/components/com_redshop');
		}

		/**
		 * get list of product
		 *
		 *
		 * @access private
		 */
		public function __getList($params)
		{

			global $mm_action_url;
			JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
			JLoader::load('RedshopHelperProduct');

			$db                  = JFactory::getDbo();
			$producthelper       = new producthelper;
			$ordering            = $params->get('redshop_ordering', 'cdate_asc');
			$limit               = $params->get('limit_items', 4);
			$ordering            = str_replace('_', '  ', $ordering);
			$thumbWidth          = (int) $params->get('thumbnail_width', 35);
			$thumbHeight         = (int) $params->get('thumbnail_height', 60);
			$imageHeight         = (int) $params->get('main_height', 300);
			$imageWidth          = (int) $params->get('main_width', 660);
			$isThumb             = $params->get('auto_renderthumb', 1);
			$titleMaxChars       = $params->get('title_max_chars', '100');
			$isStripedTags       = $params->get('auto_strip_tags', 0);
			$descriptionMaxChars = $params->get('description_max_chars', 100);
			$extraURL            = $params->get('open_target') != 'modalbox' ? '' : '&tmpl=component';

			if (trim($ordering) == 'rand')
			{
				$ordering = " rand() ";
			}
			$condition = self::buildConditionQuery($params);
			// sql query
			$query = ' SELECT p.*, p.product_id,p.publish_date as cdate, p.published, p.product_number, p.product_name,pc.ordering '
				. ' 	, p.product_s_desc, product_thumb_image, product_full_image'
				. ' 	, c.category_id'
				. ' FROM #__redshop_product AS p '
				. ' JOIN #__redshop_product_category_xref as pc ON p.product_id=pc.product_id ';
			$query .= $condition;
			$query .= ' JOIN #__redshop_category as c ON pc.category_id=c.category_id ';
			$query .= ' WHERE p.published = \'1\' AND c.published = \'1\' AND product_parent_id=0 ';

			$query .= ' ORDER BY  ' . $db->escape($ordering);
			$query .= ' LIMIT ' . $limit;

			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (!empty($rows))
			{
				foreach ($rows as $key => $item)
				{
					$tmpimage = $rows[$key]->product_full_image;
					if (!(strtolower(substr($tmpimage, 0, 4)) == 'http'))
					{
						$rows[$key]->product_image_url = JURI::root() . 'components/com_redshop/assets/images/product/' . $rows[$key]->product_full_image;
					}
					else
					{
						$rows[$key]->product_image_url = $rows[$key]->product_full_image;
					}
					$cid = $rows[$key]->category_id;
					//$rows[$key]->description .= $rows[$key]->product_desc ;
					$rows[$key]->description = $this->substring($rows[$key]->product_desc, $descriptionMaxChars, $isStripedTags);

					$item_id = JRequest::getInt('Itemid');

					$extraURL = $item_id > 0 ? $extraURL . '&Itemid=' . $item_id : '';

					$rows[$key]->link  = 'index.php?option=com_redshop&view=product&pid=' . $rows[$key]->product_id . '&Itemid=1';
					$rows[$key]->title = $rows[$key]->product_name;

					/*$rows[$key]->mainImage = $producthelper->getProductImage($rows[$key]->product_id,'',$imageWidth,$imageHeight,1);

					$rows[$key]->thumbnail = $producthelper->getProductImage($rows[$key]->product_id,'',$thumbWidth,$thumbHeight,1);
					*/
					$middlepath = "/components/com_redshop/assets/images/product/";

					if ($rows[$key]->product_full_image && file_exists(JPATH_SITE . $middlepath . $rows[$key]->product_full_image))
					{
						$image                 = self::renderThumb($rows[$key]->product_image_url, $imageWidth, $imageHeight, $rows[$key]->title, $isThumb);
						$rows[$key]->mainImage = $image;
					}

					if ($rows[$key]->product_full_image && file_exists(JPATH_SITE . $middlepath . $rows[$key]->product_full_image))
					{
						$image                 = self::renderThumb($rows[$key]->product_image_url, $thumbWidth, $thumbWidth, $rows[$key]->title, $isThumb);
						$rows[$key]->thumbnail = $image;
					}

					$url                        = "index.php?option=com_redshop&view=cart&pid=" . $rows[$key]->product_id;
					$rows[$key]->addtocart_link = "#";

				}

				return $rows;
			}

			return array();
		}

		/**
		 * build condition query base parameter
		 *
		 * @param JRegistry $params;
		 *
		 * @return string.
		 */
		function buildConditionQuery($params)
		{

			$source = trim($params->get('redshop_source', 'redshop_category'));
			if ($source == 'redshop_category')
			{
				$catids = explode(',', $params->get('redshop_category', '0'));

				if (!empty($catids))
				{
					JArrayHelper::toInteger($catids);

					$condition = ' AND  pc.category_id IN( ' . implode(',', $catids) . ' )';
				}
			}
			else
			{
				$ids = explode(',', $params->get('redshop_items_ids', ''));

				if (!empty($ids))
				{
					JArrayHelper::toInteger($ids);

					$condition = ' AND  pc.product_id IN( ' . implode(',', $ids) . ' )';
				}
			}

			return $condition;
		}
	}
}
