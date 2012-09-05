<?php
 // No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * $DESC
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules/$moduleName
 * @subpackage	libs.
 * @copyright	Copyright (C) April 2010, LandOfExtensions.Com <@emai:landofcoder@gmail.com, tyrantit@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
if( !class_exists('LofSliderGroupBase') ){ 
	class LofSliderGroupBase{
		
		/**
		 * @var string $_name is name of group;
		 *
		 * @access private;
		 */
		var $__name = 'base';
		
		/**
		 * @var string $__currentPath;
		 *
		 * @access private;
		 */
		var $__currentPath = '';
		
		/**
		 * getter of current path variable
		 */
		function setCurrentPath( $path ){
			$this->__currentPath = $path;
		}
		
		/**
		 * getter of current path variable
		 */
		function getCurrentPath(){
			return $this->__currentPath;
		}
		
		/**
		 * getter of name variable
		 */
		function getName(){
			return $this->__name;	
		}
		
		/**
		 * render paramters form
		 *
		 * @return string
		 */
		function renderForm( $params=array(), $fileName='form' ){
			
			// look up configuration file which build-in this plugin or the tempate used.
			$path = $this->getCurrentPath().$fileName.'.xml';
	
			if( file_exists($path) ){
				$params = new JParameter(  $params, $path );
				$content = $params->render('params') ;								
				return $content;
			}
			
			return ;
		}
		
		/**
		 *  check the folder is existed, if not make a directory and set permission is 755
		 *
		 * @param array $path
		 * @access public,
		 * @return boolean.
		 */
		 function makeDir( $path ){
			$folders = explode ( '/',  ( $path ) );
			$tmppath =  JPATH_SITE.DS.'images'.DS.'lofthumbs'.DS;
			if( !file_exists($tmppath) ) {
				JFolder::create( $tmppath, 0755 );
			}; 
			for( $i = 0; $i < count ( $folders ) - 1; $i ++) {
				if (! file_exists ( $tmppath . $folders [$i] ) && ! JFolder::create( $tmppath . $folders [$i], 0755) ) {
					return false;
				}	
				$tmppath = $tmppath . $folders [$i] . DS;
			}		
			return true;
		}
		
		/**
		 *  check the folder is existed, if not make a directory and set permission is 755
		 *
		 * @param array $path
		 * @access public,
		 * @return boolean.
		 */
		 function renderThumb( $path, $width=100, $height=100, $title='', $isThumb=true ){
			if( !preg_match("/.jpg|.png|.gif/",strtolower($path)) ) return '&nbsp;';
			if( $isThumb ){
				$path = str_replace( JURI::base(), '', $path );
				$imagSource = JPATH_SITE.DS. str_replace( '/', DS,  $path );
				if( file_exists($imagSource)  ) {
					$path =  $width."x".$height.'/'.$path;
					$thumbPath = JPATH_SITE.DS.'images'.DS.'lofthumbs'.DS. str_replace( '/', DS,  $path );
					if( !file_exists($thumbPath) ) {
						$thumb = PhpThumbFactory::create( $imagSource  );  
						if( !self::makeDir( $path ) ) {
								return '';
						}		
						$thumb->adaptiveResize( $width, $height);
						 
						$thumb->save( $thumbPath  ); 
					}
					$path = JURI::base().'images/lofthumbs/'.$path;
				} 
			}
	
			return '<img src="'.$path.'" title="'.$title.'" alt="'.$title.'" />';
		}
		
		/**
		 * get parameters from configuration string.
		 *
		 * @param string $string;
		 * @return array.
		 */
		 function parseParams( $string ) {
			$string = html_entity_decode($string, ENT_QUOTES);
			$regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
			 $params = null;
			 if(preg_match_all($regex, $string, $matches) ){
					for ($i=0;$i<count($matches[1]);$i++){ 
					  $key 	 = $matches[1][$i];
					  $value = $matches[3][$i]?$matches[3][$i]:($matches[4][$i]?$matches[4][$i]:$matches[5][$i]);
					  $params[$key] = $value;
					}
			  }
			  return $params;
		}
		
		/**
		 * parser a image in the content of article.
		 *
		 * @param.
		 * @return
		 */
		 function parseImages( &$row ){
			$text =  $row->introtext.$row->fulltext;
			$data = self::parserCustomTag( $text );
			if( isset($data[1][0]) ){
				$tmp = self::parseParams( $data[1][0] );
				$row->mainImage = isset($tmp['src']) ? $tmp['src']:'';
				$row->thumbnail = $row->mainImage ;// isset($tmp['thumb']) ?$tmp['thumb']:'';	
			} else {
				$regex = "/\<img.+src\s*=\s*\"([^\"]*)\"[^\>]*\>/";
				preg_match ($regex, $text, $matches); 
				$images = (count($matches)) ? $matches : array();
				if (count($images)){
					$row->mainImage = $images[1];
					$row->thumbnail = $images[1];
				} else {
					$row->thumbnail = '';
					$row->mainImage = '';	
				}
			}
		}
		
		/**
		 * get a subtring with the max length setting.
		 * 
		 * @param string $text;
		 * @param int $length limit characters showing;
		 * @param string $replacer;
		 * @return tring;
		 */
		function substring( $text, $length = 100, $isStripedTags=true,  $replacer='...' ){
			if(  !$isStripedTags ) return $text;
			$string = $isStripedTags? strip_tags( $text ):$text;
	
			if( function_exists('mb_substr') ){
				if(  $length > mb_strlen($string) ) return $string; 
				return (preg_match('/^(.*)\W.*$/', 
							mb_substr($string, 0, $length+1), $matches) 
								? $matches[1] : mb_substr($string, 0, $length)) . $replacer; 
			} 
			if(  $length > strlen($string) ) return $string; 
			return (preg_match('/^(.*)\W.*$/', 
						substr($string, 0, $length+1), $matches) 
							? $matches[1] : substr($string, 0, $length)) . $replacer; 
		}
		
			
		/**
		 * parser a custom tag in the content of article to get the image setting.
		 * 
		 * @param string $text
		 * @return array if maching.
		 */
		 function parserCustomTag( $text ){ 
			if( preg_match("#{lofimg(.*)}#s", $text, $matches, PREG_OFFSET_CAPTURE) ){ 
				return $matches;
			}	
			return null;
		}
		
		/**
		 * Abstract function get a item by id
		 */
		function getItemById( $itemId ){ return array();}
		
		/**
		 * Abstract function : get list item by name of group
		 */
		function getListByGroup( $name, $published=true ){ return array(); }
		
		/**
		 * Abstract function get list item by category id
		 */
		function getListByCategoryId( $groupId, $published=true ){ return array(); }
		
		/**
		 *  Abstract function get list item by parameter
		 */
		function getListByParameters( $params ){ return array(); }
	
	}
}
?>
