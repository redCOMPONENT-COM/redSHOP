<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class Tablemail_detail extends JTable
{
	public $mail_id = null;

	public $mail_name = null;

	public $mail_subject = null;

	public $mail_section = null;

	public $mail_order_status = null;

	public $mail_body = null;

	public $mail_bcc = null;

	public $published = null;

	public function __construct(& $db)
	{
		$this->_table_prefix = '#__redshop_';

		parent::__construct($this->_table_prefix . 'mail', 'mail_id', $db);
	}

	public function bind($array, $ignore = '')
	{
		if (array_key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
