<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
JLoader::load('RedshopHelperAdminUpdate');

/**
 * Class RedshopModelUpdate
 *
 * @since  1.4
 */
class RedshopModelUpdate extends JModelLegacy
{
	/**
	 * Check update status
	 *
	 * @return void
	 */
	public function checkUpdateStatus()
	{
		// Check tables engines
		foreach (RedshopUpdate::$tablesRelates as $tableName => $values)
		{
			if (!$this->checkEngine($tableName, $values))
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_UPDATE_SOME_DB_DATA_NOT_OPTIMIZED_PLEASE_UPDATE'), 'warning');

				return;
			}
		}

		// Check indexes
		foreach (RedshopUpdate::$tablesRelates as $tableName => $values)
		{
			if (array_key_exists('index', $values) && count($values['index']) > 0)
			{
				foreach ($values['index'] as $key => $oneIndex)
				{
					if (!$this->checkIndex($tableName, $key))
					{
						JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_UPDATE_SOME_DB_DATA_NOT_OPTIMIZED_PLEASE_UPDATE'), 'warning');

						return;
					}
				}
			}

			if (array_key_exists('unique', $values) && count($values['unique']) > 0)
			{
				foreach ($values['unique'] as $key => $oneIndex)
				{
					if (!$this->checkIndex($tableName, $key))
					{
						JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_UPDATE_SOME_DB_DATA_NOT_OPTIMIZED_PLEASE_UPDATE'), 'warning');

						return;
					}
				}
			}
		}
	}

	/**
	 * Check index
	 *
	 * @param   string  $tableName  Table name
	 * @param   string  $key        Key name
	 *
	 * @return bool
	 */
	public function checkIndex($tableName, $key)
	{
		$db = JFactory::getDbo();
		$db->setQuery('SHOW INDEXES FROM ' . $db->qn($tableName) . ' WHERE Key_name = ' . $db->q($key));

		if (!$db->loadObject())
		{
			return false;
		}

		return true;
	}

	/**
	 * Check engine
	 *
	 * @param   string  $tableName  Table name
	 * @param   array   $values     Values
	 *
	 * @return bool
	 */
	public function checkEngine($tableName, $values)
	{
		if (array_key_exists('engine', $values))
		{
			$db = JFactory::getDbo();
			$db->setQuery('SHOW TABLE STATUS WHERE Name = ' . $db->q($tableName));
			$result = $db->loadObject();

			if ($result && $result->Engine != $values['engine'])
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Update
	 *
	 * @return  array
	 */
	public function update()
	{
		$db = JFactory::getDbo();
		$counter = 0;
		$start = microtime(1);
		$maxTime = 10;
		$goToNextPart = false;
		$queryExecuted = 0;
		$app = JFactory::getApplication();

		try
		{
			$count = count(RedshopUpdate::$tablesRelates);
			$db->transactionStart();

			// Check tables engines
			foreach (RedshopUpdate::$tablesRelates as $tableName => $values)
			{
				$counter++;

				if (!$this->checkEngine($tableName, $values))
				{
					$db->setQuery('ALTER TABLE ' . $db->qn($tableName) . ' ENGINE = ' . $db->q($values['engine']));

					if (!$db->execute())
					{
						throw new Exception($db->getErrorMsg());
					}

					$queryExecuted++;

					if (microtime(1) - $start >= $maxTime)
					{
						$goToNextPart = true;
						break;
					}
				}
			}

			foreach (RedshopUpdate::$tablesRelates as $tableName => $values)
			{
				if (array_key_exists('index', $values) && count($values['index']) > 0)
				{
					$count += count($values['index']);

					if (!$goToNextPart)
					{
						foreach ($values['index'] as $key => $oneIndex)
						{
							if (!$this->checkIndex($tableName, $key))
							{
								$indexFields = implode(',', self::quote((array) $oneIndex, 'qn'));
								$db->setQuery('ALTER TABLE ' . $db->qn($tableName) . ' ADD INDEX ' . $db->qn($key) . ' (' . $indexFields . ')');

								if (!$db->execute())
								{
									throw new Exception($db->getErrorMsg());
								}

								$queryExecuted++;
							}

							if (microtime(1) - $start >= $maxTime)
							{
								$goToNextPart = true;
								break;
							}
						}
					}
				}

				if (array_key_exists('unique', $values) && count($values['unique']) > 0)
				{
					$count += count($values['unique']);

					if (!$goToNextPart)
					{
						foreach ($values['unique'] as $key => $oneIndex)
						{
							if (!$this->checkIndex($tableName, $key))
							{
								$indexFields = implode(',', self::quote((array) $oneIndex, 'qn'));
								$db->setQuery('ALTER TABLE ' . $db->qn($tableName) . ' ADD UNIQUE ' . $db->qn($key) . ' (' . $indexFields . ')');

								if (!$db->execute())
								{
									throw new Exception($db->getErrorMsg());
								}

								$queryExecuted++;
							}

							if (microtime(1) - $start >= $maxTime)
							{
								$goToNextPart = true;
								break;
							}
						}
					}
				}
			}

			$db->transactionCommit();
		}
		catch (Exception $e)
		{
			$db->transactionRollback();
			$app->enqueueMessage($e->getMessage(), 'error');

			return array('success' => false);
		}

		if ($goToNextPart)
		{
			$app->enqueueMessage(JText::sprintf('COM_REDSHOP_UPDATE_GOTO_NEXT_PART', $counter, $count));

			return array('parts' => $count - $counter, 'total' => $count, 'success' => true);
		}
		else
		{
			if ($queryExecuted > 0)
			{
				$app->enqueueMessage(JText::sprintf('COM_REDSHOP_UPDATE_QUERY_EXECUTED', $queryExecuted));
				$app->enqueueMessage(JText::_('COM_REDSHOP_UPDATE_SUCCESS'));
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_UPDATE_THERE_NO_NEED_TO_UPDATE'));
			}

			return array('success' => true);
		}
	}

	/**
	 * Quote an array of values.
	 *
	 * @param   array   $values     The values.
	 * @param   string  $nameQuote  Name quote
	 *
	 * @return  array  The quoted values
	 */
	public static function quote(array $values, $nameQuote = 'q')
	{
		$db = JFactory::getDbo();

		return array_map(
			function ($value) use ($db, $nameQuote) {
				return $db->$nameQuote($value);
			},
			$values
		);
	}
}
