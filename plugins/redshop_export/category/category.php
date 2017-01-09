<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Category
 *
 * @since  1.0
 */
class PlgRedshop_ExportCategory extends JPlugin
{
	protected $data = array();

	/**
	 * Constructor - note in Joomla 2.5 PHP4.x is no longer supported so we can use this.
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Event run for get available exports.
	 *
	 * @param   array  &$data  Data of available exports.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function getExport(&$data)
	{
		$data[] = JText::_('PLG_REDSHOP_EXPORT_CATEGORY_TITLE');
	}

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onAjaxCategory_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onAjaxCategory_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('c.*')
			->select($db->qn('cx.category_parent_id'))
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('cx.category_child_id'))
			->where($db->qn('cx.category_parent_id') . ' IS NOT NULL')
			->order($db->qn('c.category_id'));

		$this->data = $db->setQuery($query)->loadObjectList();

		return count($this->data);
	}
}
