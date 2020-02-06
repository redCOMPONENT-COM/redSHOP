<?php
/**
 * @package     Redshop.Plugin
 * @subpackage  Finder.redSHOP
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';
JLoader::import('redshop.library');

/**
 * Smart Search adapter for redSHOP Products.
 *
 * @since  2.5
 */
class PlgFinderRedShop extends FinderIndexerAdapter
{
	/**
	 * The plugin identifier.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $context = 'Redshop';

	/**
	 * The extension name.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $extension = 'com_redshop';

	/**
	 * The sublayout to use when rendering the results.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $layout = 'product';

	/**
	 * The type of content that the adapter indexes.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $type_title = 'Redshop - Product';

	/**
	 * The table name.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $table = '#__redshop_product';

	/**
	 * The field the published state is stored in.
	 *
	 * @var    string
	 * @since  2.5
	 */
	protected $state_field = 'published';

	/**
	 * Constructor
	 *
	 * @param   object $subject The object to observe
	 * @param   array  $config  An array that holds the plugin configuration
	 *
	 * @since 2.0
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Method to remove the link information for items that have been deleted.
	 *
	 * @param   string  $context  The context of the action being performed.
	 * @param   JTable  $table    A JTable object containing the record to be deleted
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	public function onFinderAfterDelete($context, $table)
	{
		if ($context == 'com_redshop.product')
		{
			$id = $table->id;
		}
		elseif ($context == 'com_finder.index')
		{
			$id = $table->link_id;
		}
		else
		{
			return true;
		}

		// Remove item from the index.
		return $this->remove($id);
	}

	/**
	 * Method to update the link information for items that have been changed
	 * from outside the edit screen. This is fired when the item is published,
	 * unpublished, archived, or unarchived from the list view.
	 *
	 * @param   string  $context The context for the products passed to the plugin.
	 * @param   array   $pks     An array of primary key ids of the products that has changed state.
	 * @param   integer $value   The value of the state that the products has been changed to.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 */
	public function onFinderChangeState($context, $pks, $value)
	{
		// We only want to handle products here
		if ($context == 'com_redshop.product')
		{
			$this->itemStateChange($pks, $value);
		}

		// Handle when the plugin is disabled
		if ($context == 'com_plugins.plugin' && $value === 0)
		{
			$this->pluginDisable($pks);
		}
	}

	/**
	 * Method to index an item. The item must be a FinderIndexerResult object.
	 *
	 * @param   FinderIndexerResult $item   The item to index as an FinderIndexerResult object.
	 * @param   string              $format The item format.  Not used.
	 *
	 * @return  void
	 *
	 * @since   2.5
	 * @throws  Exception on database error.
	 */
	protected function index(FinderIndexerResult $item, $format = 'html')
	{
		// Check if the extension is enabled.
		if (JComponentHelper::isEnabled($this->extension) == false)
		{
			return;
		}

		// Build the necessary route and path information.
		require_once JPATH_SITE . '/components/com_redshop/helpers/route.php';

		$alias       = JFilterOutput::stringURLSafe($item->slug);
		$item->url   = $this->getURL($item->id, $this->extension, $this->layout);
		$item->route = RedshopHelperRouter::getProductRoute($alias, $item->catid, $item->language, $item->manu_id);
		$item->path  = FinderIndexerHelper::getContentPath($item->route);

		// Add the meta-author.
		$item->metaauthor = $item->author;

		// Handle the link to the meta-data.
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'link');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metaauthor');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'author');

		// Add the taxonomy data.
		$item->addTaxonomy('Type', $this->type_title);
		$item->addTaxonomy('Language', $item->language);

		// Translate the state. Products should only be published if the parent category is published.
		$item->state = $this->translateState($item->state);

		// Get content extras.
		FinderIndexerHelper::getContentExtras($item);

		// Index the item.
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$this->indexer->index($item);
		}
		else
		{
			FinderIndexer::getInstance()->index($item);
		}
	}

	/**
	 * Method to setup the indexer to be run.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 */
	protected function setup()
	{
		// Load com_content route helper as it is the fallback for routing in the indexer in this instance.
		require_once JPATH_SITE . '/components/com_redshop/helpers/route.php';

		return true;
	}

	/**
	 * Method to get the SQL query used to retrieve the list of content items.
	 *
	 * @param   mixed $query A JDatabaseQuery object or null.
	 *
	 * @return  JDatabaseQuery  A database object.
	 *
	 * @since   2.5
	 */
	protected function getListQuery($query = null)
	{
		$db = JFactory::getDbo();

		// Check if we can use the supplied SQL query.
		$query = is_a($query, 'JDatabaseQuery') ? $query : $this->db->getQuery(true);

		$caseWhenItemAlias  = ' CASE WHEN ';
		$caseWhenItemAlias .= $query->charLength('p.product_name', '!=', '0');
		$caseWhenItemAlias .= ' THEN ';

		$productId          = $query->castAsChar('p.product_id');
		$caseWhenItemAlias .= $query->concatenate(array($productId, 'p.product_name'), ':');
		$caseWhenItemAlias .= ' ELSE ';
		$caseWhenItemAlias .= $productId . ' END as slug';

		$query->select($db->qn('p.product_id', 'id'))
			->select($db->qn('p.product_name', 'title'))
			->select($db->qn('p.product_price', 'product_price'))
			->select($db->qn('p.discount_price', 'discount_price'))
			->select($db->qn('p.product_s_desc', 'body'))
			->select($db->qn('p.product_desc', 'summary'))
			->select($db->qn('p.manufacturer_id', 'manu_id'))
			->select($db->qn('p.published', 'state'))
			->select($db->quote('0') . ' AS ' . $db->qn('publish_start_date'))
			->select($db->qn('update_date'))
			->select($db->qn('pc.category_id', 'catid'))
			->select($db->quote('1') . ' AS ' . $db->qn('access'))
			->select($db->quote('0') . ' AS ' . $db->qn('publish_end_date'))
			->select($db->qn('c.published', 'cat_state'))
			->select($caseWhenItemAlias)
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc')
				. ' ON ' . $db->qn('pc.product_id') . ' = ' . $db->qn('p.product_id')
			)
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pc.category_id'))
			->where($db->qn('p.published') . ' = 1');

		return $query;
	}

	/**
	 * Method to get the URL for the item. The URL is how we look up the link
	 * in the Finder index.
	 *
	 * @param   integer $id        The id of the item.
	 * @param   string  $extension The extension the item is in.
	 * @param   string  $view      The view for the URL.
	 *
	 * @return string The URL of the item.
	 *
	 * @since 2.0
	 */
	protected function getURL($id, $extension, $view)
	{
		return 'index.php?option=' . $extension . '&view=' . $view . '&pid=' . $id;
	}

	/**
	 * Method to get a SQL query to load the published and access states for
	 * a news feed and category.
	 *
	 * @return JDatabaseQuery A database object.
	 *
	 * @since 2.0
	 */
	protected function getStateQuery()
	{
		$sql = $this->db->getQuery(true);
		$sql->select($this->db->quoteName('p.product_id'));
		$sql->select($this->db->quoteName('p.' . $this->state_field, 'state'));
		$sql->from($this->db->quoteName($this->table, 'p'));

		return $sql;
	}
}
