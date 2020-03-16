<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0
 */
class RedshopTagsSectionsManufacturerDetail extends RedshopTagsAbstract
{
	/**
	 * @var    integer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $itemId;

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function init()
	{
		$this->itemId = Factory::getApplication()->input->getInt('Itemid', 0);
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION
	 */
	public function replace()
	{
		$db        = Factory::getDbo();
		$pageTitle = Text::_('COM_REDSHOP_MANUFACTURER_DETAIL');

		if ($this->data['pageHeadingTag'] != '') {
			$pageTitle = $this->data['pageHeadingTag'];
		}

		$this->template = RedshopLayoutHelper::render(
				'tags.common.pageheading',
				[
					'params'         => $this->data['params'],
					'pageheading'    => $db->escape($this->data['params']->get('page_title')),
					'pageHeadingTag' => $pageTitle,
					'class'          => 'manufacturer-detail'
				],
				'',
				$this->optionLayout
			) . $this->template;

		$row            = !empty($this->data['detail']) ? $this->data['detail'][0] : null;
		$manufacturerId = null !== $row ? $row->id : 0;
		$category       = \Redshop\Manufacturer\Helper::getManufacturerCategory($manufacturerId, $row);

		$this->replaceCategory($manufacturerId, $category);

		if ($this->isTagExists('{manufacturer_image}')) {
			$thumbImage = '';
			$media      = null !== $row ? RedshopEntityManufacturer::getInstance($manufacturerId)->getMedia() : null;

			if (null !== $media) {
				$mediaImagePath = $media->getAbsImagePath();

				if (!empty($mediaImagePath)) {
					$thumbHeight = Redshop::getConfig()->get('MANUFACTURER_THUMB_HEIGHT');
					$thumbWidth  = Redshop::getConfig()->get('MANUFACTURER_THUMB_WIDTH');

					if (Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE') || Redshop::getConfig()->get(
							'WATERMARK_MANUFACTURER_THUMB_IMAGE'
						)) {
						$imagePath = RedshopHelperMedia::watermark(
							'manufacturer',
							$media->get('media_name'),
							$thumbWidth,
							$thumbHeight,
							Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE')
						);
					} else {
						$imagePath = $media->generateThumb($thumbWidth, $thumbHeight);
					}

					$altText = $media->get('media_alternate_text', $row->name);

					$thumbImage = RedshopLayoutHelper::render(
						'tags.common.img_link',
						[
							'link'     => $mediaImagePath,
							'linkAttr' => 'rel="{handler: \'image\', size: {}}" title="' . $altText . '"',
							'src'      => $imagePath['abs'],
							'alt'      => $altText,
							'imgAttr'  => 'title="' . $altText . '"'
						],
						'',
						$this->optionLayout
					);
				}
			}

			$this->replacements['{manufacturer_image}'] = $thumbImage;
		}

		$this->replacements['{manufacturer_name}'] = null !== $row ? $row->name : '';

		// Replace Manufacturer URL
		if ($this->isTagExists('{manufacturer_url}')) {
			$this->replacements['{manufacturer_url}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => $row->manufacturer_url,
					'content' => $row->manufacturer_url
				],
				'',
				$this->optionLayout
			);
		}

		// Extra field display
		$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(10, 1, 1);
		$this->template = RedshopHelperProductTag::getExtraSectionTag(
			$extraFieldName,
			$manufacturerId,
			RedshopHelperExtrafields::SECTION_MANUFACTURER,
			$this->template
		);

		$this->replacements['{manufacturer_description}'] = null !== $row ? $row->description : '';

		if ($this->isTagExists('{manufacturer_extra_fields}')) {
			$this->replacements['{manufacturer_extra_fields}'] = RedshopHelperExtrafields::listAllFieldDisplay(
				RedshopHelperExtrafields::SECTION_MANUFACTURER,
				$manufacturerId
			);
		}

		$this->replacements['{manufacturer_link}'] = Route::_(
			'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $manufacturerId . '&Itemid=' . $this->itemId
		);

		$this->replacements['manufacturer_allproductslink'] = Route::_(
			'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $manufacturerId . '&Itemid=' . $this->itemId
		);

		$this->replacements['{manufacturer_allproductslink_lbl}'] = Text::_(
			'COM_REDSHOP_MANUFACTURER_ALLPRODUCTSLINK_LBL'
		);

		$this->template = $this->strReplace($this->replacements, $this->template);
		$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

		return parent::replace();
	}

	private function replaceCategory($manufacturerId, $category)
	{
		$subTemplate = $this->getTemplateBetweenLoop('{category_loop_start}', '{category_loop_end}');

		if (!empty($subTemplate)) {
			$midTemplate = '';

			if ($subTemplate['template'] != "") {
				for ($i = 0, $in = count($category); $i < $in; $i++) {
					$replaceCategory = [];

					$replaceCategory['{category_name_with_link}'] = RedshopLayoutHelper::render(
						'tags.common.link',
						[
							'link'    => Route::_(
								'index.php?option=com_redshop&view=category&layout=detail&cid=' . $category[$i]->id . '&manufacturer_id=' . $manufacturerId . '&Itemid=' . $this->itemId
							),
							'content' => $category[$i]->name
						],
						'',
						$this->optionLayout
					);

					$replaceCategory['{category_desc}'] = $category[$i]->description;
					$replaceCategory['{category_name}'] = $category[$i]->name;

					$thumbUrl = RedshopHelperMedia::getImagePath(
						$category[$i]->category_full_image,
						'',
						'thumb',
						'category',
						200,
						200,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);

					$replaceCategory['{category_thumb_image}'] = RedshopLayoutHelper::render(
						'tags.common.img',
						[
							'src' => $thumbUrl
						],
						'',
						$this->optionLayout
					);

					$midTemplate .= $this->strReplace($replaceCategory, $subTemplate['template']);
				}
			}

			$this->template = $subTemplate['begin'] . $midTemplate . $subTemplate['end'];
		}
	}
}