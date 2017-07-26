<?php
/**
 * @package     RedShop
 * @subpackage  redshop_payment
 * @copyright   Copyright (C) 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Create Color image plugin
 *
 * @package     RedShop
 * @subpackage  redshop_product
 * @since       1.3.3
 */
class Plgredshop_ProductCreateColorImage extends JPlugin
{
	/**
	 * Image Name
	 *
	 * @var  string
	 */
	public $ImageName = null;

	/**
	 * Trigger before image load
	 *
	 * @param   array  $productArr     Product Information array
	 * @param   array  $pluginResults  Image Information array
	 *
	 * @return  void
	 */
	public function onBeforeImageLoad($productArr, $pluginResults = array())
	{
		if (!extension_loaded('imagick'))
		{
			return;
		}

		$producthelper     = productHelper::getInstance();
		$product_id        = $productArr['product_id'];
		$main_imgwidth     = $productArr['main_imgwidth'];
		$main_imgheight    = $productArr['main_imgheight'];
		$property_data     = $productArr['property_data'];
		$subproperty_data  = $productArr['subproperty_data'];
		$property_id       = urldecode($productArr['property_id']);
		$subproperty_id    = urldecode($productArr['subproperty_id']);
		$url               = JURI::base();
		$imagePath         = $url . "components/com_redshop/assets/images";
		$arrpReturn        = $producthelper->getdisplaymainImage($product_id);
		$productImage      = $arrpReturn['imagename'];
		$arrproperty_id    = explode('##', $property_data);
		$arrsubproperty_id = explode('##', $subproperty_data);

		JTable::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/tables');
		$propertyItem = JTable::getInstance('attribute_property', 'Table');

		$checkflg     = false;
		$checkReverse = false;
		$ImageName    = $productImage;
		$section      = 'product';
		$colorToBeReplaced = $this->params->get('colorToBeReplaced');
		$bgImage = $this->params->get('bgImage');

		for ($i = 0, $count = count($arrproperty_id); $i < $count; $i++)
		{
			if (!empty($arrproperty_id[$i]))
			{
				$Arrresult   = $producthelper->getProperty($arrproperty_id[$i], 'property');
				$extra_field = $Arrresult->extra_field;

				if ($extra_field && $extra_field != 'reverse')
				{
					$checkflg        = true;
					$ext             = JFile::getExt($productImage);
					$ImageName       = $product_id . '_' . $property_id . '_' . str_replace('#', '', $extra_field) . "." . $ext;
					$this->ImageName = $ImageName;
					$section         = "product_attributes";

					$propertyItem->load($property_id);
					$propertyItem->property_id         = $property_id;
					$propertyItem->property_main_image = $ImageName;

					$propertyItem->bind($propertyItem);
					$propertyItem->store($propertyItem);

					if ($extra_field == $colorToBeReplaced)
					{
						$imageProperty = new Imagick($imagePath . "/product/" . $productImage);
						$width = $imageProperty->getImageWidth();
						$height = $imageProperty->getImageHeight();
						$cmd = "convert " . JPATH_SITE . "/$bgImage -resize " . $width . "x" . $height . " "
							. JPATH_COMPONENT . "/assets/images/product_attributes/" . $ImageName
							. " -gravity center -composite -mosaic " . JPATH_COMPONENT
							. "/assets/images/product_attributes/$ImageName";
					}
					else
					{
						$cmd = "convert $imagePath/product/$productImage +level-colors '"
							. $extra_field . "', " . JPATH_COMPONENT . "/assets/images/product_attributes/$ImageName";
					}

					exec($cmd);

					$property_id = $arrproperty_id[$i];
					$fileName    = $property_id . '_' . str_replace('#', '', $extra_field);
				}

				if ($extra_field == 'reverse')
				{
					$checkReverse = true;
				}
			}
		}

		if ($checkReverse)
		{
			$cmd = "convert " . JPATH_COMPONENT . "/assets/images/$section/"
				. $ImageName . " -flop " . JPATH_COMPONENT . "/assets/images/product_attributes/reverse$ImageName";
			$this->ImageName = $ImageName = "reverse" . $ImageName;
			exec($cmd);
		}

		if (!$checkflg && !$checkReverse)
		{
			return;
		}

		$aHrefImageResponse              = $imagePath . "/property/" . $ImageName;
		$Arrreturn['imagename']          = $ImageName;
		$Arrreturn['aHrefImageResponse'] = $aHrefImageResponse;

		$mainImageResponse = RedShopHelperImages::getImagePath(
			$ImageName,
			'',
			'thumb',
			'product_attributes',
			$main_imgwidth,
			$main_imgheight,
			Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
		);

		$arrReturn['mainImageResponse'] = $mainImageResponse;
		$arrReturn['imageTitle']        = 'colorImage';
		$arrReturn['attrbimg']          = $url . "components/com_redshop/assets/images/product_attributes/" . $ImageName;

		$pluginResults = $arrReturn;

		return;
	}
}
