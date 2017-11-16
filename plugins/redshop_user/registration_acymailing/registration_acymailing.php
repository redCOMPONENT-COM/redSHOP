<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

require_once JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/acyplugins.php';
require_once JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/list.php';

/**
 * Plugins RedITEM Category Fields
 *
 * @since  1.0
 */
class PlgRedshop_UserRegistration_Acymailing extends JPlugin
{
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
	 * autoAcymailingSubscription function
	 *
	 * @param   bool   $isNew  To know that user is new or not
	 * @param   array  $data   data for trigger
	 *
	 * @return boolean
	 */
	public function addNewsLetterSubscription($isNew, $data = array())
	{
		if ($isNew)
		{
			$user = JFactory::getUser();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->qn(array('subid')))
				->from($db->qn('#__acymailing_subscriber'))
				->where($db->qn('userid') . ' = ' . $db->q($user->id));

			$db->setQuery($query);
			$sub = $db->loadObject();

			if (isset($sub->subid))
			{
				$plugin = JPluginHelper::getPlugin('redshop_user', 'registration_acymailing');
				$pluginParams = new JRegistry($plugin->params);

				$list = $pluginParams->get('listschecked');

				if (isset($list) && (JString::trim($list) != ''))
				{
					$query = $db->getQuery(true);
					$query->select($db->qn(array('listid')))
						->from($db->qn('#__acymailing_list'));

					switch ($list)
					{
						case 'None':
							return true;
							break;
						case 'All':
							break;
						default:
							$list = explode(',', $list);
							JArrayHelper::toInteger($list);
							$query->where($db->qn('listid') . ' IN (' . implode(',', $list) . ')');
							break;
					}

					$db->setQuery($query);
					$items = $db->loadObjectList();

					if (count($items))
					{
						foreach ($items as $item)
						{
							$date = JFactory::getDate()->toUnix();
							$query = $db->getQuery(true);
							$query->insert($db->qn('#__acymailing_listsub'))
								->columns($db->qn(array('listid', 'subid', 'subdate', 'status')))
								->values($db->q($item->listid) . ',' . $db->q($sub->subid) . ',' . $date . ',' . $db->q('1'));

							$db->setQuery($query);
							$db->execute();
						}
					}
				}
			}
		}

		return true;
	}
}
