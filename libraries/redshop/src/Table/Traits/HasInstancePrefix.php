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
trait HasInstancePrefix
{
	/**
	 * Instance prefix used for autoloading + events.
	 *
	 * @var    string
	 */
	protected $instancePrefix;

	/**
	 * Get the class prefix
	 *
	 * @return  string
	 */
	public function getInstancePrefix()
	{
		if (null === $this->instancePrefix)
		{
			$this->instancePrefix = strtolower(strstr(get_class($this), 'Table', true));
		}

		return $this->instancePrefix;
	}
}
