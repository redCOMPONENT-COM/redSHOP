<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedShopHelperImages
 *
 * @since  ever
 */
class RedShopHelperImages extends JObject
{
	/**
	 * Protected! Use the getInstance
	 */
	protected function RedShopHelperImages()
	{
		// Parent Helper Construction
		parent::__construct();
	}

	/**
	 * Function cleanFileName.
	 *
	 * @param   string  $fileName  File name
	 * @param   int     $id        ID current item
	 *
	 * @return string
	 */
	public static function cleanFileName($fileName, $id = null)
	{
		$fileExt = strtolower(JFile::getExt($fileName));
		$fileNameNoExt = JFile::stripExt(basename($fileName));
		$fileNameNoExt = preg_replace("/[&'#]/", '', $fileNameNoExt);
		$fileNameNoExt = JApplication::stringURLSafe($fileNameNoExt);
		$fileName = JPath::clean($fileName);
		$segments = explode(DIRECTORY_SEPARATOR, $fileName);

		if (strlen($fileNameNoExt) == 0)
		{
			$fileNameNoExt = $id;
		}

		$fileName = time() . '_' . $fileNameNoExt . '.' . $fileExt;
		$fileName = substr($fileName, 0, 50);

		if (count($segments) > 1)
		{
			$segments[count($segments) - 1] = $fileName;

			return implode(DIRECTORY_SEPARATOR, $segments);
		}
		else
		{
			return $fileName;
		}
	}

	/**
	 * Get redSHOP images live thumbnail path
	 *
	 * @param   string   $imageName     Image Name
	 * @param   string   $dest          Image Destination path
	 * @param   string   $command       Commands like thumb, upload etc...
	 * @param   string   $type          Thumbnail for types like, product, category, subcolor etc...
	 * @param   integer  $width         Thumbnail Width
	 * @param   integer  $height        Thumbnail Height
	 * @param   integer  $proportional  Thumbnail Proportional sizing enable / disable.
	 *
	 * @return  string                 Thumbnail Live path
	 */
	public static function getImagePath($imageName, $dest, $command = 'upload', $type = 'product', $width = 50, $height = 50, $proportional = USE_IMAGE_SIZE_SWAPPING)
	{
		// Set Default Type
		if ($type === '')
		{
			return REDSHOP_FRONT_IMAGES_ABSPATH . 'noimage.jpg';
		}

		// Set Default Width
		if ((int) $width <= 0)
		{
			$width = 50;
		}

		// Set Default Height
		if ((int) $height <= 0)
		{
			$height = 50;
		}

		$filePath     = JPATH_SITE . '/components/com_redshop/assets/images/' . $type . '/' . $imageName;
		$physiclePath = self::generateImages($filePath, $dest, $command, $type, $width, $height, $proportional);
		$thumbUrl     = REDSHOP_FRONT_IMAGES_ABSPATH . $type . '/thumb/' . basename($physiclePath);

		return $thumbUrl;
	}

	public static function generateImages($file_path, $dest, $command = 'upload', $type, $width, $height, $proportional = USE_IMAGE_SIZE_SWAPPING)
	{
		$info = getimagesize($file_path);
		$ret = false;

		switch (strtolower($info['mime']))
		{
			case 'image/png':
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/gif':

				if (JFile::exists($dest) && !empty($dest))
				{
					mt_srand();
					$rand1 = mt_rand(0, mt_getrandmax());
					mt_srand();
					$rand2 = mt_rand(0, mt_getrandmax());
					mt_srand();
					$rand3 = mt_rand(0, mt_getrandmax());
					mt_srand();
					$rand4 = mt_rand(0, mt_getrandmax());

					$dest = $original_path . '/' . $data['image_name'] . '-' . $rand1 . $rand2 . $rand3 . $rand4
						. '.' . JFile::getExt(strtolower($file['name']));
				}

				// This method should be expanded to be useable for other purposes not just making thumbs
				// But for now it just makes thumbs and proceed to the else part
				if ($command != 'thumb')
				{
					switch ($command)
					{
						case 'copy':
							if (!JFile::copy($file_path, $dest))
							{
								return false;
							}
							break;
						case 'upload':
						default:
							if (!JFile::upload($file_path, $dest))
							{
								return false;
							}
							break;
					}
				}
				else
				{
					// THUMB
					$src = $file_path;
					$src_path_info = pathinfo($src);
					$dest = $src_path_info['dirname'] . '/thumb/' . $src_path_info['filename'] . '_w'
						. $width . '_h' . $height . '.' . $src_path_info['extension'];
					$alt_dest = '';

					if (!JFile::exists($dest))
					{
						$ret = self::writeImage($src, $dest, $alt_dest, $width, $height, $proportional);
					}

					else
					{
						$ret = $dest;
					}
				}

				break;
		}

		return $ret;
	}

	public static function writeImage($src, $dest, $alt_dest, $width, $height, $proportional = USE_IMAGE_SIZE_SWAPPING)
	{
		ob_start();
		self::resizeImage($src, $width, $height, $proportional, 'browser', false);
		$contents = ob_get_contents();
		ob_end_clean();

		if (JFile::exists($dest) && $alt_dest != '')
		{
			if (JFile::exists($alt_dest))
			{
				return false;
			}

			$dest = $alt_dest;
		}

		if (!JFile::write($dest, $contents))
		{
			return false;
		}

		return $dest;
	}

	public static function createDir($path)
	{
		if (!JFolder::exists($path))
		{
			if (!JFolder::create($path))
			{
				return false;
			}
			else
			{
				if (!JFile::exists($path . '/index.html'))
				{
					// Avoid 'pass by reference' error in J1.6+
					$content = '<html><body bgcolor="#ffffff"></body></html>';

					JFile::write($path . '/index.html', $content);
				}
			}
		}

		return true;
	}

	public static function resizeImage($file, $width = 0, $height = 0, $proportional = USE_IMAGE_SIZE_SWAPPING, $output = 'file', $delete_original = true, $use_linux_commands = false)
	{
		if ($height <= 0 && $width <= 0)
		{
			return false;
		}

		// Setting defaults and meta
		$info = getimagesize($file);
		list($width_old, $height_old) = $info;
		$horizontalCenter = 0;
		$verticalCenter = 0;


		// Calculating proportionality resize
		switch ($proportional)
		{
			case '1':
				if ($width == 0)
				{
					$factor = $height / $height_old;
				}
				elseif ($height == 0)
				{
					$factor = $width / $width_old;
				}
				else
				{
					$factor = min($width / $width_old, $height / $height_old);
				}

				$final_width = round($width_old * $factor);
				$final_height = round($height_old * $factor);
				break;

			// Resize and cropped
			case '2':
				$width = ($width <= 0) ? $width_old : $width;
				$height = ($height <= 0) ? $height_old : $height;
				$ratio_orig = $width_old / $height_old;

				if ($width / $height > $ratio_orig)
				{
					$final_height = $width / $ratio_orig;
					$final_width = $width;
				}
				else
				{
					$final_width = $height * $ratio_orig;
					$final_height = $height;
				}

				$x_mid = $final_width / 2;
				$y_mid = $final_height / 2;
				$horizontalCenter = $x_mid - ($width / 2);
				$verticalCenter = $y_mid - ($height / 2);
				break;

			// Not proportionality resize
			case '0':
			default:
				$final_width = ($width <= 0) ? $width_old : $width;
				$final_height = ($height <= 0) ? $height_old : $height;
		}

		// Loading image to memory according to type
		switch ($info[2])
		{
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($file);
				break;
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($file);
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($file);
				break;
			default:
				return false;
		}

		// This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor($final_width, $final_height);

		if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG))
		{
			$transparency = imagecolortransparent($image);

			if ($info[2] == IMAGETYPE_PNG)
			{
				imagealphablending($image_resized, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagesavealpha($image_resized, true);
			}

			elseif ($transparency >= 0)
			{
				$trnprt_color = imagecolorsforindex($image, $transparency);
				$transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				imagefill($image_resized, 0, 0, $transparency);
				imagecolortransparent($image_resized, $transparency);
			}
		}

		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);

		if ($proportional == 2)
		{
			$thumb = imagecreatetruecolor($width, $height);
			imagecopyresampled($thumb, $image_resized, 0, 0, $horizontalCenter, $verticalCenter, $width, $height, $width, $height);
			$image_resized = $thumb;
		}

		// Taking care of original, if needed
		if ($delete_original)
		{
			if ($use_linux_commands)
			{
				exec('rm ' . $file);
			}

			else
			{
				@unlink($file);
			}
		}

		// Preparing a method of providing result
		switch (strtolower($output))
		{
			case 'browser':
				$output = null;
				break;
			case 'file':
				$output = $destPath;
				break;
			case 'return':
				return $image_resized;
				break;
			default:
				break;
		}

		// Writing image according to type to the output destination
		switch ($info[2])
		{
			case IMAGETYPE_GIF:
				imagegif($image_resized, $output);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($image_resized, $output, IMAGE_QUALITY_OUTPUT);
				break;
			case IMAGETYPE_PNG:
				imagepng($image_resized, $output);
				break;
			default:
				@ImageDestroy($image_resized);
				@ImageDestroy($image);

				return false;
		}

		@ImageDestroy($image_resized);
		@ImageDestroy($image);

		return true;
	}

/**
	 * Some function which was in obscure reddesignhelper class.
	 *
	 * @return array
	 */
	public static function geticonarray()
	{
		$icon_array = array("products" => array('product', 'category', 'manufacturer', 'media'),
							"orders" => array('order', 'quotation', 'stockroom', 'container'),
							"discounts" => array('discount', 'giftcard', 'voucher', 'coupon'),
							"communications" => array('mail', 'newsletter'),
							"shippings" => array('shipping', 'shipping_box', 'shipping_detail', 'wrapper'),
							"users" => array('user', 'shopper_group', 'accessmanager'),
							"vats" => array('tax_group', 'currency', 'country', 'state'),
							"importexport" => array('import', 'xmlimport'),
							"altration" => array('fields', 'template', 'textlibrary'),
							"customerinput" => array('question', 'rating'),
							"accountings" => array('accountgroup'),
							"popular" => array(),
							"prodimages" => array('products48.png', 'categories48.png', 'manufact48.png', 'media48.png'),
							"orderimages" => array('order48.png', 'quotation_48.jpg', 'stockroom48.png', 'container48.png'),
							"discountimages" => array('discountmanagmenet48.png', 'giftcard_48.png', 'voucher48.png', 'coupon48.png'),
							"commimages" => array('mailcenter48.png', 'newsletter48.png'),
							"shippingimages" => array('shipping48.png', 'shipping_boxes48.png', 'shipping48.png', 'wrapper48.png'),
							"userimages" => array('user48.png', 'manufact48.png', 'catalogmanagement48.png'),
							"vatimages" => array('vatgroup_48.png', 'currencies_48.png', 'country_48.png', 'region_48.png'),
							"importimages" => array('importexport48.png', 'importexport48.png'),
							"altrationimages" => array('fields48.png', 'templates48.png', 'textlibrary48.png'),
							"customerinputimages" => array('question_48.jpg', 'rating48.png'),
							"accimages" => array('accounting_group48.png'),
							"popularimages" => array(),
							"prodtxt" => array('PRODUCTS', 'CATEGORIES', 'MANUFACTURERS', 'MEDIA'),
							"ordertxt" => array('ORDER', 'QUOTATION', 'STOCKROOM', 'CONTAINER'),
							"discounttxt" => array('DISCOUNT_MANAGEMENT', 'GIFTCARD', 'VOUCHER', 'COUPON_MANAGEMENT'),
							"commtxt" => array('MAIL_CENTER', 'NEWSLETTER'),
							"shippingtxt" => array('SHIPPING', 'SHIPPING_BOX', 'SHIPPING_DETAIL', 'WRAPPER'),
							"usertxt" => array('USER', 'SHOPPER_GROUP', 'ACCESS_MANAGER'),
							"vattxt" => array('TAX_GROUP', 'CURRENCY', 'COUNTRY', 'STATE'),
							"importtxt" => array('IMPORT_EXPORT', 'XML_IMPORT_EXPORT'),
							"altrationtxt" => array('FIELDS', 'TEMPLATES', 'TEXT_LIBRARY'),
							"customerinputtxt" => array('QUESTION', 'REVIEW'),
							"acctxt" => array('ECONOMIC_ACCOUNT_GROUP'),
							"populartxt" => array());

		return $icon_array;
	}
}
