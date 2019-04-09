<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * PlgSystemRedSHOP class.
 *
 * @extends JPlugin
 *
 * @since   2.0.1
 */
class PlgSystemRedSHOP extends JPlugin
{
	/**
	 * Auto load language
	 *
	 * @var  string
	 */
	protected $autoloadLanguage = true;

	/**
	 * onAfterDispatch function.
	 *
	 * @return void
	 */
	public function onAfterDispatch()
	{
		if (!JFactory::getApplication()->isSite())
		{
			return;
		}

		JLoader::import('redshop.library');

		RedshopHelperJs::init();
	}

	/**
	 * onBeforeCompileHead function
	 *
	 * @return  void
	 */
	public function onBeforeCompileHead()
	{
		if (class_exists('RedshopHelperConfig'))
		{
			RedshopHelperConfig::scriptDeclaration();
		}

		if (JFactory::getApplication()->input->get('option') != 'com_redshop')
		{
			return;
		}

		$doc = new RedshopHelperDocument;
		$doc->cleanHeader();
	}

	/**
	 * onBeforeRender function.
	 *
	 * @return void
	 */
	public function onAfterInitialise()
	{
		// Set product currency
		$session       = JFactory::getSession();
		$newCurrencyId = JFactory::getApplication()->input->getInt('product_currency', 0);

		if ($newCurrencyId)
		{
			$session->set('product_currency', $newCurrencyId);
		}
	}

	/**
	 * Change the state in core_content if the state in a table is changed
	 *
	 * @param   string   $context  The context for the content passed to the plugin.
	 * @param   array    $pks      A list of primary key ids of the content that has changed state.
	 * @param   integer  $value    The value of the state that the content has been changed to.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onContentChangeState($context, $pks, $value)
	{
		if ($context != 'com_plugins.plugin')
		{
			return false;
		}

		$this->checkDisableExtrafield($pks, $value);

		return true;
	}

	/**
	 * On Saving extensions logging method
	 * Method is called when an extension is being saved
	 *
	 * @param   string   $context  The extension
	 * @param   JTable   $table    DataBase Table object
	 * @param   boolean  $isNew    If the extension is new or not
	 * @param   array    $data     array data extension
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onExtensionAfterSave($context, $table, $isNew, $data)
	{
		if ($context != 'com_plugins.plugin')
		{
			return false;
		}

		$this->checkDisableExtrafield($data['extension_id'], $data['enabled']);

		return true;
	}

	/**
	 * Method check disable extrafield
	 *
	 * @param   integer   $pks      extension id
	 * @param   boolean   $status   status extension
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function checkDisableExtrafield($pks, $status)
	{
		if (is_array($pks))
		{
			$pks = implode(',', $pks);
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('element'))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('extension_id') . ' IN (' . $pks . ')');
		$db->setQuery($query);

		$plugins = $db->loadColumn();

		foreach ($plugins as $plugin)
		{
			if ($plugin == 'giaohangnhanh')
			{
				if ($status == false)
				{
					$fields = array(
						$db->qn('published') . ' = ' . $db->q(0)
					);
				}
				else
				{
					$fields = array(
						$db->qn('published') . ' = ' . $db->q(1)
					);
				}

				$conditions = array(
					$db->qn('name') . ' = ' . $db->q('rs_ghn_city') . ' OR ' .
					$db->qn('name') . ' = ' . $db->q('rs_ghn_district') . ' OR ' .
					$db->qn('name') . ' = ' . $db->q('rs_ghn_billing_city') . ' OR ' .
					$db->qn('name') . ' = ' . $db->q('rs_ghn_billing_district')
				);

				$query->clear();
				$query->update($db->quoteName('#__redshop_fields'))->set($fields)->where($conditions);
				$db->setQuery($query)->execute();
			}
		}
	}
}
