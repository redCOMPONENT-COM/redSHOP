<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Mail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableMail extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_mail';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'mail_id';
}
