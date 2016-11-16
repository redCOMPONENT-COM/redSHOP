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
abstract class RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  2.1
	 */
	public $tags = array();

	/**
	 * @var    array
	 *
	 * @since  2.1
	 */
	protected $search = array();

	/**
	 * @var    array
	 *
	 * @since  2.1
	 */
	protected $replace = array();

	/**
	 * @var    string
	 *
	 * @since  2.1
	 */
	protected $template = '';

	/**
	 * @var   array
	 *
	 * @since  2.1
	 */
	protected $data = array ();

	/**
	 * RedshopTagsAbstract constructor.
	 *
	 * @param   string  $template  Template
	 * @param   array   $data      Data
	 *
	 * @since  2.1
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
	 * @since  2.1
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
	 * @since  2.1
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
	 * @since  2.1
	 */
	public function replace()
	{
		$content = str_replace($this->search, $this->replace, $this->template);

		return $content;
	}

	/**
	 * Add replace
	 *
	 * @param   string  $tag    Tag
	 * @param   string  $value  Value
	 *
	 * @return  void
	 *
	 * @since version
	 */
	protected function _addReplace ($tag, $value)
	{
		if (strpos($this->template, $tag) !== false)
		{
			// Make sure this tag is exists before adding replace
			if ($this->isTagExists($tag))
			{
				$this->search [] = $tag;
				$this->replace[] = $value;
			}
		}
	}
}
