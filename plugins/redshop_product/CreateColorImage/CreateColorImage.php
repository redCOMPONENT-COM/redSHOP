<?php

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
require_once JPATH_COMPONENT . '/helpers/product.php';
JLoader::import('images', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');

class plgredshop_productCreateColorImage extends JPlugin
{
	public $ImageName = null;
	/*
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgredshop_productCreateColorImage( &$subject )
	{
		parent::__construct($subject);
	}

	/**
	 * This will change product image.
	 */
	function onBeforeImageLoad($productArr)
	{
		$producthelper 	= new producthelper;
		$product_id = $productArr['product_id'];
		$main_imgwidth = $productArr['main_imgwidth'];
		$main_imgheight = $productArr['main_imgheight'];
		$property_data = $productArr['property_data'];
		$subproperty_data = $productArr['subproperty_data'];
		$property_id = urldecode($productArr['property_id']);
		$subproperty_id = urldecode($productArr['subproperty_id']);
		$url = JURI::base();
		$imagePath = $url . "components/com_redshop/assets/images";
		$arrpReturn = $producthelper->getdisplaymainImage($product_id);
		$productImage = $arrpReturn['imagename'];
		$arrproperty_id	= explode('##', $property_data);

		$arrsubproperty_id = explode('##', $subproperty_data);
		$row = JTable::getInstance('attribute_property', 'Table');
		$checkflg = false;
		$checkReverse = false;
		$ImageName = $productImage;
		$section = 'product';

		for ($i = 0;$i < count($arrproperty_id);$i++)
		{
			if (!empty($arrproperty_id[$i]))
			{
				$Arrresult = $producthelper->getProperty($arrproperty_id[$i], 'property');
				$extra_field = $Arrresult->extra_field;

				if ($extra_field && $extra_field != 'reverse')
				{
					$checkflg = true;
					$ext = JFile::getExt($productImage);
					$ImageName = $property_id . '_' . str_replace('#', '', $extra_field) . "." . $ext;
					$this->ImageName = $ImageName;
					$propertyItem = $row->load($property_id);
					$propertyItem->property_id = $property_id;
					$propertyItem->property_main_image = $ImageName;
					$section = "product_attributes";
					$row->bind($propertyItem);
					$row->store($propertyItem);

					$cmd = "convert $imagePath/product/$productImage +level-colors '" . $extra_field . "', " . JPATH_COMPONENT . "/assets/images/product_attributes/$ImageName";
					exec($cmd);
					$property_id = $arrproperty_id[$i];
					$fileName = $property_id . '_' . str_replace('#', '', $extra_field);
				}

				if ($extra_field == 'reverse')
				{
					$checkReverse = true;
				}
			}
		}

		if ($checkReverse)
		{
			$cmd = "convert " . JPATH_COMPONENT . "/assets/images/$section/" . $ImageName . " -flop " . JPATH_COMPONENT . "/assets/images/product_attributes/reverse$ImageName";
			$this->ImageName = $ImageName = "reverse" . $ImageName;
			exec($cmd);
		}

		if (!$checkflg && !$checkReverse)
		{
			return false;
		}

		$aHrefImageResponse = $imagePath . "/property/" . $ImageName;
		$Arrreturn['imagename']	= $ImageName;
		$Arrreturn['aHrefImageResponse']	= $aHrefImageResponse;
		$mainImageResponse = RedShopHelperImages::getImagePath(
								$ImageName,
								'',
								'thumb',
								'product_attributes',
								$main_imgwidth,
								$main_imgheight,
								USE_IMAGE_SIZE_SWAPPING
							);
		$arrReturn['mainImageResponse']	= $mainImageResponse;
		$arrReturn['imageTitle'] = $imageTitle;
		$arrReturn['attrbimg'] = $url . "components/com_redshop/assets/images/product_attributes/" . $ImageName;

		return $arrReturn;
	}
}
