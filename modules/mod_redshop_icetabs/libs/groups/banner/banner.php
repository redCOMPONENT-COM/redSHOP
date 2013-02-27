<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
/**
 * $DESC
 * 
 * @version		$Id: helper.php $Revision
 * @copyright	Copyright (C) Dec 2009 LandOfExtensions.Com <@emai:landofcoder@gmail.com, tyrantit@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
if(!class_exists('LofSliderGroupBanner'))
{
	class LofSliderGroupBanner extends LofSliderGroupBase
	{
		var $__name = 'banner';
		/**
		 * override method: get list image from articles.
		 */
		function getListByParameters($params)
		{
			$thumbWidth    = (int)$params->get('thumbnail_width', 35);
			$thumbHeight   = (int)$params->get('thumbnail_height', 60);
			$imageHeight   = (int)$params->get('imagemain_height', 300) ;
			$imageWidth    = (int)$params->get('imagemain_width', 650) ;
			$isThumb       = $params->get('auto_renderthumb',1);
			$image_quanlity = $params->get('image_quanlity', 100);
			$ordering      = $params->get('image_ordering', 'ordering_desc');
			$limit 	       = $params->get('limit_items', 5);
			$ordering      =  preg_split('/_/', $ordering);
			$catids        = $params->get('banner_category', '');
			$clientids     = $params->get('clientids', '');
			$imageurl =  "images/banners/";
					
			// fetch data
			$query = " SELECT  * FROM #__banner b WHERE showBanner=1 ";	 
			// filter by client banner
			if(!empty($clientids)){
				$clientids = !is_array($clientids) ? $clientids : '"'.implode('","',$clientids).'"';
				$query .= ' AND  b.cid IN('.$clientids.')';
			}
			// filter by category
			if(!empty($catids)){
				$catids = !is_array($catids) ? $catids : '"'.implode('","',$catids).'"';
				$query .= ' AND  b.catid IN('.$catids.')';
			}
			// order by	
			$query .= " ORDER BY " . ($ordering[0]=='random' ? " RAND() " : $ordering[0] . " " . $ordering[1]);
			$query .= " LIMIT " . (int) $limit;
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			
			$banners = $db->loadObjectList();
			if(empty($banners)) { return array() ; }
			
			foreach($banners  as $key =>  $item)
			{
				$item->mainImage = $imageurl.$item->imageurl;
				$item->thumbnail = $imageurl.$item->imageurl;
				$item->title 	 = $item->name;
				$link = JRoute::_('index.php?option=com_banners&task=click&bid='. $item->bid);
				$item->link      =  $link;
				$item->introtext =$item->description;
				if($item->mainImage &&  $image=self::renderThumb($item->mainImage, $imageWidth, $imageHeight, $item->title, $isThumb,$image_quanlity)){
					$item->mainImage = $image;
				}
				if($item->thumbnail &&  $image = self::renderThumb($item->thumbnail, $thumbWidth, $thumbHeight, $item->title, $isThumb,$image_quanlity)){
					$item->thumbnail = $image;
				}
				$banners[$key] = $item;
			}
			return $banners;
		}
	}
}
