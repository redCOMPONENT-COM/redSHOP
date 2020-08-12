<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Newsletter Subscriptions
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewNewsletter_Subscriptions extends RedshopViewList
{
	/**
	 * Method for render column
	 *
	 * @param array  $config Row config.
	 * @param int    $index  Row index.
	 * @param object $row    Row data.
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function onRenderColumn($config, $index, $row)
	{
		$model = $this->getModel('newsletter_subscriptions');
		$newsletterName = $model::getNewsletterNameById($row->newsletter_id);
		$userName = \RedshopEntityNewsletter_Subscription::getUserFullName($row->user_id);

		switch ($config['dataCol']) {
			case 'user_id':
				$link = JRoute::_(
					'index.php?option=com_redshop&task=newsletter_subscription.edit&id=' . $row->id
				);

				return '<a href="'. $link .'">'. $userName .'</a>';
			case 'newsletter_id':
				return $newsletterName;
			default:
				return parent::/** @scrutinizer ignore-call */ onRenderColumn($config, $index, $row);
		}
	}

	/**
	 * Method for prepare table.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function prepareTable()
	{
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/models/forms/' . $this->getInstanceName() . '.xml';

		if (!JFile::exists($formPath)) {
			return;
		}

		// Load single form xml file
		$form = simplexml_load_file($formPath);

		// Get field set data
		$fields = $form->xpath(
			'(//fieldset[@name="details"]//field | //field[@fieldset="details"])[not(ancestor::field)]'
		);

		if (empty($fields)) {
			return;
		}

		foreach ($fields as $field) {
			// Skip for spacer
			if ($field['type'] == 'spacer' || $field['type'] == 'hidden' || !empty($field['table-hide'])) {
				continue;
			}

			if ($field['name'] == 'ordering') {
				$this->hasOrdering = true;
			}

			if (JText::_((string)$field['label']) == 'Select User') {
				$field['label'] = 'COM_REDSHOP_NEWSLETTER_USERNAME';
			}

			if (JText::_((string)$field['label']) == 'Select Newsletter') {
				$field['label'] = 'COM_REDSHOP_NEWSLETTER';
			}

			$column = array(
				// This column is sortable?
				'sortable'  => isset($field['table-sortable']) ? (bool)$field['table-sortable'] : false,
				// Text for column
				'text'      => JText::_((string)$field['label']),
				// Name of property for get data.
				'dataCol'   => (string)$field['name'],
				// Width of column
				'width'     => isset($field['table-width']) ? (string)$field['table-width'] : 'auto',
				// Enable edit inline?
				'inline'    => isset($field['table-inline']) ? (bool)$field['table-inline'] : false,
				// Display with edit link or not?
				'edit_link' => isset($field['table-edit-link']) ? (bool)$field['table-edit-link'] : false,
				// Type of column
				'type'      => (string)$field['type'],
			);

			if ($field['type'] == 'number' || ($field['type'] == 'redshop.text'
					&& isset($field['filter']) && ($field['filter'] == 'integer' || $field['filter'] == 'float'))) {
				$column['type'] = 'number';
			}

			$this->columns[] = $column;
		}
	}
}
