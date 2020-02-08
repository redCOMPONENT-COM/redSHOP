<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Twig
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Twig\Traits;

defined('_JEXEC') || die;

use Joomla\Registry\Registry;

/**
 * For classes having params.
 *
 * @since  1.1.0
 */
trait HasParams
{
	/**
	 * Class parameters.
	 *
	 * @var  Registry
	 */
	protected $params;

	/**
	 * Get the value of a parameter.
	 *
	 * @param   string  $name          Name of the parameter to get
	 * @param   mixed   $defaultValue  Default value if parameter is null
	 *
	 * @return  mixed
	 */
	public function getParam($name, $defaultValue = null)
	{
		return $this->params->get($name, $defaultValue);
	}

	/**
	 * Get active parameters.
	 *
	 * @return  Registry
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Set the value of a parameter.
	 *
	 * @param   string  $name   Name of the parameter to set.
	 * @param   mixed   $value  Value to assign.
	 *
	 * @return  self
	 */
	public function setParam($name, $value)
	{
		$this->params->set($name, $value);

		return $this;
	}

	/**
	 * Set active parameters.
	 *
	 * @param   array|Registry  $params  Parameters to set
	 *
	 * @return  self
	 */
	public function setParams($params)
	{
		$this->params = $params instanceof Registry ? $params : new Registry($params);

		return $this;
	}
}
