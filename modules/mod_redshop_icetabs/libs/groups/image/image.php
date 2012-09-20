<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website 	htt://landofcoder.com
 * @license		GNU General Public License version 2
 */
if(!class_exists('LofSliderGroupImage'))
{  
	class LofSliderGroupImage extends LofSliderGroupBase
	{
		/**
		 * @var string $__name;
		 *
		 * @access private
		 */
		var $__name = 'image';
		/**
		 * override method: get list image from articles.
		 */
		function getListByParameters($params)
		{
			$subpath = trim($params->get('image_folder', ''));
			if(empty($subpath)) { return array(); }
			$tmpPath = str_replace(DS, '/', $subpath).'/';
			$path = JPATH_SITE.DS.$subpath;
			
			$files = JFolder::files($path,'.jpg|.png|.gif'); 
			if(is_array($files))
			{
				$thumbWidth    = (int)$params->get('thumbnail_width', 35);
				$thumbHeight   = (int)$params->get('thumbnail_height', 60);
				$imageHeight   = (int)$params->get('imagemain_height', 300) ;
				$imageWidth    = (int)$params->get('imagemain_width', 660) ;
				$isThumb       = $params->get('auto_renderthumb',1);
				$image_quanlity = $params->get('image_quanlity', 100);
				$ordering 	   = $params->get('image_ordering', '');
				$limit 		   = (int)$params->get('limit_items', 5);
				$categoryId    = (int)$params->get('image_category', '');
				$content = $this->getContent($categoryId);
				$extraURL 		= $params->get('open_target')!='modalbox'?'':'&tmpl=component'; 
				$tmp = $files;
				if(trim($ordering) == 'random')
				{ 
					$rand = (array_rand($files, count($files)));
					$files = (array_combine( $rand , $files));
				}
				$data = array();
				foreach($tmp as $key => $file)
				{
					$item = new stdClass();
					$item->link = '';
					if(isset($content[preg_replace("/\.(\w{3})$/",'',strtolower(trim($file)))]))
					{
						$tmp = $content[preg_replace("/\.(\w{3})$/",'',strtolower(trim($file)))];
						$item->title = $tmp->title;
						$item->description = $tmp->introtext;
						$item->introtext =  $tmp->introtext;;
					} else {	
						$item->title = $file;
						$item->description = '';
						$item->introtext = '';
					}
					$item->mainImage = $tmpPath.$files[$key];
					$item->thumbnail  = $tmpPath.$files[$key];
					if($item->mainImage &&  $image=self::renderThumb($item->mainImage, $imageWidth, $imageHeight, $item->title, $isThumb,$image_quanlity)){
						$item->mainImage = $image;
					}
					if($item->thumbnail &&  $image = self::renderThumb($item->thumbnail, $thumbWidth, $thumbHeight, $item->title, $isThumb,$image_quanlity)){
						$item->thumbnail = $image;
					}
			
					$data[] = $item;
					if($key>= $limit-1){ break; }
					
				}
				return $data;
			}
			return array();
		}
		/**
		 * get the list of articles, using for joomla 1.6.x
		 * 
		 * @param JParameter $params;
		 * @return array;
		 */
		public function getContent($categoryId)
		{
			$mainframe = JFactory::getApplication(); 
			if((int) $categoryId <=0)return;
			$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
	
			// Set application parameters in model
			$appParams = JFactory::getApplication()->getParams();
			$model->setState('params', $appParams);
			
			$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.title_alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
								' a.modified, a.modified_by,a.publish_up, a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
								' a.hits, a.featured,' .
								' LENGTH(a.fulltext) AS readmore');

			$model->setState('filter.published', 1);
			// Access filter
			$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
			$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
			$model->setState('filter.access', $access);
			// Category filter
			$catids = $categoryId;
			$flag = true;
			if(is_array($catids) && count($catids) == 1)
			{
				if(empty($catids[0]))
				{
					$flag = false;
				}
			}
			if($flag)
			{
				$catids = is_array($catids) ? $catids : explode(",",$catids);
				$model->setState('filter.category_id', $catids);
			}
			$model->setState('list.ordering', "a.ordering");
			$model->setState('list.direction', "ASC");
			//echo "<pre>";
			//print_r($model);die();
			$items = $model->getItems();
			$output = array();
			foreach ($items as $key => &$item) {
				$item->slug = $item->id.':'.$item->alias;
				$item->catslug = $item->catid.':'.$item->category_alias;
	
				if ($access || in_array($item->access, $authorised))
				{
					// We know that user has the privilege to view the article
					$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
				}
				else {
					$item->link = JRoute::_('index.php?option=com_user&view=login');
				}
				$item->date = JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2')); 

				$item->introtext = JHtml::_('content.prepare', $item->introtext);
				$tmp  = explode("|", $item->title);
				$item->title  = count($tmp) >= 2 ? $tmp[1]:$tmp[0];
				$output[strtolower(trim($tmp[0]))] = $items[$key];
			}
			return $output;
		}
	}
}