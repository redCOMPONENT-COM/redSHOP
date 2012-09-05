<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
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
if( !class_exists('LofSliderGroupImage') ){  
	class LofSliderGroupImage extends LofSliderGroupBase{
		/**
		 * @var string $__name;
		 *
		 * @access private
		 */
		var $__name = 'image';
		
		/**
		 * override method: get list image from articles.
		 */
		function getListByParameters( $params ){
			$subpath = trim( $params->get( 'image_folder', '' ) );
			if( empty($subpath) ) { return array(); }
			$tmpPath = str_replace( DS, '/', $subpath ).'/';
			$path = JPATH_SITE.DS.$subpath;
			
			$files = JFolder::files( $path,'.jpg|.png|.gif' ); 
			if( is_array($files) ){
				$thumbWidth    = (int)$params->get( 'thumbnail_width', 35 );
				$thumbHeight   = (int)$params->get( 'thumbnail_height', 60 );
				$imageHeight   = (int)$params->get( 'main_height', 300 ) ;
				$imageWidth    = (int)$params->get( 'main_width', 660 ) ;
				$isThumb       = $params->get( 'auto_renderthumb',1);
				$ordering 	   = $params->get( 'image_ordering', '' );
				$limit 		   = (int)$params->get( 'limit_items', 5 );
				$categoryId    = (int)$params->get( 'image_category', '' );
				$content = $this->getContent( $categoryId );
				$extraURL 		= $params->get('open_target')!='modalbox'?'':'&tmpl=component'; 
				$tmp = $files;
				if( trim($ordering) == 'random' ){ 
					$rand = (array_rand($files, count($files)));
					$files = ( array_combine(  $rand , $files  ) );
				}
				$data = array();
				foreach( $tmp as $key => $file){
					$item = new stdClass();
					$item->link = '';
					if( isset($content[preg_replace("/\.(\w{3})$/",'',strtolower(trim($file)))]) ){
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
					if( $item->mainImage &&  $image=self::renderThumb($item->mainImage, $imageWidth, $imageHeight, $item->title, $isThumb ) ){
						$item->mainImage = $image;
					}
					if( $item->thumbnail &&  $image = self::renderThumb($item->thumbnail, $thumbWidth, $thumbHeight, $item->title, $isThumb ) ){
						$item->thumbnail = $image;
					}
			
					$data[] = $item;
					if( $key>= $limit-1 ){ break; }
					
				}
				return $data;
			}
			return array();
		}
		
		
		function getContent( $categoryId ){
			global $mainframe; 
			if( (int) $categoryId <=0 )return;
			$my 	       = &JFactory::getUser();
			$aid	       = $my->get( 'aid', 0 );
			$db	    = &JFactory::getDBO();
			$date   =& JFactory::getDate();
			$now    = $date->toMySQL();
			$cparam = &JComponentHelper::getParams( 'com_content' );
			// make sql query
			$query 	= 'SELECT a.*,cc.description as catdesc, cc.title as category_title, cc.title as cattitle,s.description as secdesc, s.title as sectitle,' 
					. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
					. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":",cc.id,cc.alias) ELSE cc.id END as catslug,'
					. ' CASE WHEN CHAR_LENGTH(s.alias) THEN CONCAT_WS(":", s.id, s.alias) ELSE s.id END as secslug'
					. "\n FROM #__content AS a"
					. ' INNER JOIN #__categories AS cc ON cc.id = a.catid' 
					. ' INNER JOIN #__sections AS s ON s.id = a.sectionid'
					. "\n WHERE a.state = 1"
					. "\n AND ( a.publish_up = " . $db->Quote( $db->getNullDate() ) . " OR a.publish_up <= " . $db->Quote( $now  ) . " )"
					. "\n AND ( a.publish_down = " . $db->Quote( $db->getNullDate() ) . " OR a.publish_down >= " . $db->Quote( $now  ) . " )"
					. ( ( !$mainframe->getCfg( 'shownoauth' ) ) ? "\n AND a.access <= " . (int) $aid : '' )
			;
			
			$query .=  ' AND cc.id='.(int) $categoryId ;//. ' ORDER BY ' . $ordering;
	
			$db->setQuery($query);
			$data = $db->loadObjectList();
			$output = array();
			JPluginHelper::importPlugin('content');
			$dispatcher   =& JDispatcher::getInstance();	
			
			foreach( $data as $key => $item ){	
				if($item->access <= $aid ) {
					$data[$key]->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug, $item->sectionid));
				} else {
					$data[$key]->link = JRoute::_('index.php?option=com_user&view=login');
				}
				$item->introtext = JHTML::_('content.prepare', $item->introtext , $cparam);	
				$item->date = JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2')); 
				$results = @$dispatcher->trigger('onPrepareContent', array (&$data[$key], & $params, 0));
				$tmp  = explode("|", $item->title );
				$item->title  = count($tmp) >= 2 ? $tmp[1]:$tmp[0];
				$output[strtolower(trim($tmp[0]))] = $data[$key];
			}
			return $output;
		}
		
	}
}
?>