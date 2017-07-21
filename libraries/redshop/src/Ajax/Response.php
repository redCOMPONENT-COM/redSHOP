<?php
/**
 * @package     Redshop\Ajax
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Redshop\Ajax;

class Response extends \stdClass
{
	/**
	 * @var    bool
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
	 * @param   string $key
	 * @param   mixed  $value
	 *
	 * @return  \Response  $this
	 *
	 * @since   2.0.7
	 */
	public function addProperty($key, $value)
	{
		$this->$key = $value;

		return $this;
	}

	public function addData($data)
	{
		$this->data[] = $data;

		return $this;
	}

	public function respond()
	{
		return json_encode($this);
	}

	public function success()
	{
		$this->status = true;

		return $this;
	}
}