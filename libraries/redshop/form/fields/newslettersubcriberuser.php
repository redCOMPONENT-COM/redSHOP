<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Renders a Newsletter Subscription Uesr by Fields
 *
 * @package  RedSHOP
 * @since    1.5
 */
class JFormFieldNewslettersubcriberuser extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'newslettersubcriberuser';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$selected  = array();

		if (!empty($this->value)) {
			$values = !$this->multiple || !is_array($this->value) ? array($this->value) : $this->value;
			$db     = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->qn(array('user_id', 'user_email', 'firstname', 'lastname')))
				->from($db->qn('#__redshop_users_info'))
				->where($db->qn('user_id') . ' = ' . $db->q($values[0]))
				->where($db->qn('address_type') . ' = ' . $db->q('BT'));

			$users = $db->setQuery($query)->loadObjectList();

			$subQuery = $db->getQuery(true)
				->select($db->qn('username'))
				->from($db->qn('#__users'))
				->where($db->qn('id') . ' = ' . $db->q($values[0]));

			$userName = $db->setQuery($subQuery)->loadResult();

			foreach ($users as $user) {
				if (isset($selected[$user->user_id])) {
					continue;
				}

				$data        = new stdClass;
				$data->value = $user->user_id;
				$data->text  = $user->firstname . $user->lastname . ' ( ' . $userName . ' ) ';
				$selected = $data;
			}
		}

		return JHtml::_(
			'redshopselect.search',
			$selected,
			'jform[' . $this->fieldname . ']',
			array(
				'select2.ajaxOptions' => array('typeField' => ', user:1'),
				'select2.options'     => array(
					'placeholder' => JText::_('COM_REDSHOP_NEWSLETTER_SELECT_USER'),
					'events'      => array('select2-selecting' => 'function(e) {document.getElementById(\'jform_email\').value = e.object.volume;}')
				)
			)
		);
	}
}
