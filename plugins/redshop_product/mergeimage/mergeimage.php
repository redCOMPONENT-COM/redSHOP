<?php
/**
 * @package     RedShop
 * @subpackage  redshop_payment
 * @copyright   Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminImages');

/**
 * Merge Products Images with Attribute Images
 *
 * @package     RedShop
 * @subpackage  redshop_product
 * @since       1.3.3
 */
class PlgRedshop_ProductMergeImage extends JPlugin
{
	/**
	 * Method will trigger Before redSHOP Image load
	 *
	 * @param   array  $productArr  Product Image information
	 *
	 * @return  array  Product Image Merge information
	 */
	function onBeforeImageLoad($productArr)
	{
		$producthelper 	= new producthelper;

		$product_id            = $productArr['product_id'];
		$main_imgwidth         = $productArr['main_imgwidth'];
		$main_imgheight        = $productArr['main_imgheight'];
		$property_data         = $productArr['property_data'];
		$subproperty_data      = $productArr['subproperty_data'];
		$property_id           = urldecode($productArr['property_id']);
		$subproperty_id        = urldecode($productArr['subproperty_id']);

		$arrpReturn            = $producthelper->getdisplaymainImage($product_id);
		$arrImage['product'][] = $arrpReturn['imagename'];
		$arrproperty_id        = explode('##', $property_data);
		$arrsubproperty_id     = explode('##', $subproperty_data);
		$arrId[]               = $product_id;

		for ($i = 0;$i < count($arrproperty_id); $i++)
		{
			if (!empty($arrproperty_id[$i]))
			{
				$Arrresult = $producthelper->getdisplaymainImage($product_id, $arrproperty_id[$i]);

				if ($property_id == $arrproperty_id[$i])
				{
					$pr_number           = $Arrresult['pr_number'];
					$aTitleImageResponse = $Arrresult['aTitleImageResponse'];
					$imageTitle          = $Arrresult['imageTitle'];
				}

				$arrImage['property'][] = $Arrresult['imagename'];
				$arrId[]                = 'p' . $arrproperty_id[$i];
			}
		}

		for ($i = 0; $i < count($arrsubproperty_id); $i++)
		{
			if (!empty($arrsubproperty_id[$i]))
			{
				$Arrresult = $producthelper->getdisplaymainImage($product_id, 0, $arrsubproperty_id[$i]);

				if ($subproperty_id == $arrsubproperty_id[$i])
				{
					$pr_number           = $Arrresult['pr_number'];
					$aTitleImageResponse = $Arrresult['aTitleImageResponse'];
					$imageTitle          = $Arrresult['imageTitle'];
				}

				$arrImage['subproperty'][] = $Arrresult['imagename'];
				$arrId[]                   = 'sp' . $arrsubproperty_id[$i];
			}
		}

		$newImagename                   = implode('_', $arrId) . '.png';
		$url                            = JURI::base();

		$mainImageResponse              = $this->mergeImage($arrImage, $main_imgwidth, $main_imgheight, $newImagename);
		$arrReturn['mainImageResponse'] = $mainImageResponse;
		$arrReturn['imageTitle']        = $imageTitle;
		$arrReturn['ImageName']         = $url . 'components/com_redshop/assets/images/mergeImages/' . $newImagename;

		return $arrReturn;
	}

	/**
	 * Perform Merge
	 *
	 * @param   array    $arrImage        Images Array
	 * @param   integer  $main_imgwidth   Image width
	 * @param   integer  $main_imgheight  Image Height
	 * @param   string   $newImagename    Merge Image name
	 *
	 * @return  string   Merged Image name
	 */
	function mergeImage($arrImage=array(), $main_imgwidth, $main_imgheight, $newImagename)
	{
		$url             = JURI::root();

		$DestinationFile = JPATH_BASE . '/components/com_redshop/assets/images/mergeImages/' . $newImagename;
		$productImage    = JPATH_BASE . '/components/com_redshop/assets/images/product/' . $arrImage['product'][0];
		$final_img       = imagecreatefrompng($productImage);
		$arrproperty     = $arrImage['property'];
		$arrsubproperty  = $arrImage['subproperty'];

		for ($i = 0; $i < count($arrproperty); $i++)
		{
			$image_2 = '';

			$arrImageproperty = JPATH_BASE . '/components/com_redshop/assets/images/property/' . $arrproperty[$i];

			if (file_exists($arrImageproperty))
			{
				if ($arrImageproperty)
				{
					$image_2 = imagecreatefrompng($arrImageproperty);
				}

				list($width, $height, $type, $attr) = getimagesize($arrImageproperty);
				imagecopy($final_img, $image_2, 0, 0, 0, 0, $width, $height);
			}

			if (isset($arrsubproperty[$i]) && $arrsubproperty[$i])
			{
				$image_3 = '';
				$arrImagesubproperty = JPATH_BASE . '/components/com_redshop/assets/images/subproperty/' . $arrsubproperty[$i];

				if (file_exists($arrImagesubproperty))
				{
					$image_3                            = imagecreatefrompng($arrImagesubproperty);
					list($width, $height, $type, $attr) = getimagesize($arrImagesubproperty);

					imagecopy($final_img, $image_3, 0, 0, 0, 0, $width, $height);
				}
			}
		}

		imagealphablending($final_img, true);
		imagesavealpha($final_img, true);
		imagepng($final_img, $DestinationFile);

		$DestinationFile = RedShopHelperImages::getImagePath(
			$newImagename,
			'',
			'thumb',
			'mergeImages',
			$main_imgwidth,
			$main_imgheight,
			0
		);

		return $DestinationFile;
	}
}
