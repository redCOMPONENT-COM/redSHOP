<?php 
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
if(!class_exists('LofSliderGroupVirtuemart'))
{ 
	if(file_exists(dirname(dirname(dirname(dirname(__FILE__)))).'/../../components/com_virtuemart/virtuemart_parser.php')) {
		require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/../../components/com_virtuemart/virtuemart_parser.php');
	} else {
		require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/../components/com_virtuemart/virtuemart_parser.php');
	}

	class LofSliderGroupVirtuemart extends LofSliderGroupBase
	{
		/**
		 * @var string $__name 
		 *
		 * @access private;
		 */
		var $__name = 'virtuemart';
		
		/**
		 * override get List of Item by the module's parameters
		 */
		public function getListByParameters($params){
			if(!LofSliderGroupVirtuemart::isVirtueMartExisted()){
				return array();
			} 
			return $this->__getList($params);
		}
		
		/**
		 * check virtuemart is installed or not ?
		 */
		public function isVirtueMartExisted(){
			return  is_dir(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart');
		}
		
		/**
		 * get list of product 
		 *
		 *
		 * @access private
		 */
		public function __getList($params)
		{
			global $sess, $mm_action_url;
			require_once CLASSPATH . 'ps_product.php';
			$ps_product 	= new ps_product;
			$ordering      = $params->get('vm_ordering', 'cdate_asc'); 
			$limit 	       = $params->get('limit_items', 4);
			$ordering      = str_replace('_', '  ', $ordering);
			$thumbWidth    = (int)$params->get('thumbnail_width', 35);
			$thumbHeight   = (int)$params->get('thumbnail_height', 60);
			$imageHeight   = (int)$params->get('main_height', 300) ;
			$imageWidth    = (int)$params->get('main_width', 660) ;
			$isThumb       = $params->get('auto_renderthumb',1);
			$image_quanlity = $params->get('image_quanlity', 100);
			  $titleMaxChars = $params->get('title_max_chars', '100');
			  $isStripedTags = $params->get('auto_strip_tags', 0);
			  $descriptionMaxChars = $params->get('description_max_chars', 100);
      
			$extraURL 	   = $params->get('open_target')!='modalbox'?'':'&tmpl=component'; 
			if(trim($ordering[0]) == 'rand'){
				$ordering = " RAND() ";
			}
			$condition = LofSliderGroupVirtuemart::buildConditionQuery($params);
			// sql query
			$query 	= ' SELECT p.*, p.product_id, p.product_publish, p.product_sku, p.product_name, p.product_url '
					. ' 	, p.product_s_desc, product_thumb_image, product_full_image'
					. ' 	, c.category_id, c.category_flypage'
					. ' FROM #__{vm}_product AS p '
					. ' JOIN #__{vm}_product_category_xref as pc ON p.product_id=pc.product_id ';
			$query  .= $condition;
			$query .= ' JOIN #__{vm}_category as c ON pc.category_id=c.category_id ';
			$query .= ' WHERE p.product_publish = \'Y\' AND c.category_publish = \'Y\' AND product_parent_id=0 ';
			if(CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != '1') {
				$query .= ' AND product_in_stock > 0 ';
			}
		
			$query .= ' ORDER BY  ' . $ordering;
			$query .= ' LIMIT '. $limit; 
			
			require_once (CLASSPATH. 'ps_product.php');
			$ps_product = new ps_product;
			$database = new ps_DB();
			$database->query($query);
			$rows = $database->record;
			if(!empty($rows)){
				foreach($rows as $key => $item){
					$tmpimage =  $rows[$key]->product_full_image;
					if(!(strtolower(substr($tmpimage, 0, 4)) == 'http')) {
						$rows[$key]->product_image_url = IMAGEURL . 'product/' . $rows[$key]->product_full_image;
					} else {
						$rows[$key]->product_image_url =  $rows[$key]->product_full_image;
					}
					$cid = $rows[$key]->category_id;
					
			//		$rows[$key]->description  = '<div class="vm-product-snapshot">'.$ps_product->product_snapshot($rows[$key]->product_sku, true, true).'</div>';
					$rows[$key]->description = $this->substring($rows[$key]->product_desc, $descriptionMaxChars, $isStripedTags) ;
          $rows[$key]->introtext = $rows[$key]->product_desc ;
					$flypage = $rows[$key]->category_flypage ? $rows[$key]->category_flypage : $ps_product->get_flypage($rows[$key]->product_id);
					
					$rows[$key]->link = '?page=shop.product_details&category_id=' .$cid . '&flypage=' . $flypage . '&product_id=' . $rows[$key]->product_id.$extraURL;
					$rows[$key]->link  =  $sess->url($mm_action_url. "index.php" .$rows[$key]->link);
					$rows[$key]->title = $this->substring( $rows[$key]->product_name, $titleMaxChars);
					$rows[$key]->mainImage = $rows[$key]->thumbnail = ''; 
					
					if($rows[$key]->product_image_url&&$image=self::renderThumb($rows[$key]->product_image_url, $imageWidth, $imageHeight, $rows[$key]->title, $isThumb,$image_quanlity)){
						$rows[$key]->mainImage = $image;
					}
					if($rows[$key]->product_image_url&&$image=self::renderThumb($rows[$key]->product_image_url, $thumbWidth, $thumbHeight, $rows[$key]->title, $isThumb,$image_quanlity)){
						$rows[$key]->thumbnail = $image;
					}
					
					$url = "?page=shop.cart&func=cartAdd&product_id=" . $rows[$key]->product_id ;
					$rows[$key]->addtocart_link = $sess->url($mm_action_url. "index.php" . $url);
					
			
				
				}
				return $rows;
			}
			return array();
		}
		
		/**
		 * build condition query base parameter  
		 * 
		 * @param JParameter $params;
		 * @return string.
		 */
		  function buildConditionQuery($params)
		  {
			$source = trim($params->get('vm_source', 'vm_category'));
			if($source == 'vm_category'){
				$catids = $params->get('vm_category','0');
				
				if(!$catids){
					return '';
				}
				$catids = !is_array($catids) ? $catids : '"'.implode('","',$catids).'"';
				$condition = ' AND  pc.category_id IN('.$catids.')';
			} else {
				$ids = preg_split('/,/',$params->get('vm_items_ids',''));	
				$tmp = array();
				foreach($ids as $id){
					$tmp[] = (int) trim($id);
				}
				$condition = " AND pc.product_id IN('". implode("','", $tmp) ."')";
			}
			return $condition;
		}
	}
}