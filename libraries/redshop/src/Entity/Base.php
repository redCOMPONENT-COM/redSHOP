<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity;

use Joomla\Utilities\ArrayHelper;
use Redshop\BaseObject;

defined('_JEXEC') or die;

/**
 * Base Entity.
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class Base extends BaseObject
{
    /**
     * @const  integer
     * @since  __DEPLOY_VERSION__
     */
    const STATE_ENABLED = 1;

    /**
     * @const  integer
     * @since  1.0
     */
    const STATE_DISABLED = 0;

    /**
     * Cached instances
     *
     * @var  array
     * @since  __DEPLOY_VERSION__
     */
    protected static $instances = array();

    /**
     * ACL prefix used to check permissions
     *
     * @var    string
     * @since  1.0
     */
    protected $aclPrefix = "core";

    /**
     * Identifier of the loaded instance
     *
     * @var  mixed
     * @since  __DEPLOY_VERSION__
     */
    protected $id = null;

    /**
     * Cached table.
     *
     * @var  \JTable
     * @since  __DEPLOY_VERSION__
     */
    protected $table;

    /**
     * Option of the component containing the tables. Example: com_content
     *
     * @var  string
     * @since  __DEPLOY_VERSION__
     */
    protected $component;

    /**
     * Translations for items that support them
     *
     * @var  array
     * @since  __DEPLOY_VERSION__
     */
    protected $translations = array();

    /**
     * Table primary key for load item.
     *
     * @var  string
     * @since  __DEPLOY_VERSION__
     */
    protected $tableKey = null;

    /**
     * Constructor
     *
     * @param   mixed  $id  Identifier of the active item
     * @since  __DEPLOY_VERSION__
     */
    public function __construct($id = null)
    {
        if ($id) {
            $this->id = $id;
        }

        if (null === $this->tableKey) {
            $this->tableKey = false !== $this->getTable() ? $this->getTable()->getKeyName() : 'id';
        }
    }

    /**
     * Get the associated table
     *
     * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
     *
     * @return  \RedshopTable
     * @since   __DEPLOY_VERSION__
     */
    public function getTable($name = null)
    {
        if (null === $name) {
            $class = get_class($this);
            $name  = strstr($class, 'Entity');
        }

        $name = str_replace('Entity', '', $name);

        return \RedshopTable::getAdminInstance($name, array(), $this->getComponent());
    }

    /**
     * Get the component that contains the tables
     *
     * @return  string
     * @since __DEPLOY_VERSION__
     */
    protected function getComponent()
    {
        if (null === $this->component) {
            $this->component = $this->getComponentFromPrefix();
        }

        return $this->component;
    }

    /**
     * Get the component from the prefix. Ex.: ContentEntityArticle will return com_content
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    protected function getComponentFromPrefix()
    {
        return 'com_' . $this->getPrefix();
    }

    /**
     * Get the prefix of this entity.
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getPrefix()
    {
        $class = get_class($this);

        return strtolower(strstr($class, 'Entity', true));
    }

    /**
     * Create and return a cached instance by a different field of the table (UID)
     *
     * @param   string  $fieldName   Field to use
     * @param   string  $fieldValue  Key value
     *
     * @return  $this
     * @since   __DEPLOY_VERSION__
     */
    public static function getInstanceByField($fieldName, $fieldValue)
    {
        $instance = static::getInstance();

        return $instance->loadItem($fieldName, $fieldValue);
    }

    /**
     * Create and return a cached instance
     *
     * @param   integer  $id  Identifier of the active item
     *
     * @return  $this
     * @since   __DEPLOY_VERSION__
     */
    public static function getInstance($id = null)
    {
        if (null === $id) {
            return new static;
        }

        $class = get_called_class();

        if (empty(static::$instances[$class][$id])) {
            static::$instances[$class][$id] = new static($id);
        }

        return static::$instances[$class][$id];
    }

    /**
     * Load a cached instance and ensure that the item is loaded
     *
     * @param   integer  $id  Identifier of the active item
     *
     * @return  self
     * @since   __DEPLOY_VERSION__
     */
    public static function load($id = null)
    {
        $instance = static::getInstance($id);

        if (!$instance->isLoaded()) {
            $instance->loadItem();
        }

        return $instance;
    }

    /**
     * Check if item has been loaded
     *
     * @return  boolean
     * @since   __DEPLOY_VERSION__
     */
    public function isLoaded()
    {
        return ($this->hasId() && $this->item !== null);
    }

    /**
     * Bind an object/array to the entity
     *
     * @param   mixed  $item  Array/Object containing the item fields
     *
     * @return  $this
     * @since   __DEPLOY_VERSION__
     */
    public function bind($item)
    {
        // Accept basic array binding
        $item = is_array($item) ? (object) $item: $item;

        $this->item = $item;

        if (property_exists($item, 'id')) {
            $this->id = $item->id;

            $class = get_called_class();

            // Ensure that we cache the item
            if (!isset(static::$instances[$class][$this->id])) {
                static::$instances[$class][$this->id] = $this;
            }
        }

        return $this;
    }

    /**
     * Check if current user can create an item
     *
     * @return  boolean
     *
     * @since   __DEPLOY_VERSION__
     */
    public function canCreate()
    {
        if ($this->canDo($this->getAclPrefix() . '.create')) {
            return true;
        }

        if ($this->canDo($this->getAclPrefix() . '.create.own')) {
            return $this->isOwner();
        }

        return false;
    }

    /**
     * Check if current user has permission to perform an action
     *
     * @param   string  $action  The action. Example: core.create
     *
     * @return  boolean
     * @since   __DEPLOY_VERSION__
     */
    public function canDo($action)
    {
        $user = \Joomla\CMS\Factory::getUser();

        return $user->authorise($action, $this->getAssetName());
    }

    /**
     * Get the identifier of the project asset
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    protected function getAssetName()
    {
        if ($this->hasId()) {
            return $this->getComponent() . '.' . $this->getInstanceName() . '.' . $this->id;
        }

        // Use the global permissions
        return $this->getComponent();
    }

    /**
     * Check if we have an identifier loaded
     *
     * @return  boolean
     * @since   __DEPLOY_VERSION__
     */
    public function hasId()
    {
        $id = (int)$this->id;

        return !empty($id);
    }

    /**
     * Get the name of the current entity type
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getInstanceName()
    {
        $class = get_class($this);

        $name = strstr($class, 'Entity');
        $name = str_replace('Entity', '', $name);

        return strtolower($name);
    }

    /**
     * Get the ACL prefix applied to this class
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getAclPrefix()
    {
        return $this->aclPrefix;
    }

    /**
     * Check if current member is owner
     *
     * @return  boolean
     *
     * @since   __DEPLOY_VERSION__
     */
    public function isOwner()
    {
        if (!$this->hasId()) {
            return false;
        }

        $user = \Joomla\CMS\Factory::getUser();

        if ($user->get('guest')) {
            return false;
        }

        $item = $this->getItem();

        if (!$item) {
            return false;
        }

        return ($item->created_by == $user->get('id'));
    }

    /**
     * Get item from the database
     *
     * @return  mixed  Object / null
     * @since   __DEPLOY_VERSION__
     */
    public function getItem()
    {
        if (empty($this->item)) {
            $this->loadItem();
        }

        return $this->item;
    }

    /**
     * Default loading is trying to use the associated table
     *
     * @param   string  $key       Field name used as key
     * @param   string  $keyValue  Value used if it's not the $this->id property of the instance
     *
     * @since   __DEPLOY_VERSION__
     */
    public function loadItem($key = null, $keyValue = null)
    {
        $key = null === $key ? $this->tableKey : $key;

        if ($key === $this->tableKey && !$this->hasId()) {
            return $this;
        }

        $table = $this->getTable();

        if (false !== $table && $table->load(array($key => ($key === $this->tableKey ? $this->id : $keyValue)))) {
            $this->loadFromTable($table);
        }

        return $this;
    }

    /**
     * Load the item already loaded in a table
     *
     * @param   \RedshopTable|\JTable|bool  $table  Table with the item loaded
     *
     * @return  self
     * @since   __DEPLOY_VERSION__
     */
    public function loadFromTable($table)
    {
        $key = $table->getKeyName();

        if (!empty($table->{$key})) {
            // Get the data from the table
            if (method_exists($table, 'getTableProperties')) {
                // Redshopb method to get only public properties
                $data = $table->getTableProperties();
            } else {
                // Fallback for every other JTable (not redshopb tables)
                $data = $table->getProperties(true);
            }

            // Item is always an object
            $this->item = ArrayHelper::toObject($data);

            $this->id    = $table->{$key};
            $this->table = clone $table;

            $class = get_called_class();

            // Ensure that we cache the item
            if (!isset(static::$instances[$class][$this->id]) || !static::$instances[$class][$this->id]->isLoaded()) {
                static::$instances[$class][$this->id] = $this;
            }
        }

        return $this;
    }

    /**
     * Check if current user can delete an item
     *
     * @return  boolean
     * @since   __DEPLOY_VERSION__
     */
    public function canDelete()
    {
        if (!$this->hasId()) {
            return false;
        }

        if ($this->canDo($this->getAclPrefix() . '.delete')) {
            return true;
        }

        if ($this->canDo($this->getAclPrefix() . '.delete.own')) {
            return $this->isOwner();
        }

        return false;
    }

    /**
     * Check if current user can edit this item
     *
     * @return  boolean
     * @since   __DEPLOY_VERSION__
     */
    public function canEdit()
    {
        if (!$this->hasId()) {
            return false;
        }

        // User has global edit permissions
        if ($this->canDo($this->getAclPrefix() . '.edit')) {
            return true;
        }

        // User has global edit permissions
        if ($this->canDo($this->getAclPrefix() . '.edit.own')) {
            return $this->isOwner();
        }

        return false;
    }

    /**
     * Check if user can view this item.
     *
     * @return  boolean
     *
     * @since   __DEPLOY_VERSION__
     */
    public function canView()
    {
        $item = $this->getItem();

        if (!$item) {
            return false;
        }

        return ((int)$item->state === static::STATE_ENABLED || $this->isOwner());
    }

    /**
     * Get an item property
     *
     * @param   string  $property  Property to get
     * @param   mixed   $default   Default value to assign if property === null | property === ''
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function get($property, $default = null)
    {
        $item = $this->getItem();

        if (!empty($item) && property_exists($item, $property)) {
            return ($item->$property !== null && $item->$property !== '') ? $item->$property : $default;
        }

        return $default;
    }

    /**
     * Set an item property
     *
     * @param   string  $property  Property to get
     * @param   mixed   $data      Data for set to property
     *
     * @return  self
     * @since   __DEPLOY_VERSION__
     */
    public function set($property, $data = null)
    {
        if (null === $this->item) {
            $this->loadItem();
        }

        $this->item->{$property} = $data;

        return $this;
    }

    /**
     * Get the item add link with a return link to the current page.
     *
     * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
     * @param   boolean  $routed  Process URL with JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getAddLinkWithReturn($itemId = 'inherit', $routed = true, $xhtml = true)
    {
        $url = $this->getAddLink($itemId, false, false) . '&return=' . base64_encode(\JUri::getInstance()->toString());

        return $this->formatUrl($url, $routed, $xhtml);
    }

    /**
     * Get the item add link
     *
     * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
     * @param   boolean  $routed  Process URL with JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getAddLink($itemId = 'inherit', $routed = true, $xhtml = true)
    {
        $url = $this->getBaseUrl() . '&task=' . $this->getInstanceName() . '.add' . $this->getLinkItemIdString($itemId);

        return $this->formatUrl($url, $routed, $xhtml);
    }

    /**
     * Gets the base URL for tasks/views.
     *
     * Example: index.php?option=com_redshopb&view=shop
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    protected function getBaseUrl()
    {
        return 'index.php?option=' . $this->getComponent() . '&view=' . $this->getInstanceName();
    }

    /**
     * Generate the Itemid string part for URLs
     *
     * @param   mixed  $itemId  inherit or desired itemId. Use 0 to not inherit active itemId
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    protected function getLinkItemIdString($itemId = 'inherit')
    {
        return ($itemId !== 'inherit') ? '&Itemid=' . (int)$itemId : null;
    }

    /**
     * Format a link
     *
     * @param   string   $url     Url to format
     * @param   boolean  $routed  Process Url through JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    protected function formatUrl($url, $routed = true, $xhtml = true)
    {
        if (!$url) {
            return null;
        }

        if (!$routed) {
            return $url;
        }

        return \Redshop\IO\Route::_($url, $xhtml);
    }

    /**
     * Get an entity date field formatted
     *
     * @param   string   $itemProperty     Item property containing the date
     * @param   string   $format           Desired date format
     * @param   boolean  $translateFormat  Translate the format for multilanguage purposes
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getDate($itemProperty, $format = 'DATE_FORMAT_LC1', $translateFormat = true)
    {
        $item = $this->getItem();

        if (!$item || !property_exists($item, $itemProperty)) {
            return null;
        }

        if ($format && $translateFormat) {
            $format = \JText::_($format);
        }

        return \JHtml::_('date', $item->{$itemProperty}, $format);
    }

    /**
     * Get the item delete link with a return link to the current page.
     *
     * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
     * @param   boolean  $routed  Process URL with JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getDeleteLinkWithReturn($itemId = 'inherit', $routed = true, $xhtml = true)
    {
        if (!$this->hasId()) {
            return null;
        }

        $url = $this->getDeleteLink($itemId, false, false) . '&return=' . base64_encode(
                \JUri::getInstance()->toString()
            );

        return $this->formatUrl($url, $routed, $xhtml);
    }

    /**
     * Get the item delete link
     *
     * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
     * @param   boolean  $routed  Process URL with JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getDeleteLink($itemId = 'inherit', $routed = true, $xhtml = true)
    {
        if (!$this->hasId()) {
            return null;
        }

        $urlToken = '&' . \JSession::getFormToken() . '=1';

        $url = $this->getBaseUrl() . '&task=' . $this->getInstanceName()
            . '.delete&id=' . $this->getSlug() . $urlToken . $this->getLinkItemIdString($itemId);

        return $this->formatUrl($url, $routed, $xhtml);
    }

    /**
     * Generate the item slug for URLs
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    protected function getSlug()
    {
        $item = $this->getItem();

        if (!$item) {
            return $this->hasId() ? $this->id : null;
        }

        return !empty($item->alias) ? $this->id . '-' . $item->alias : $this->id;
    }

    /**
     * Get the item edit link with a return link to the current page.
     *
     * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
     * @param   boolean  $routed  Process URL with JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getEditLinkWithReturn($itemId = 'inherit', $routed = true, $xhtml = true)
    {
        if (!$this->hasId()) {
            return null;
        }

        $url = $this->getEditLink($itemId, false, false) . '&return=' . base64_encode(JUri::getInstance()->toString());

        return $this->formatUrl($url, $routed, $xhtml);
    }

    /**
     * Get the item edit link
     *
     * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
     * @param   boolean  $routed  Process URL with JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getEditLink($itemId = 'inherit', $routed = true, $xhtml = true)
    {
        if (!$this->hasId()) {
            return null;
        }

        $url = $this->getBaseUrl() . '&task=' . $this->getInstanceName()
            . '.edit&id=' . $this->getSlug() . $this->getLinkItemIdString($itemId);

        return $this->formatUrl($url, $routed, $xhtml);
    }

    /**
     * Get the item link
     *
     * @param   mixed    $itemId  Specify a custom itemId if needed. Default: joomla way to use active itemid
     * @param   boolean  $routed  Process URL with JRoute?
     * @param   boolean  $xhtml   Replace & by &amp; for XML compliance.
     *
     * @return  string
     * @since   __DEPLOY_VERSION__
     */
    public function getLink($itemId = 'inherit', $routed = true, $xhtml = true)
    {
        if (!$this->hasId()) {
            return null;
        }

        $url = $this->getBaseUrl() . '&id=' . $this->getSlug() . $this->getLinkItemIdString($itemId);

        return $this->formatUrl($url, $routed, $xhtml);
    }

    /**
     * Basic instance check: has id + loadable item
     *
     * @return  boolean
     * @since   __DEPLOY_VERSION__
     */
    public function isValid()
    {
        if (!$this->hasId()) {
            return false;
        }

        $item = $this->getItem();

        return !empty($item);
    }

    /**
     * @param   array  $data  Data
     *
     * @return  self
     * @since   __DEPLOY_VERSION__
     */
    public function loadItemByArray($data)
    {
        $table = $this->getTable();

        if (false === $table) {
            return $this;
        }

        if ($table->load($data)) {
            $this->loadFromTable($table);
        }

        return $this;
    }

    /**
     * Try to directly save the entity using the associated table
     *
     * @param   mixed  $item  Object / Array to save. Null = try to store current item
     *
     * @return  boolean|integer  The item id
     *
     * @since   __DEPLOY_VERSION__
     */
    public function save($item = null)
    {
        if (!$this->processBeforeSaving($item)) {
            return false;
        }

        if (null === $item) {
            $item = $this->getItem();
        }

        if (!$item) {
            \JLog::add("Nothing to save", \JLog::ERROR, 'entity');

            return false;
        }

        try {
            $table = $this->getTable();
        } catch (\Exception $exception) {
            \JLog::add("Table for instance " . $this->getInstanceName() . " could not be loaded", \JLog::ERROR, 'entity');

            return false;
        }

        if (!$table->save((array)$item)) {
            \JLog::add($table->getError(), \JLog::ERROR, 'entity');

            return false;
        }

        // Force entity reload / save to cache
        static::clearInstance($this->id);
        $this->loadFromTable($table);

        $this->processAfterSaving($table);

        return $table->{$table->getKeyName()};
    }

    /**
     * Process $item data before saving.
     *
     * @param   mixed  $item  Array / Object of data.
     *
     * @return  boolean       Return false will break save process
     * @since   __DEPLOY_VERSION__
     */
    public function processBeforeSaving(&$item)
    {
        return true;
    }

    /**
     * Remove an instance from cache
     *
     * @param   integer  $id  Identifier of the active item
     *
     * @return  void
     * @since   __DEPLOY_VERSION__
     */
    public static function clearInstance($id = null)
    {
        $class = get_called_class();

        unset(static::$instances[$class][$id]);
    }

    /**
     * Process data after saving.
     *
     * @param   \JTable  $table  JTable instance data.
     *
     * @return  boolean
     * @since   __DEPLOY_VERSION__
     */
    public function processAfterSaving(&$table)
    {
        return true;
    }

    /**
     * Method for reset this static for load new data.
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    public function reset()
    {
        if (!$this->isLoaded()) {
            return $this;
        }

        $class = get_called_class();
        $id    = $this->getId();

        unset(static::$instances[$class][$id]);

        return static::getInstance($id);
    }

    /**
     * Get the id
     *
     * @return  integer | null
     * @since   __DEPLOY_VERSION__
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Local proxy for JFactory::getDbo()
     *
     * @return  \JDatabaseDriver
     * @since   __DEPLOY_VERSION__
     */
    protected function getDbo()
    {
        return \Joomla\CMS\Factory::getDbo();
    }
}
