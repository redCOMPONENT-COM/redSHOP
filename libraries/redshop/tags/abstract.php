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
 * @since  2.0.0.5
 */
abstract class RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since   2.0.0.5
	 */
	public $tags = array();

	/**
	 * @var    array
	 *
	 * @since  2.0.0.6
	 */
	public $tagAlias = array();

	/**
	 * @var    array
	 *
	 * @since  2.0.0.5
	 */
	public $search = array();

	/**
	 * @var    array
	 *
	 * @since  2.0.0.5
	 */
	public $replace = array();

	/**
	 * @var    array
	 *
	 * @since  2.1.5
	 */
	public $replacements = array();

	/**
	 * @var    string
	 *
	 * @since  2.0.0.5
	 */
	protected $template = '';

	/**
	 * @var    array
	 *
	 * @since  2.0.0.5
	 */
	protected $data = array();

    /**
     * @var    array
     *
     * @since   __DEPLOY_VERSION__
     */
    public $optionLayout;

	/**
	 * RedshopTagsAbstract constructor.
	 *
	 * @param   string  $template  Template
	 * @param   array   $data      Data
	 *
	 * @since   2.0.0.5
	 */
	public function __construct($template, $data)
	{
		$this->template = $template;
		$this->data     = $data;
		$this->init();
	}

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   2.0.0.5
	 */
	abstract public function init();

	/**
	 * Check if tag / tagAlias is registered or not
	 *
	 * @param   string  $tag  Tag
	 *
	 * @return  boolean
	 *
	 * @since   2.1
	 */
	public function isTagRegistered($tag)
	{
		if (array_key_exists($tag, $this->tagAlias))
		{
			$tag = $this->tagAlias[$tag];
		}

		return in_array($tag, $this->tags);
	}

	/**
	 * Check if this tag exists in main template
	 *
	 * @param   string  $tag  Tag
	 *
	 * @return  boolean
	 *
	 * @since   2.1
	 */
	public function isTagExists($tag)
	{
		if (strpos($this->template, $tag) === false)
		{
			return false;
		}

		return true;
	}

	/**
	 * Get available tags
	 *
	 * @return  array
	 *
	 * @since   2.0.0.5
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   2.0.0.5
	 */
	public function replace()
	{
		$dispatcher = $this->getDispatcher();

		// Trigger event and cancel replace if event return false
		if ($dispatcher->trigger('onBeforeReplaceTags', array(&$this->search, &$this->replace, &$this->template)) === false)
		{
			return $this->template;
		}

		$this->template = str_replace($this->search, $this->replace, $this->template);

		return $this->template;
	}

	/**
	 * Add custom tag replace
	 *
	 * @param   string  $tag    Tag
	 * @param   string  $value  Value
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.5
	 */
	protected function addReplace($tag, $value)
	{
		$dispatcher = $this->getDispatcher();

		// Trigger event and cancel addReplace if event return false
		if ($dispatcher->trigger('onBeforeAddReplaceTag', array(&$tag, &$value) === false))
		{
			return false;
		}

		// Make sure this tag is exists before adding replace
		if ($this->isTagExists($tag) === false || $this->isTagRegistered($tag) === false)
		{
			return true;
		}

		$this->search[]  = $tag;
		$this->replace[] = $value;

		// Check alias tag
		if ($key = array_search($tag, $this->tagAlias) !== false)
		{
			$this->search[]  = $key;
			$this->replace[] = $value;
		}

		return true;
	}

	/**
	 * Get template content between loop tags
	 *
	 * @param   string  $beginTag  Begin tag
	 * @param   string  $endTag    End tag
	 * @param   string  $template  Template
	 *
	 * @return  mixed
	 *
	 * @since   2.0.0.6
	 */
	protected function getTemplateBetweenLoop($beginTag, $endTag, $template = '')
	{
		if ($this->isTagExists($beginTag) && $this->isTagExists($endTag))
		{
			$templateStartData = explode($beginTag, $this->template);

			if (!empty($template))
			{
				$templateStartData = explode($beginTag, $template);
			}

			$templateStart     = $templateStartData [0] ?? '';

			$templateEndData = explode($endTag, $templateStartData [1] ?? '');
			$templateEnd     = $templateEndData[1] ?? '';

			$templateMain = $templateEndData[0] ?? '';

			return array(
				'begin'    => $templateStart,
				'template' => $templateMain,
				'end'      => $templateEnd
			);
		}

		return false;
	}

	/**
	 *
	 * @return  JEventDispatcher
	 *
	 * @since  2.0.6
	 */
	protected function getDispatcher($group = 'redshop')
	{
		JPluginHelper::importPlugin($group);

		return RedshopHelperUtility::getDispatcher();
	}

	/**
	 * Method exclusion tags
	 *
	 * @param   string $tag tag
	 *
	 * @return  boolean
	 * @since  2.0.6
	 */
	protected function excludeTags($tag)
	{
		if (!empty($this->data['excludedTags']) && in_array($tag, $this->data['excludedTags']))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method help to do str_replace.
	 *
	 * @param   array  $replacements array of list tags and html replacements
	 * @param   string $template     template before replace tags.
	 *
	 * @return  string $template
	 * @since  2.1.5
	 */
	public function strReplace($replacements, $template)
	{
		$search = array_keys($replacements);
		$replace = array_values($replacements);
		$template = str_replace($search, $replace, $template);

		return $template;
	}

    /**
     * Get width height
     *
     * @param   string   $template
     * @param   string   $prefix
     * @param   string   $redConfigHeight
     * @param   string   $redConfigWidth
     *
     * @return  array
     *
     * @since __DEPLOY_VERSION__
     */
    public function getWidthHeight($template, $prefix, $redConfigHeight, $redConfigWidth)
    {
        if (strpos($template, '{' . $prefix . '_3}') !== false) {
            $imageTag = '{' . $prefix . '_3}';
            $height   = Redshop::getConfig()->get($redConfigHeight . '_3');
            $width    = Redshop::getConfig()->get($redConfigWidth . '_3');
        } elseif (strpos($template, '{' . $prefix . '_2}') !== false) {
            $imageTag = '{' . $prefix . '_2}';
            $height   = Redshop::getConfig()->get($redConfigHeight . '_2');
            $width    = Redshop::getConfig()->get($redConfigWidth . '_2');
        } elseif (strpos($template, '{' . $prefix . '_1}') !== false) {
            $imageTag = '{' . $prefix . '_1}';
            $height   = Redshop::getConfig()->get($redConfigHeight);
            $width    = Redshop::getConfig()->get($redConfigWidth);
        } else {
            $imageTag = '{' . $prefix . '}';
            $height   = Redshop::getConfig()->get($redConfigHeight);
            $width    = Redshop::getConfig()->get($redConfigWidth);
        }

        return ['height' => $height, 'imageTag' => $imageTag, 'width' => $width];
    }
}
