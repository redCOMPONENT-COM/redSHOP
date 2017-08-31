<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Ajax;

/**
 * Ajax respond class
 *
 * @package     Redshop\Ajax
 *
 * @since       2.0.7
 */
class Response extends \stdClass
{
	/**
	 * @var    boolean
	 *
	 * @since  2.0.7
	 */
	public $status = false;

	/**
	 * @var    string
	 *
	 * @since  2.0.7
	 */
	public $msg = null;

	/**
	 * @var    array
	 *
	 * @since  2.0.7
	 */
	public $data = null;

	/**
	 * @param   string  $property  Property key
	 * @param   mixed   $value     Value
	 *
	 * @return  Response  $this
	 *
	 * @since   2.0.7
	 */
	public function set($property, $value)
	{
		$this->$property = $value;

		return $this;
	}

	/**
	 * Returns a property of the object or the default value if the property is not set.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $default   The default value.
	 *
	 * @return  mixed
	 *
	 * @since   2.0.7
	 */
	public function get($property, $default = null)
	{
		return (property_exists($this, $property)) ? $this->{$property} : $default;
	}

	/**
	 * @param   mixed  $data  Data
	 *
	 * @return  $this
	 *
	 * @since   2.0.7
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @param   mixed  $data  Data
	 *
	 * @return  Response  $this
	 *
	 * @since   2.0.7
	 */
	public function addData($data)
	{
		$this->data[] = $data;

		return $this;
	}

	/**
	 * @param   string  $html   HTML content
	 *
	 * @return  Response  $this
	 *
	 * @since   2.0.7
	 */
	public function addHtml($html)
	{
		$data = new \stdClass;
		$data->dataContent = $html;
		$data->dataType = 'html';

		return $this->addData($data);
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public function getJson()
	{
		return json_encode($this, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}

	/**
	 *
	 * @return   void
	 *
	 * @since    2.0.7
	 */
	public function respond()
	{
		header('Content-Type: application/json');

		echo $this->getJson();

		\JFactory::getApplication()->close();
	}

	/**
	 *
	 * @return  Response
	 *
	 * @since   2.0.7
	 */
	public function success()
	{
		$this->status = true;

		return $this;
	}
}
