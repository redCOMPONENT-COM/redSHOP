<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Newsletter Subscriber
 *
 * @since  1.0
 */
class PlgRedshop_ImportNewsletter_Subscriber extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'subscription_id';

	/**
	 * @var string
	 */
	protected $nameKey = 'email';

	protected $newsletterId;

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxNewsletter_Subscriber_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();
		$newsletter = $this->getNewsletters();
		$configs    = array();

		$configs[] = '<div class="form-group">
			<label class="col-md-2 control-label">' . JText::_('PLG_REDSHOP_IMPORT_NEWSLETTER_SUBSCRIBER_LABLE') . '</label>
			<div class="col-md-10">'
			. JHTML::_('select.genericlist', $newsletter, 'newsletter_id', 'class="inputbox" size="1" ', 'value', 'text', '') . '</div>
		</div>';

		return implode('', $configs);

	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxNewsletter_Subscriber_Import()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$input              = JFactory::getApplication()->input;
		$this->encoding     = $input->getString('encoding', 'UTF-8');
		$this->separator    = $input->getString('separator', ',');
		$this->folder       = $input->getCmd('folder', '');
		$this->newsletterId = $input->getString('newsletter_id', '');

		return json_encode($this->importing());
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable|boolean
	 *
	 * @since   1.0.0
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('newslettersubscr_detail', 'Table');
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable $table Header array
	 * @param   array   $data  Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		if (!$data['newsletter_id'])
		{
			$data['newsletter_id'] = $this->newsletterId;
		}

		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			if (!$table->load($data[$this->primaryKey]))
			{
				return false;
			}
		}

		if (!$table->bind($data) || !$table->check() || !$table->store())
		{
			return false;
		}

		return true;
	}

	/**
	 * Get newsletters.
	 *
	 * @return  mixed
	 *
	 * @since   1.0.0
	 */
	public function getNewsletters()
	{
		$db    = $this->db;
		$query = $db->getQuery(true)
			->select(array('newsletter_id AS value', 'name AS text'))
			->from($db->qn('#__redshop_newsletter'))
			->where($db->qn('published') . ' = 1');

		return $db->setQuery($query)->loadObjectList();
	}
}
