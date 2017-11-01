<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  2.1
 */
class RedshopTagsSectionsAccessory extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  2.1
	 */
	public $tags = array(
		'{accessory_preview_image}'
	);

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since   2.1
	 */
	public function init()
	{
		$productHelper = productHelper::getInstance();
		$accessory     = $this->data['accessory'];
		$count         = count($accessory);
		$previewImage  = '';

		for ($a = 0; $a < $count; $a++)
		{
			$accessoryId = $accessory[$a]->child_product_id;
			$productInfo = $productHelper->getProductById($accessoryId);
			$imageUrl    = RedshopHelperMedia::getImagePath(
				$productInfo->product_preview_image,
				'',
				'thumb',
				'product',
				Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH'),
				Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT'),
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			$previewImage .= RedshopLayoutHelper::render(
				'tags.accessory.preview_image',
				array(
					'accessoryId' => $accessoryId,
					'imageUrl'    => $imageUrl,
					'productInfo' => $productInfo
				),
				'',
				array(
					'component' => 'com_redshop'
				)
			);
		}

		$this->addReplace('{accessory_preview_image}', $previewImage);
	}
}
