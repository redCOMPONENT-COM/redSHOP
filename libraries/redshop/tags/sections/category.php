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
class RedshopTagsSectionsCategory extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  2.1
	 */
	public $tags = array(
		'{category_name}',
		'{category_short_description}',
		'{category_description}',
		'{category_thumb_image}',
		'{category_thumb_image_1}',
		'{category_thumb_image_2}',
		'{category_thumb_image_3}',
	);

	/**
	 * @var    array
	 * @since  2.1
	 */
	public $tags_alias = array(
		'{category_short_desc}' => '{category_short_description}',
		'{categoryshortdesc}'   => '{category_short_description}',
		'{categorydesc}'        => '{category_description}',
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
		// Apply general init here
	}

	/**
	 * Override parent replace with own category replacing
	 *
	 * @return  string
	 *
	 * @since version
	 */
	public function replace()
	{
		if (isset($this->data['subCategories']))
		{
			// Execute category self replace before parent::replace
			$this->template = $this->replaceSubCategories($this->data['subCategories']);
		}

		// Replace main category if possible
		if (isset($this->data['category']))
		{
			$this->template = $this->replaceCategory($this->data['category'], $this->template);
		}

		return parent::replace();
	}

	/**
	 * Replace category fields tags
	 *
	 * @param   object $category Category object
	 * @param   string $template Template to replace
	 *
	 * @return  string
	 *
	 * @since   2.0.0.6
	 */
	private function replaceCategory($category, $template)
	{
		// Specific cases
		if ($this->isTagExists('{category_thumb_image}') && $this->isTagRegistered('{category_thumb_image}') && isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH'), Redshop::getConfig()->get('THUMB_HEIGHT'));
			$template      = str_replace("{category_thumb_image}", $categoryImage, $template);
		}

		if ($this->isTagExists('{category_thumb_image_1}') && $this->isTagRegistered('{category_thumb_image_1}') && isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH'), Redshop::getConfig()->get('THUMB_HEIGHT'));
			$template      = str_replace("{category_thumb_image_1}", $categoryImage, $template);
		}

		if ($this->isTagExists('{category_thumb_image_2}') && $this->isTagRegistered('{category_thumb_image_2}') && isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH_2'), Redshop::getConfig()->get('THUMB_HEIGHT_2'));
			$template      = str_replace("{category_thumb_image_2}", $categoryImage, $template);
		}

		if ($this->isTagExists('{category_thumb_image_3}') && $this->isTagRegistered('{category_thumb_image_3}') && isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH_3'), Redshop::getConfig()->get('THUMB_HEIGHT_3'));
			$template      = str_replace("{category_thumb_image_3}", $categoryImage, $template);
		}

		// Replace all registered tag if category object have it
		foreach ($this->tags as $tag)
		{
			$tag = str_replace('{', '', $tag);
			$tag = str_replace('}', '', $tag);

			// Make this this tag also have object property to use
			if (property_exists($category, $tag))
			{
				$template = str_replace('{' . $tag . '}', $category->{$tag}, $template);
			}
		}

		// Also replace with alias
		foreach ($this->tags_alias as $alias => $tag)
		{
			$tag = str_replace('{', '', $tag);
			$tag = str_replace('}', '', $tag);

			// Make this this tag also have object property to use
			if (property_exists($category, $tag))
			{
				$template = str_replace('{' . $alias . '}', $category->{$tag}, $template);
			}
		}

		return $template;
	}

	/**
	 * Replace sub categories tags
	 *
	 * @param   array $subCategories Sub categories array
	 *
	 * @return  string
	 *
	 * @since   2.0.0.6
	 */
	private function replaceSubCategories($subCategories)
	{
		$subTemplate = $this->getTemplateBetweenLoop('{category_loop_start}', '{category_loop_end}');

		if ($subTemplate)
		{
			$template = array();

			// Replace all sub categories
			foreach ($subCategories as $category)
			{
				$categoryTemplate = $subTemplate['template'];
				$categoryTemplate = $this->replaceCategory($category, $categoryTemplate);
				$template[]       = $categoryTemplate;
			}

			// Return all template after replaced
			return $subTemplate['begin'] . implode(PHP_EOL, $template) . $subTemplate['end'];
		}

		return $this->template;
	}

	/**
	 * @param   object  $category  Category object
	 * @param   int     $width     Width
	 * @param   int     $height    Height
	 *
	 * @return string
	 */
	private function getThumbnail($category, $width, $height)
	{
		$objhelper = redhelper::getInstance();
		$jinput    = JFactory::getApplication()->input;
		$model     = JModelLegacy::getInstance('Category', 'RedshopModel');

		$manufacturerId = $model->getState('manufacturer_id');

		// Default with JPATH_ROOT . '/components/com_redshop/assets/images/'
		$middlepath  = REDSHOP_FRONT_IMAGES_RELPATH . 'category/';
		$title       = " title='" . $category->category_name . "' ";
		$alt         = " alt='" . $category->category_name . "' ";
		$product_img = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
		$linkimage   = $product_img;

		// Try to get category Itemid
		$cItemid = $objhelper->getCategoryItemid($category->category_id);

		if ($cItemid != "")
		{
			$tmpItemid = $cItemid;
		}
		else
		{
			$tmpItemid = $jinput->getInt('Itemid', null);
		}

		// Generate category link
		$link = JRoute::_(
			'index.php?option=' . $jinput->get('option', 'com_redshop') .
			'&view=category&cid=' . $category->category_id .
			'&manufacturer_id=' . $manufacturerId .
			'&layout=detail&Itemid=' . $tmpItemid
		);

		// If full size image exists
		if ($category->category_full_image && file_exists($middlepath . $category->category_full_image))
		{
			$categoryFullImage = $category->category_full_image;

			// Generate thumbnail with watermark ( if configured )
			$product_img       = $objhelper->watermark('category', $category->category_full_image, $width, $height, Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'), '0');
			$linkimage         = $objhelper->watermark('category', $category->category_full_image, '', '', Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'), '0');
		}
		elseif (Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE') && file_exists($middlepath . Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')))
		{
			// Use default image
			$categoryFullImage = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
			$product_img       = $objhelper->watermark('category', Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'), $width, $height, Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'), '0');
			$linkimage         = $objhelper->watermark('category', Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'), '', '', Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'), '0');
		}

		if (Redshop::getConfig()->get('CAT_IS_LIGHTBOX'))
		{
			$categoryThumbnail = "<a class='modal' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $categoryFullImage . "' rel=\"{handler: 'image', size: {}}\" " . $title . ">";
		}
		else
		{
			$categoryThumbnail = "<a href='" . $link . "' " . $title . ">";
		}

		$categoryThumbnail .= "<img src='" . $product_img . "' " . $alt . $title . ">";
		$categoryThumbnail .= "</a>";

		return $categoryThumbnail;
	}
}
