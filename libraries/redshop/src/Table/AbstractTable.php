<?php
/**
 * @package     Aesir.Core
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use Redshop\Table\Traits\HasAutoEvents;
use Redshop\Table\Traits\HasInstanceName;
use Redshop\Table\Traits\HasInstancePrefix;

/**
 * Base table class.
 *
 * @since  3.2.3
 */
abstract class AbstractTable extends Table implements TableInterface
{
    use HasAutoEvents;
    use HasInstanceName;
    use HasInstancePrefix;

    /**
     * The table name without the prefix. Ex: cursos_courses
     *
     * @var  string
     */
    protected $_tableName = null;

    /**
     * The table key column. Usually: id
     *
     * @var  string
     */
    protected $_tableKey = 'id';

    /**
     * Array with alias for "special" columns such as ordering, hits etc etc
     *
     * @var    array
     */
    protected $_columnAlias = array();

    /**
     * The options.
     *
     * @var  array
     */
    protected $options = array();

    /**
     * Constructor
     *
     * @param   \JDatabaseDriver  $db  A database connector object
     *
     * @throws  \UnexpectedValueException
     */
    public function __construct(&$db)
    {
        // Keep checking _tbl value for standard defined tables
        if (empty($this->_tbl) && !empty($this->_tableName)) {
            // Add the table prefix
            $this->_tbl = '#__' . $this->_tableName;
        }

        $key = $this->_tbl_key;

        if (empty($key) && !empty($this->_tbl_keys)) {
            $key = $this->_tbl_keys;
        }

        // Keep checking _tbl_key for standard defined tables
        if (empty($key) && !empty($this->_tableKey)) {
            $this->_tbl_key = $this->_tableKey;
            $key            = $this->_tbl_key;
        }

        if (empty($this->_tbl) || empty($key)) {
            throw new \UnexpectedValueException(
                sprintf('Missing data to initialize %s table | id: %s', $this->_tbl, $key)
            );
        }

        if ($this->autoEvents) {
            $this->generateEventsConfig();
        }

		parent::__construct($this->_tbl, $key, $db);
    }

    /**
     * Get a table option value.
     *
     * @param   string  $key      The key
     * @param   mixed   $default  The default value
     *
     * @return  mixed             The value or the default value
     */
    public function getOption($key, $default = null)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * Set a table option value.
     *
     * @param   string  $key  The key
     * @param   mixed   $val  The default value
     *
     * @return  self
     */
    public function setOption($key, $val)
    {
        $this->options[$key] = $val;

        return $this;
    }
}
