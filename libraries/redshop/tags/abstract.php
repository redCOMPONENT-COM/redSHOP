<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * Check if tag is registered or not
	 *
	 * @param   string  $tag  Tag
	 *
	 * @return  boolean
	 *
	 * @since   2.1
	 */
	public function isTagRegistered($tag)
	{
		if (in_array($tag, $this->tagAlias))
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
		JPluginHelper::importPlugin('redshop');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		// Trigger event and cancel replace if event return false
		if ($dispatcher->trigger('onBeforeReplaceTags', array(&$this->search, &$this->replace, &$this->template)) === false)
		{
			return $this->template;
		}

		$this->template = str_replace($this->search, $this->replace, $this->template);

		return $this->template;
	}

	/**
	 * Add replace
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
		JPluginHelper::importPlugin('redshop');
		$dispatcher = RedshopHelperUtility::getDispatcher();

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
	 * Get template between loop tags
	 *
	 * @param   string  $beginTag  Begin tag
	 * @param   string  $endTag    End tag
	 *
	 * @return  mixed
	 *
	 * @since   2.0.0.6
	 */
	protected function getTemplateBetweenLoop($beginTag, $endTag)
	{
		if ($this->isTagExists($beginTag) && $this->isTagExists($endTag))
		{
			$templateStartData = explode($beginTag, $this->template);
			$templateStart     = $templateStartData [0];

			$templateEndData = explode($endTag, $templateStartData [1]);
			$templateEnd     = $templateEndData[1];

			$templateMain = $templateEndData[0];

			return array(
				'begin'    => $templateStart,
				'template' => $templateMain,
				'end'      => $templateEnd
			);
		}

		return false;
	}
}
