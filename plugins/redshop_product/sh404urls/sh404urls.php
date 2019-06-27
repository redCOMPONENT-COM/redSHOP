<?php
/**
 * @package     redSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Plugin Redshop_ProductSh404urls
 *
 * @since __DEPLOY_VERSION__
 */
class PlgRedshop_ProductSh404urls extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param    object $subject The object to observe
	 * @param    array  $config  An array that holds the plugin configuration
	 *
	 * @since    __DEPLOY_VERSION__
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @param   $pids  array  products id
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onAfterProductDelete($pids)
	{
		if (empty($pids))
		{
			return false;
		}

		$db      = JFactory::getDbo();
		$query   = $db->getQuery(true);
		$conds   = array();
		$results = $db->setQuery('SHOW TABLES')->loadColumn();
		$table   = $db->getPrefix() . 'sh404sef_urls';

		if (empty($results) || !in_array($table, $results))
		{
			return false;
		}

		foreach ($pids as $pid)
		{
			$conds[] = $db->qn('newurl') . ' LIKE ' . $db->q('%pid=' . (int) $pid . '%');
		}

		$query->clear()
			->delete($db->qn($table))
			->where($db->qn('newurl') . ' LIKE ' . $db->q('%view=product%'))
			->where($db->qn('newurl') . ' LIKE ' . $db->q('%option=com_redshop%'))
			->where(implode(' OR ', $conds));

		return $db->setQuery($query)->execute();
	}
}
