<?php
/**
 * @package     Redshop\Ajax
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
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
	 * @param   string  $key    Property key
	 * @param   mixed   $value  Value
	 *
	 * @return  Response  $this
	 *
	 * @since   2.0.7
	 */
	public function addProperty($key, $value)
	{
		$this->$key = $value;

		return $this;
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
	 *
	 * @return   string
	 *
	 * @since    2.0.7
	 */
	public function respond()
	{
		return json_encode($this);
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
