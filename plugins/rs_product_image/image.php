<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.plugin.plugin');
//require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_SITE . DS . 'components'. DS .'com_redshop'. DS .'helpers' . DS . 'product.php');
class plgredshop_productimage extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	 function plgredshop_productimage( &$subject )
	 {
	    parent::__construct( $subject );

	    // load plugin parameters
	    $this->_plugin = JPluginHelper::getPlugin( 'redshop_product', 'onBeforeImageLoad' );
	    $this->_params = new JParameter( $this->_plugin->params );
	 }

	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param 	object		The Product Template Data
	 * @param 	object		The product params
	 * @param 	object		The product object
	 */
	function onBeforeImageLoad($productArr)
	 {
	 	$producthelper 	= new producthelper();
	 
	 	$product_id = $productArr['product_id'];
	 	$main_imgwidth = $productArr['main_imgwidth'];
		$main_imgheight = $productArr['main_imgheight'];
		$property_data = $productArr['property_data'];
		$subproperty_data = $productArr['subproperty_data'];
		$property_id = urldecode($productArr['property_id']);
		$subproperty_id = urldecode($productArr['subproperty_id']);
		
		$arrpReturn 			= $producthelper->getdisplaymainImage($product_id);
		$arrImage['product'][] 	= $arrpReturn['imagename'];
		$arrproperty_id			= explode('##',$property_data);
		$arrsubproperty_id		= explode('##',$subproperty_data);
		$arrId[]				= $product_id;	
		
		for($i=0;$i<count($arrproperty_id);$i++)
		{
			if(!empty($arrproperty_id[$i]))
			{	
				$Arrresult 					= $producthelper->getdisplaymainImage($product_id, $arrproperty_id[$i]);
				
				if($property_id==$arrproperty_id[$i])
				{
					$pr_number				= $Arrresult['pr_number'];
					$aTitleImageResponse	= $Arrresult['aTitleImageResponse'];
					$imageTitle				= $Arrresult['imageTitle'];
				}
				$arrImage['property'][]		= $Arrresult['imagename'];
				$arrId[]					= 'p'.$arrproperty_id[$i];
			}
		}
		
		for($i=0;$i<count($arrsubproperty_id);$i++)
		{
			if(!empty($arrsubproperty_id[$i]))
			{
				$Arrresult 					= $producthelper->getdisplaymainImage($product_id, $arrsubproperty_id[$i]);
				if($subproperty_id==$arrsubproperty_id[$i])
				{
					$pr_number				= $Arrresult['pr_number'];
					$aTitleImageResponse	= $Arrresult['aTitleImageResponse'];
					$imageTitle				= $Arrresult['imageTitle'];
				}
				$arrImage['subproperty'][]	= $Arrresult['imagename'];
				$arrId[]					= 'sp'.$arrsubproperty_id[$i];
			}
		}
//		$Arrreturn['aHrefImageResponse']	= $aHrefImageResponse;
//		$Arrreturn['mainImageResponse']		= $mainImageResponse;
//		$Arrreturn['productmainimg']		= $productmainimg;
//		$Arrreturn['aTitleImageResponse']	= $aTitleImageResponse;
//		$Arrreturn['imagename']				= $imagename;
//		$Arrreturn['type']					= $type;
//		$Arrreturn['attrbimg']				= $attrbimg;
//		$Arrreturn['pr_number']				= $pr_number;
		
		
		$newImagename 					= implode('_',$arrId).'.png';
		$url= JURI::base();
		$mainImageResponse 				= $this->mergeImage($arrImage,$main_imgwidth,$main_imgheight,$newImagename);
		$arrReturn['mainImageResponse']	= $mainImageResponse;//$result['mainImageResponse'];
		$arrReturn['imageTitle']		= $imageTitle;
		$arrReturn['ImageName']			=  $url."components/com_redshop/assets/images/mergeImages/".$newImagename;
		return $arrReturn;
	 }
	 
	
	 
	function mergeImage($arrImage=array(),$main_imgwidth,$main_imgheight,$newImagename)
	{
		$url= JURI::root();
		
		$DestinationFile = JPATH_BASE."/components/com_redshop/assets/images/mergeImages/".$newImagename;
		$chkexist = $this->getDirectoryList($newImagename);
		if($chkexist==false)
		{
			$DestinationFile		= $url."components/com_redshop/helpers/thumb.php?filename=mergeImages/".$newImagename."&newxsize=".$main_imgwidth."&newysize=".$main_imgheight."&swap=".USE_IMAGE_SIZE_SWAPPING;
			return $DestinationFile;
		}
		
		$productImage	 	=  JPATH_BASE."/components/com_redshop/assets/images/product/".$arrImage['product'][0];
		$final_img 			= imagecreatefrompng($productImage);
		$arrproperty		= $arrImage['property'];
		$arrsubproperty		= $arrImage['subproperty'];

		for($i=0;$i<count($arrproperty);$i++)
		{
			$image_2 ='';	
			$arrImageproperty	 	=  JPATH_BASE."/components/com_redshop/assets/images/property/".$arrproperty[$i];
			$image_2 				= imagecreatefrompng($arrImageproperty);
			list($width, $height, $type, $attr) = getimagesize($arrImageproperty);
//			imagecopy($final_img, $image_2, 0, 0, 0, 0, $main_imgwidth, $main_imgheight);
			imagecopy($final_img, $image_2, 0, 0, 0, 0, $width, $height);
		}
		
		for($i=0;$i<count($arrsubproperty);$i++)
		{
			$image_3 ='';	
			$arrImagesubproperty	 	=  JPATH_BASE."/components/com_redshop/assets/images/subcolor/".$arrsubproperty[$i];
			$image_3				= imagecreatefrompng($arrImagesubproperty);
			list($width, $height, $type, $attr) = getimagesize($arrImagesubproperty);
//			imagecopy($final_img, $image_3, 0, 0, 0, 0, $main_imgwidth, $main_imgheight);
			imagecopy($final_img, $image_3, 0, 0, 0, 0, $width, $height);
		}
		imagealphablending($final_img, true);
		imagesavealpha($final_img, true);
		imagepng($final_img,$DestinationFile);

//		$DestinationFile = $url."components/com_redshop/assets/images/mergeImages/".$newImagename;
		$DestinationFile		= $url."components/com_redshop/helpers/thumb.php?filename=mergeImages/".$newImagename."&newxsize=".$main_imgwidth."&newysize=".$main_imgheight."&swap=".USE_IMAGE_SIZE_SWAPPING;
		return $DestinationFile; 
	}
	
	function getDirectoryList($newImagename)
	{ 
		$dirname = JPATH_COMPONENT_SITE.DS."assets/images/mergeImages";	
		
		if (is_dir($dirname))
		{
			$dir_handle = opendir($dirname);
			if ($dir_handle)
			{
				while($file = readdir($dir_handle))
				{	
					if ($file == $newImagename)
					{
						return false;
					}
				}
			}
			closedir($dir_handle);
		}
		return true;
	}
	
}
?>
