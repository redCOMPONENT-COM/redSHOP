<?php
/**
 * @package     Redshop
 * @subpackage  Table.Traits
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Table\Traits;

defined('_JEXEC') or die;

/**
 * Tables with instance name.
 *
 * @since  2.0.3
 */
trait HasInstanceName
{
	/**
	 * Name of the instance.
	 *
	 * @var    string
	 */
	protected $instanceName;

	/**
	 * Gets the name of the latest extending class.
	 * For a class named ContentTableArticles will return Articles
	 *
	 * @return  string
	 */
	public function getInstanceName()
	{
		if (null === $this->instanceName)
		{
			$this->instanceName = strtolower(str_replace('Table', '', strstr(get_class($this), 'Table')));
		}

		return $this->instanceName;
	}
}
