<?php
/**
 * @package     Aesir
 * @subpackage  Cest
 * @copyright   Copyright (C) 2016 - 2018 Aesir. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Codeception\Module;

use Codeception\Configuration;

/**
 * Aesir DB Helper
 *
 * @package   Aesir
 * @since     1.0.0
 */
class DbHelper extends \Codeception\Module
{
	/**
	 * @var   \PDO
	 * @since 5.6.0
	 */
	private static $dbh;

	/**
	 * Get the DB handler
	 *
	 * @return  \PDO
	 * @since   5.6.0
	 * @throws  \Exception
	 */
	private function getDbh()
	{
		if (!is_null(static::$dbh))
		{
			return static::$dbh;
		}

		/** @var Db $dbModule */
		$dbModule    = $this->getModule('Db');
		static::$dbh = $dbModule->dbh;

		return static::$dbh;
	}

	/**
	 * Quotes and optionally escapes a string to database requirements for use in database queries.
	 *
	 * @param   mixed    $text    A string or an array of strings to quote.
	 * @param   boolean  $escape  True (default) to escape the string, false to leave it unchanged.
	 *
	 * @return  string|array  The quoted input.
	 *
	 * @since   5.6.0
	 */
	public function quoteQueryString($text, $escape = true)
	{
		if (is_array($text))
		{
			foreach ($text as $k => $v)
			{
				$text[$k] = $this->quoteQueryString($v, $escape);
			}

			return $text;
		}
		else
		{
			return '\'' . $text . '\'';
		}
	}

	/**
	 * This function replaces a string identifier <var>$prefix</var> with the table prefix used in Joomla
	 *
	 * @param   string  $sql     The SQL statement to prepare.
	 * @param   string  $prefix  The common table prefix.
	 *
	 * @return  string  The processed SQL statement.
	 *
	 * @since   5.6.0
	 * @throws  \Exception
	 */
	private function replacePrefixQuery($sql, $prefix = '#__')
	{
		$config   = Configuration::suiteSettings('acceptance', Configuration::config());
		$dbPrefix = isset($config['modules']['config']['JoomlaBrowser']['database prefix'])
			? $config['modules']['config']['JoomlaBrowser']['database prefix']
			: '';
		$startPos = 0;
		$literal  = '';

		$sql = trim($sql);
		$n   = strlen($sql);

		while ($startPos < $n)
		{
			$ip = strpos($sql, $prefix, $startPos);

			if ($ip === false)
			{
				break;
			}

			$j = strpos($sql, "'", $startPos);
			$k = strpos($sql, '"', $startPos);

			if (($k !== false) && (($k < $j) || ($j === false)))
			{
				$quoteChar = '"';
				$j         = $k;
			}
			else
			{
				$quoteChar = "'";
			}

			if ($j === false)
			{
				$j = $n;
			}

			$literal .= str_replace($prefix, $dbPrefix, substr($sql, $startPos, $j - $startPos));
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n)
			{
				break;
			}

			// Quote comes first, find end of quote
			while (true)
			{
				$k       = strpos($sql, $quoteChar, $j);
				$escaped = false;

				if ($k === false)
				{
					break;
				}

				$l = $k - 1;

				while ($l >= 0 && $sql{$l} == '\\')
				{
					$l--;
					$escaped = !$escaped;
				}

				if ($escaped)
				{
					$j = $k + 1;
					continue;
				}

				break;
			}

			if ($k === false)
			{
				// Error in the query - no end quote; ignore it
				break;
			}

			$literal .= substr($sql, $startPos, $k - $startPos + 1);
			$startPos = $k + 1;
		}

		if ($startPos < $n)
		{
			$literal .= substr($sql, $startPos, $n - $startPos);
		}

		return $literal;
	}

	/**
	 * Update entries from $table set $data where $criteria conditions
	 * Use: $I->updateFromDatabase('users', ['startdate' => '2014-12-12'], ['id' => '111111']);
	 *
	 * @param   string  $table     Table name
	 * @param   array   $data      Changes for update
	 * @param   array   $criteria  Conditions. See seeInDatabase() method.
	 *
	 * @return  boolean
	 * @since   5.6.0
	 * @throws  \Exception
	 */
	public function executeUpdateTable($table, $data, $criteria = [])
	{
		$dbh = $this->getDbh();

		if (empty($data))
		{
			throw new \Exception('No data provided for update query');
		}

		$query   = (empty($criteria) ? 'UPDATE %s SET %s' : 'UPDATE %s SET %s WHERE %s');
		$dataset = [];

		foreach ($data as $c => $d)
		{
			$dataset[] = "$c = ?";
		}

		$dataset = implode(' , ', $dataset);
		$query   = sprintf($query, $table, $dataset, implode(' AND ', $criteria));
		$this->debugSection('Update Table Query', $query);

		$sth = $dbh->prepare($this->replacePrefixQuery($query));

		return $sth->execute(array_values($data));
	}

	/**
	 * Delete entries from $table where $criteria conditions
	 * Use: $I->deleteFromDatabase('users', ['id' => '111111', 'banned' => 'yes']);
	 *
	 * @param   string   $table              Table name
	 * @param   array    $criteria           Conditions. See seeInDatabase() method.
	 * @param   boolean  $ignoreForeignKeys  Adds an instruction to ignore foreign keys while processing
	 *
	 * @return  boolean
	 * @since   5.6.0
	 * @throws  \Exception
	 */
	public function executeDeleteTable($table, $criteria, $ignoreForeignKeys = false)
	{
		$dbh = $this->getDbh();

		$query  = (empty($criteria) ? 'DELETE FROM %s' : 'DELETE FROM %s WHERE %s');
		$return = true;

		$query = sprintf($query, $table, implode(' AND ', $criteria));
		$this->debugSection('Delete Table Query', $query);

		if ($ignoreForeignKeys)
		{
			$sth    = $dbh->prepare('SET FOREIGN_KEY_CHECKS = 0');
			$return = $sth->execute();
		}

		if (!$return)
		{
			return false;
		}

		$sth    = $dbh->prepare($this->replacePrefixQuery($query));
		$return = $sth->execute();

		if (!$return)
		{
			return false;
		}

		if ($ignoreForeignKeys)
		{
			$sth    = $dbh->prepare('SET FOREIGN_KEY_CHECKS = 0');
			$return = $sth->execute();
		}

		return $return;
	}

	/**
	 * Drop a table from the database
	 *
	 * @param   string  $table  Table name
	 *
	 * @return  boolean
	 * @since   5.6.0
	 * @throws  \Exception
	 */
	public function executeDropTable($table)
	{
		$dbh = $this->getDbh();

		$query = 'DROP TABLE IF EXISTS %s';
		$query = sprintf($query, $table);
		$this->debugSection('Delete Table Query', $query);

		$sth = $dbh->prepare($this->replacePrefixQuery($query));

		return $sth->execute();

	}

	/**
	 * Executes a select query and returns all rows as objects
	 *
	 * @param   string  $query  Query
	 *
	 * @return  array|false
	 * @since   5.6.0
	 * @throws  \Exception
	 */
	public function loadObjectsQuerySelect($query)
	{
		$dbh = $this->getDbh();

		$this->debugSection('Select Query (all objects)', $query);
		$sth = $dbh->prepare($this->replacePrefixQuery($query));
		$sth->execute();

		return $sth->fetchAll(\PDO::FETCH_CLASS);
	}

	/**
	 * Executes a select query and returns the specified column as an array
	 *
	 * @param   string   $query   Query
	 * @param   integer  $column  Number of column
	 *
	 * @return  array|false
	 * @since   5.6.0
	 * @throws  \Exception
	 */
	public function loadColumnQuerySelect($query, $column = 0)
	{
		$dbh = $this->getDbh();

		$this->debugSection('Select Query (single column)', $query);
		$sth = $dbh->prepare($this->replacePrefixQuery($query));
		$sth->execute();

		return $sth->fetchAll(\PDO::FETCH_COLUMN, $column);
	}
}
