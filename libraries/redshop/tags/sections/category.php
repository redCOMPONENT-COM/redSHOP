<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsCategory extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $tags = array(
		'{name}',
		'{category_short_description}',
		'{category_description}',
		'{category_thumb_image}',
		'{category_thumb_image_1}',
		'{category_thumb_image_2}',
		'{category_thumb_image_3}',
	);

	/**
	 * List of tag alias
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	public $tagAlias = array(
		'{category_short_desc}'        => '{category_short_description}',
		'{categoryshortdesc}'          => '{category_short_description}',
		'{categorydesc}'               => '{category_description}',
		'{category_name}'              => '{name}',
		'{category_short_description}' => '{short_description}',
		'{category_description}'       => '{description}',
	);

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function init()
	{
		// Apply general init here
	}

	/**
	 * Override parent replace with own category replacing
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
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
	 * @param   bool   $isSubCat is Sub category
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceCategory($category, $template, $isSubCat = false)
	{
		$replacements = [];
		$manufacturerId = (!empty($this->data['manufacturerId'])) ? $this->data['manufacturerId'] : '';
		$catItemId      = RedshopHelperRouter::getCategoryItemid($category->id);

		$link = JRoute::_(
			'index.php?option=com_redshop' .
			'&view=category&cid=' . $category->id .
			'&manufacturer_id=' . $manufacturerId .
			'&layout=detail&Itemid=' . $catItemId
		);

		$title = ' title="' . $category->name . '" ';

		// Specific cases
		if ($this->isTagExists('{category_thumb_image}') && $this->isTagRegistered('{category_thumb_image}') && isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH'), Redshop::getConfig()->get('THUMB_HEIGHT'), 'full');
			$replacements['{category_thumb_image}'] = $categoryImage;
		}

        if ($this->isTagExists('{category_back_thumb_image}') && isset($category->category_back_full_image))
        {
            $categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH'), Redshop::getConfig()->get('THUMB_HEIGHT'), 'back');
            $replacements['{category_back_thumb_image}'] = $categoryImage;
        }

		if ($this->isTagExists('{category_thumb_image_1}') && $this->isTagRegistered('{category_thumb_image_1}')
			&& isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH'), Redshop::getConfig()->get('THUMB_HEIGHT'), 'full');
			$replacements['{category_thumb_image_1}'] = $categoryImage;
		}

		if ($this->isTagExists('{category_thumb_image_2}') && $this->isTagRegistered('{category_thumb_image_2}')
			&& isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH_2'), Redshop::getConfig()->get('THUMB_HEIGHT_2'), 'full');
			$replacements['{category_thumb_image_2}'] = $categoryImage;
		}

		if ($this->isTagExists('{category_thumb_image_3}') && $this->isTagRegistered('{category_thumb_image_3}')
			&& isset($category->category_full_image))
		{
			$categoryImage = $this->getThumbnail($category, Redshop::getConfig()->get('THUMB_WIDTH_3'), Redshop::getConfig()->get('THUMB_HEIGHT_3'), 'full');
			$replacements['{category_thumb_image_3}'] = $categoryImage;
		}

		if ($this->isTagExists('{category_name}') && $this->isTagRegistered('{category_name}') && isset($category->name))
		{
			$categoryName = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link' => $link,
					'attr' => $title,
					'content' => $category->name
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$replacements['{category_name}'] = $categoryName;
		}

		if ($this->isTagExists('{category_readmore}') && $this->excludeTags('{category_readmore}'))
		{
			$categoryReadMore = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link' => $link,
					'attr' => $title,
					'content' => JText::_('COM_REDSHOP_READ_MORE')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$replacements['{category_readmore}'] = $categoryReadMore;
		}

		if ($this->isTagExists('{category_total_product}') && $this->excludeTags('{category_total_product}'))
		{
			$totalPrd                                     = \RedshopHelperProduct::getProductCategory($category->id);
			$replacements['{category_total_product}']     = count($totalPrd);
			$replacements['{category_total_product_lbl}'] = JText::_('COM_REDSHOP_TOTAL_PRODUCT');
		}

		$template = $this->strReplace($replacements, $template);
		$this->replaceCategoryProperties($template, $category);

		if ($isSubCat == true)
		{
			RedshopHelperProduct::replaceProductSubCategory($template, $category);
		}

		RedshopHelperUtility::getDispatcher()->trigger('onReplaceCategory', array(&$template, &$category));

		return $template;
	}

	/**
	 * @param   string $template Template
	 * @param   object $category Category object
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceCategoryProperties(&$template, $category)
	{
		$replacements = [];

		// Replace all registered tag if category object have it
		foreach ($this->tags as $tag)
		{
			if ($this->excludeTags($tag))
			{
				$tag = str_replace('{', '', $tag);
				$tag = str_replace('}', '', $tag);

				// Make this this tag also have object property to use
				if (property_exists($category, $tag))
				{
					$replacements['{' . $tag . '}'] = $category->{$tag};
				}
			}
		}

		// Also replace with alias
		foreach ($this->tagAlias as $alias => $tag)
		{
			if ($this->excludeTags($alias) && $this->excludeTags($tag))
			{
				$tag              = str_replace('{', '', $tag);
				$tag              = str_replace('}', '', $tag);
				$tagWithoutPrefix = str_replace('category_', '', $tag);

				// Make this this tag also have object property to use
				if (property_exists($category, $tag))
				{
					$replacements[$alias] = $category->{$tag};
				}
				else
				{
					$replacements[$alias] = $category->{$tagWithoutPrefix};
				}
			}
		}

		$template = $this->strReplace($replacements, $template);
	}

	/**
	 * Replace sub categories tags
	 *
	 * @param   array  $subCategories  Sub categories array
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
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
				$lastElement      = end($subCategories);
				$categoryTemplate = $subTemplate['template'];
				$categoryTemplate = $this->replaceCategory($category, $categoryTemplate, true);
				$categoryTemplate = $this->replaceSubCategoriesLevel2($category, $categoryTemplate);

				if (!empty($this->data['excludedTags']))
				{
					$template[] = ($category->id == $lastElement->id) ? $categoryTemplate : $categoryTemplate . "{explode_product}";
				}
				else
				{
					$template[] = $categoryTemplate;
				}
			}

			$templateTmp = implode(PHP_EOL, $template);

			if (!empty($this->data['excludedTags']))
			{
				$templateTmp = "{category_loop_start}" . $templateTmp . "{category_loop_end}";
			}

			// Return all template after replaced
			return $subTemplate['begin'] . $templateTmp . $subTemplate['end'];
		}

		return $this->template;
	}

	/**
	 * Replace sub categories level 2
	 *
	 * @param   object  $category          Category
	 * @param   string  $categoryTemplate  Template
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceSubCategoriesLevel2($category, $categoryTemplate)
	{
		if (strstr($categoryTemplate, "{subcategory_loop_start}") && strstr($categoryTemplate, "{subcategory_loop_end}"))
		{
			$template            = [];
			$templateSubCategory = $this->getTemplateBetweenLoop('{subcategory_loop_start}', '{subcategory_loop_end}', $categoryTemplate);
			$subCategories       = RedshopHelperCategory::getCategoryListArray($category->id);

			if (count($subCategories) > 0)
			{
				foreach ($subCategories as $row)
				{
					$replacements = [];

					if ($row->parent_id != $category->id)
					{
						continue;
					}

					$dataAdd = $templateSubCategory['template'];

					if (strstr($dataAdd, '{subcategory_id}'))
					{
						$replacements['{subcategory_id}'] = $row->id;
					}

					if (strstr($dataAdd, '{subcategory_name}'))
					{
						$replacements['{subcategory_name}'] = $row->name;
					}

					if (strstr($dataAdd, '{subcategory_link}'))
					{
						$link  = 'index.php?option=com_redshop' .
							'&view=category&cid=' . $row->id .
							'&layout=detail&Itemid=' . $this->data['itemId'];
						$link .= isset($this->data['manufacturerId']) ? '&manufacturer_id=' . $this->data['manufacturerId'] : '';
						$replacements['{subcategory_link}'] = $link;
					}

					$dataAdd    = $this->strReplace($replacements, $dataAdd);
					$template[] = $dataAdd;
				}
			}

			$categoryTemplate = $templateSubCategory['begin'] . implode(PHP_EOL, $template) . $templateSubCategory['end'];
		}

		return $categoryTemplate;
	}

	/**
	 * Method for get category thumbnail
	 *
	 * @param   object  $category Category object
	 * @param   integer $width    Width
	 * @param   integer $height   Height
	 *
	 * @return  string
	 * @throws  Exception
	 */
	private function getThumbnail($category, $width, $height, $scope = '')
	{
		$input          = JFactory::getApplication()->input;
		$model          = JModelLegacy::getInstance('Category', 'RedshopModel');
		$manufacturerId = $model->getState('manufacturer_id');

		// Default with JPATH_ROOT . '/components/com_redshop/assets/images/'
		$middlePath        = REDSHOP_FRONT_IMAGES_RELPATH . 'category/';
		$title             = ' title="' . $category->name . '" ';
		$alt               = $category->name;
		$productImg        = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
		$categoryFullImage = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";

		// Try to get category Itemid
		$categoryItemId = (int) RedshopHelperRouter::getCategoryItemid($category->id);
		$mainItemId     = !$categoryItemId ? $input->getInt('Itemid', null) : $categoryItemId;

		// Generate category link
		$link = JRoute::_(
			'index.php?option=' . $input->get('option', 'com_redshop') .
			'&view=category&cid=' . $category->id .
			'&manufacturer_id=' . $manufacturerId .
			'&layout=detail&Itemid=' . $mainItemId
		);

		$medias = RedshopEntityCategory::getInstance($category->id)->getMedia();

		/** @var RedshopEntityMediaImage $fullImage */
		$fullImage = null;

		foreach ($medias->getAll() as $media)
		{
			/** @var RedshopEntityMedia $media */
			if ($media->get('scope') == 'full' && $scope == 'full')
			{
				$fullImage = RedshopEntityMediaImage::getInstance($media->getId());

				break;
			}

			if ($media->get('scope') == 'back' && $scope == 'back') {
                $backImage = RedshopEntityMediaImage::getInstance($media->getId());

                break;
            }
		}

		if (isset($backImage) && $backImage !== null) {
            $categoryBackImage = $backImage->getAbsImagePath();

            // Generate thumb with watermark if needed.
            if (Redshop::getConfig()->getBool('WATERMARK_CATEGORY_THUMB_IMAGE'))
            {
                $productImg = RedshopHelperMedia::watermark(
                    'category',
                    $category->category_back_full_image,
                    $width,
                    $height,
                    Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE')
                );
            }
            else
            {
                $productImg = $backImage->generateThumb($width, $height);
                $productImg = $productImg['abs'];
            }
        } elseif (Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')
            && JFile::exists($middlePath . Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')))
        {
            // Use default image
            $categoryBackImage = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
            $productImg        = RedshopHelperMedia::watermark(
                'category',
                Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'),
                $width,
                $height,
                Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE')
            );
        }

		if ($fullImage !== null)
		{
			$categoryFullImage = $fullImage->getAbsImagePath();

			// Generate thumb with watermark if needed.
			if (Redshop::getConfig()->getBool('WATERMARK_CATEGORY_THUMB_IMAGE'))
			{
				$productImg = RedshopHelperMedia::watermark(
					'category',
					$category->category_full_image,
					$width,
					$height,
					Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE')
				);
			}
			else
			{
                $productImg = $fullImage->generateThumb($width, $height);
				$productImg = $productImg['abs'];
			}
		}
		elseif (Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')
			&& JFile::exists($middlePath . Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')))
		{
			// Use default image
			$categoryFullImage = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');
			$productImg        = RedshopHelperMedia::watermark(
				'category',
				Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'),
				$width,
				$height,
				Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE')
			);
		}

		if (Redshop::getConfig()->get('CAT_IS_LIGHTBOX')) {
		    if ($backImage) {
                $categoryThumbnail = RedshopLayoutHelper::render(
                    'tags.common.img',
                    array(
                        'class' => 'modal',
                        'link' => $categoryBackImage,
                        'linkAttr' => 'rel="{handler: \'image\', size: {}}" ' . $title,
                        'src' => $productImg,
                        'alt' => $alt,
                        'imgAttr' => $title
                    ),
                    '',
                    RedshopLayoutHelper::$layoutOption
                );
            } else {
                $categoryThumbnail = RedshopLayoutHelper::render(
                    'tags.common.img_link',
                    array(
                        'class' => 'modal',
                        'link' => $categoryFullImage,
                        'linkAttr' => 'rel="{handler: \'image\', size: {}}" ' . $title,
                        'src' => $productImg,
                        'alt' => $alt,
                        'imgAttr' => $title
                    ),
                    '',
                    RedshopLayoutHelper::$layoutOption
                );
            }
		} else  {
            $categoryThumbnail = RedshopLayoutHelper::render(
                'tags.common.img_link',
                array(
                    'link' => $link,
                    'linkAttr' => $title,
                    'src' => $productImg,
                    'alt' => $alt,
                    'imgAttr' => $title
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            if (isset($backImage)) {
                $categoryThumbnail = RedshopLayoutHelper::render(
                    'tags.common.img',
                    array(
                        'linkAttr' => $title,
                        'src' => $productImg,
                        'alt' => $alt,
                        'imgAttr' => $title
                    ),
                    '',
                    RedshopLayoutHelper::$layoutOption
                );
            }
		}

		return $categoryThumbnail;
	}
}
