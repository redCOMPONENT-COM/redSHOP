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
	 * @since   2.0.0.5
	 */
	public $search = array();

	/**
	 * @var    array
	 *
	 * @since   2.0.0.5
	 */
	public $replace = array();

	/**
	 * @var    string
	 *
	 * @since   2.0.0.5
	 */
	protected $template = '';

	/**
	 * @var   array
	 *
	 * @since   2.0.0.5
	 */
	protected $data = array ();

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
		$this->data = $data;
		$this->init();
	}

	/**
	 * Init
	 *
	 * @return mixed
	 *
	 * @since   2.0.0.5
	 */
	abstract public function init();

	/**
	 * Check if tag is exists or not
	 *
	 * @param   string  $tag  Tag
	 *
	 * @return  bool
	 *
	 * @since   2.1
	 */
	public function isTagExists($tag)
	{
		return in_array($tag, $this->tags);
	}

	/**
	 * Get available tags
	 *
	 * @return array
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
		$dispatcher = JEventDispatcher::getInstance();

		// Trigger event and cancel replace if event return false
		if ($dispatcher->trigger('onBeforeReplaceTags', array (&$this->search, &$this->replace, &$this->template)) === false)
		{
			return $this->template;
		}

		return str_replace($this->search, $this->replace, $this->template);
	}

	/**
	 * Add replace
	 *
	 * @param   string  $tag    Tag
	 * @param   string  $value  Value
	 *
	 * @return  bool
	 *
	 * @since   2.0.0.5
	 */
	protected function addReplace($tag, $value)
	{
		JPluginHelper::importPlugin('redshop');
		$dispatcher = JEventDispatcher::getInstance();

		// Trigger event and cancel addReplace if event return false
		if ($dispatcher->trigger('onBeforeAddReplaceTag', array(&$tag, &$value) === false))
		{
			return false;
		}

		// Make sure this tag is exists before adding replace
		if (strpos($this->template, $tag) === false || !$this->isTagExists($tag))
		{
			return true;
		}

		$this->search[]  = $tag;
		$this->replace[] = $value;
	}
}
