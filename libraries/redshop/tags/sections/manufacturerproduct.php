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
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0
 */
class RedshopTagsSectionsManufacturerProduct extends RedshopTagsAbstract
{
	use \Redshop\Traits\Replace\Product;

	/**
	 * @var    string
	 *
	 * @since   3.0.1
	 */
	protected $width = 'MANUFACTURER_PRODUCT_THUMB_WIDTH';

	/**
	 * @var    string
	 *
	 * @since   3.0.1
	 */
	protected $height = 'MANUFACTURER_PRODUCT_THUMB_HEIGHT';

	public function init()
	{
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
		$app          = JFactory::getApplication();
		$url          = JUri::base();
		$itemId       = $app->input->getInt('Itemid');
		$print        = $app->input->getInt('print');
		$manufacturer = $this->data['manufacturer'];
		$db           = JFactory::getDbo();

		// Page Title
		if ($this->data['params']->get('show_page_heading', 1)) {
			$this->template = RedshopLayoutHelper::render(
					'tags.common.pageheading',
					[
						'params'         => $this->data['params'],
						'pageheading'    => trim($db->escape($this->data['params']->get('page_title'))),
						'pageHeadingTag' => JText::_('COM_REDSHOP_MANUFACTURER_PRODUCTS'),
						'class'          => 'manufacturer-product'
					],
					'',
					$this->optionLayout
				) . $this->template;
		}
		// Page title end

		if ($print) {
			$onClick = "onclick='window.print();'";
		} else {
			$printUrl = $url . "index.php?option=com_redshop&view=manufacturers&layout=products&mid=" . $manufacturer->id . "&print=1&tmpl=component&Itemid=" . $itemId;
			$onClick  = 'onclick="window.open(\'' . $printUrl . '\',\'mywindow\',\'scrollbars=1\',\'location=1\')"';
		}

		$this->replacements['{print}'] = RedshopLayoutHelper::render(
			'tags.common.print',
			[
				'onClick' => $onClick
			]
			,
			'',
			$this->optionLayout
		);

		$cartMData            = '';
		$manufacturerProducts = $this->data['manufacturerProducts'];
		$subTemplateProduct   = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

		if (!empty($subTemplateProduct)) {
			for ($i = 0, $in = count($manufacturerProducts); $i < $in; $i++) {
				$cartMData .= $this->replaceManufacturerProduct(
					$subTemplateProduct['template'],
					$manufacturerProducts[$i]
				);
			}
		}

		$this->template = $subTemplateProduct['begin'] . $cartMData . $subTemplateProduct['end'];

		if ($this->isTagExists('{manufacturer_image}')) {
			$thumbImage = '';
			$media      = RedshopEntityManufacturer::getInstance($manufacturer->id)->getMedia();

			if ($media->isValid() && !empty($media->get('media_name'))
				&& JFile::exists(
					REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $manufacturer->id . '/' . $media->get('media_name')
				)) {
				$thumbHeight = Redshop::getConfig()->get($this->height);
				$thumbWidth  = Redshop::getConfig()->get($this->width);

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
					$imagePath = RedshopHelperMedia::getImagePath(
						$media->get('media_name'),
						'',
						'thumb',
						'manufacturer',
						$thumbWidth,
						$thumbHeight,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'),
						'manufacturer',
						$manufacturer->id
					);
				}

				$altText = $media->get('media_alternate_text', $manufacturer->name);

				$thumbImage = RedshopLayoutHelper::render(
					'tags.common.img_link',
					[
						'link'     => REDSHOP_MEDIA_IMAGE_ABSPATH . 'manufacturer/' . $manufacturer->id . '/' . $media->get(
								'media_name'
							),
						'linkAttr' => 'rel="{handler: \'image\', size: {}}" title="' . $altText . '"',
						'src'      => $imagePath,
						'alt'      => $altText,
						'imgAttr'  => 'title="' . $altText . '"'
					],
					'',
					$this->optionLayout
				);
			}

			$this->replacements['{manufacturer_image}'] = $thumbImage;
		}

		$this->replacements['{manufacturer_name}'] = $manufacturer->name;

		// Extra field display
		$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(10, 1, 1);
		$this->template = RedshopHelperProductTag::getExtraSectionTag(
			$extraFieldName,
			$manufacturer->id,
			"10",
			$this->template
		);

		$this->replacements['{manufacturer_description}']  = $manufacturer->description;
		$this->replacements['{manufacturer_extra_fields}'] = RedshopHelperExtrafields::listAllFieldDisplay(
			10,
			$manufacturer->id
		);

		$this->replacements['{manufacturer_link}'] = JRoute::_(
			'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $manufacturer->id . '&Itemid=' . $itemId
		);

		if ($this->isTagExists('{filter_by}')) {
			$this->replacements['{filter_by}'] = RedshopLayoutHelper::render(
				'tags.manufacturer_product.filter_by',
				[
					'filterSelect' => $this->data['lists']['filter_select']
				],
				'',
				$this->optionLayout
			);
		}

		if ($this->isTagExists('{order_by}')) {
			$this->replacements['{order_by}'] = RedshopLayoutHelper::render(
				'tags.manufacturer_product.order_by',
				[
					'orderSelect' => $this->data['lists']['order_select']
				],
				'',
				$this->optionLayout
			);
		}

		if ($this->isTagExists('{pagination}')) {
			$this->replacements['{pagination}'] = $this->data['pagination']->getPagesLinks();
		}

		$this->template = $this->strReplace($this->replacements, $this->template);
		$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

		return parent::replace();
	}

	/**
	 * Replace manufacturer product
	 *
	 * @param string $template
	 * @param object $product
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION
	 */
	private function replaceManufacturerProduct($template, $product)
	{
		$cName    = '';
		$template = $this->replaceCommonProduct($template, $product->product_id);
		$replace  = [];

		$link = Route::_(
			'index.php?option=com_redshop&view=product&pid='
			. $product->product_id . '&cid=' . $product->cat_in_sefurl
		);

		$subTemplateCatHeading = $this->getTemplateBetweenLoop('{category_heading_start}', '{category_heading_end}');

		if (strstr($template, "{category_heading_start}") && strstr($template, "{category_heading_end}")) {
			if ($cName != $product->name) {
				$replace['{category_name}']          = $product->name;
				$replace['{category_heading_start}'] = '';
				$replace['{category_heading_end}']   = '';
			} else {
				$template = $subTemplateCatHeading['begin'] . $subTemplateCatHeading['end'];
			}
		}

		$replace['{read_more}'] = RedshopLayoutHelper::render(
			'tags.common.link',
			[
				'link'    => $link,
				'content' => Text::_('COM_REDSHOP_READ_MORE')
			],
			'',
			$this->optionLayout
		);

		$replace['{read_more_link}'] = $link;

		if (strstr($template, '{manufacturer_product_link}')) {
			$replace['{manufacturer_product_link}'] = $link;
		}

		return $this->strReplace($replace, $template);
	}
}