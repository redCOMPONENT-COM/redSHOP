<?php
/**
 * @package     Redshop.Modules
 * @subpackage  plg_redshop_order_clickatell
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;

/**
 * PlgRedshop_OrderClickATell installer class.
 *
 * @package  Redshopb.Plugin
 * @since    2.0
 */
class PlgRedshop_OrderClickATellInstallerScript
{
	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @param   string $type The type of change (install, update or discover_install)
	 *
	 * @return  void
	 */
	public function postflight($type)
	{
		if ($type !== 'install' && $type !== 'discover_install')
		{
			return;
		}

		$this->getClickATellKeyFromOldRedshop();
	}

	/**
	 * Method for get Click-A-Tell API key from redSHOP config.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function getClickATellKeyFromOldRedshop()
	{
		/** @var JTableExtension $extensionTable */
		$extensionTable = JTable::getInstance('Extension');

		$pluginId = $extensionTable->find(
			array(
				'element' => 'clickatell',
				'type'    => 'plugin',
				'folder'  => 'redshop_order'
			)
		);

		$extensionTable->load($pluginId);
		$pluginParams = $extensionTable->get('params');

		jimport('redshop.library');

		// Set the reset_status parameter to 0 and save the updated parameters
		$pluginParams = new Registry($pluginParams);
		$pluginParams->set('username', Redshop::getConfig()->get('CLICKATELL_USERNAME', ''));
		$pluginParams->set('password', Redshop::getConfig()->get('CLICKATELL_PASSWORD', ''));
		$pluginParams->set('apiId', Redshop::getConfig()->get('CLICKATELL_API_ID', ''));

		$orderStatus = array(Redshop::getConfig()->get('CLICKATELL_ORDER_STATUS', ''));

		// Get from template
		$template = RedshopHelperTemplate::getTemplate('clicktell_sms_message');

		if (!empty($template))
		{
			// Just work on first template.
			$template = $template[0];
			$pluginParams->set('payment', explode(',', $template->payment_methods));
			$pluginParams->set('shipping', explode(',', $template->shipping_methods));
			$pluginParams->set('status', array_merge($orderStatus, explode(',', $template->order_status)));
			$pluginParams->set('message', $template->template_desc);
		}

		$extensionTable->set('params', $pluginParams->toString());
		$extensionTable->store();

		// Clean up old template of Click-A-Tell
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_template'))
			->where($db->qn('template_section') . ' = ' . $db->quote('clicktell_sms_message'));
		$db->setQuery($query)->execute();
	}
}
