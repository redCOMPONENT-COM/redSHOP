<?php
/**
 * @package     RedShop
 * @subpackage  redshop_payment
 * @copyright   Copyright (C) 2015 redCOMPONENT.com. All rights reserved.
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
	 * After Display Product method
	 *
	 * Method is called by the product view
	 *
	 * @param   string  &$template  The Product Template Data
	 * @param   object  $params     The product params
	 * @param   object  $product    The product object
	 *
	 * @return  null
	 */
	public function onAfterDisplayProduct(&$template, $params, $product)
	{
		$app = JFactory::getApplication();

		// No for admin
		if ($app->isAdmin())
		{
			return;
		}

		$view = JRequest::getCmd('view');

		if ($view != 'product')
		{
			return;
		}

		// Requests
		$option = JRequest::getCmd('option');
		$tmpl = JRequest::getCmd('tmpl');

		if ($option == "com_redshop" && $tmpl != "component")
		{
			$document = JFactory::getDocument();
			$document->addScriptDeclaration("
				function setZoomImagepath(elm) {
					var elmpath = elm.href;
					var elmfilenamepath = elmpath.replace(/\\\/g, '/').replace(/.*\//, '');
					var path = document.getElementById('main_image" . $product->product_id . "').src;
					var filenamepath = path.replace(/\\\/g, '/').replace(/.*\//, '');
					var imageName = filenamepath.split('&');
					imageName = imageName[0].replace(/_w\d+_h\d+/, '');

					var imageurl;

					if (/MSIE[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
						elmfilenamepath = decodeURI(elmfilenamepath);
						if (elmfilenamepath != imageName[0]) {
							imageurl = redSHOP.RSConfig._('SITE_URL') + 'components/com_redshop/assets/images/mergeImages/' + imageName;
							elm.href = imageurl;
						}
					}
					else {
						if (elmfilenamepath != imageName[0]) {
							imageurl = redSHOP.RSConfig._('SITE_URL')  + 'components/com_redshop/assets/images/mergeImages/' + imageName;
							elm.href = imageurl;
						}
					}
				}"
			);
		}

		return;
	}

	/**
	 * Method will trigger Before redSHOP Image load
	 *
	 * @param   array  $productArr  Product Image information
	 * @param   array  &$arrReturn  Returning information array
	 *
	 * @return  array  Product Image Merge information
	 */
	public function onBeforeImageLoad($productArr, &$arrReturn)
	{
		// Load plugin language file
		$this->loadLanguage();

		$producthelper         = new producthelper;

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
		$imageTitle            = '';

		for ($i = 0;$i < count($arrproperty_id); $i++)
		{
			if (!empty($arrproperty_id[$i]))
			{
				$arrResult = $producthelper->getdisplaymainImage($product_id, $arrproperty_id[$i]);

				if ($property_id == $arrproperty_id[$i])
				{
					$pr_number           = $arrResult['pr_number'];
					$aTitleImageResponse = $arrResult['aTitleImageResponse'];

					if (isset($arrResult['imageTitle']))
					{
						$imageTitle          = $arrResult['imageTitle'];
					}
				}

				$arrImage['property'][] = $arrResult['imagename'];
				$arrId[]                = 'p' . $arrResult['imagename'];
			}
		}

		for ($i = 0; $i < count($arrsubproperty_id); $i++)
		{
			if (!empty($arrsubproperty_id[$i]))
			{
				$arrResult = $producthelper->getdisplaymainImage($product_id, 0, $arrsubproperty_id[$i]);

				if ($subproperty_id == $arrsubproperty_id[$i])
				{
					$pr_number           = $arrResult['pr_number'];
					$aTitleImageResponse = $arrResult['aTitleImageResponse'];
					$imageTitle          = $arrResult['imageTitle'];
				}

				$arrImage['subproperty'][] = $arrResult['imagename'];
				$arrId[]                   = 'sp' . $arrResult['imagename'];
			}
		}

		$newImagename                   = implode('_', $arrId) . '.png';
		$mainImageResponse              = $this->mergeImage($arrImage, $main_imgwidth, $main_imgheight, $newImagename);
		$arrReturn['mainImageResponse'] = $mainImageResponse;
		$arrReturn['imageTitle']        = $imageTitle;
		$arrReturn['ImageName']         = JURI::base() . 'components/com_redshop/assets/images/mergeImages/' . $newImagename;

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
	public function mergeImage($arrImage=array(), $main_imgwidth, $main_imgheight, $newImagename)
	{
		$destinationFile = JPATH_BASE . '/components/com_redshop/assets/images/mergeImages/' . $newImagename;
		$productImage    = JPATH_BASE . '/components/com_redshop/assets/images/product/' . $arrImage['product'][0];

		// Only support png files for merge
		if ('png' != JFile::getExt($productImage))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('PLG_REDSHOP_PRODUCT_MERGEIMAGE_INVALID_EXTENSION'), 'error');

			return;
		}

		$final_img       = imagecreatefrompng($productImage);

		$arrproperty    = array();
		$arrsubproperty = array();

		if (isset($arrImage['property']))
		{
			$arrproperty     = $arrImage['property'];
		}

		if (isset($arrImage['subproperty']))
		{
			$arrsubproperty  = $arrImage['subproperty'];
		}

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
		imagepng($final_img, $destinationFile);

		$destinationFile = RedShopHelperImages::getImagePath(
			$newImagename,
			'',
			'thumb',
			'mergeImages',
			$main_imgwidth,
			$main_imgheight,
			0
		);

		return $destinationFile;
	}
}
