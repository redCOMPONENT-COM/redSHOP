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
	);

	/**
	 * @var    array
	 * @since  2.1
	 */
	public $tags_alias = array(
		'{category_short_desc}' => '{category_short_description}',
		'{categoryshortdesc}' => '{category_short_description}',
		'{categorydesc}' => '{category_description}',
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
	 * @param   object  $category  Category object
	 * @param   string  $template  Template to replace
	 *
	 * @return  string
	 *
	 * @since   2.0.0.6
	 */
	private function replaceCategory($category, $template)
	{
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
	 * @param   array  $subCategories  Sub categories array
	 *
	 * @return  string
	 *
	 * @since   2.0.0.6
	 */
	private function replaceSubCategories ($subCategories)
	{
		$subTemplate = $this->getTemplateBetweenLoop('{category_loop_start}', '{category_loop_end}');

		if ($subTemplate)
		{
			$template = array ();

			// Replace all sub categories
			foreach ($subCategories as $category)
			{
				$categoryTemplate = $subTemplate['template'];
				$categoryTemplate = $this->replaceCategory($category, $categoryTemplate);
				$template[] = $categoryTemplate;
			}

			// Return all template after replaced
			return $subTemplate['begin'] . implode(PHP_EOL, $template) . $subTemplate['end'];
		}

		return $this->template;
	}
}
