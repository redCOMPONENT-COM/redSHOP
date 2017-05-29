<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/redshop/library.php';

/**
 * Rules field override
 *
 * @since  1.0
 */
class RedshopFormFieldRules extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Rules';

	/**
	 * Method to get the field input markup for Access Control Lists.
	 * Optionally can be associated with a specific component and section.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		JHtml::_('bootstrap.tooltip');

		// Initialise some field attributes.
		$section    = $this->element['section'] ? (string) $this->element['section'] : '';
		$component  = $this->element['component'] ? (string) $this->element['component'] : '';
		$assetField = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$showGroup  = $this->element['show_group'] ? (string) $this->element['show_group'] : '';
		$showGroup  = ($showGroup == 'true') ? true : false;

		// Current view is global config?
		$isGlobalConfig = $component === 'root.1';

		// Get the actions for the asset.
		$actions = JAccess::getActions($component, $section);

		// Iterate over the children and add to the actions.
		foreach ($this->element->children() as $el)
		{
			if ($el->getName() != 'action')
			{
				continue;
			}

			$actions[] = (object) array(
				'name'        => (string) $el['name'],
				'title'       => (string) $el['title'],
				'description' => (string) $el['description']
			);
		}

		if ($showGroup === true)
		{
			$newActions = array();

			foreach ($actions as $action)
			{
				$group = explode('.', (string) $action->name);
				$group = $group[0];

				if (!isset($newActions[$group]))
				{
					$newActions[$group] = array();
				}

				$newActions[$group][] = $action;
			}

			$actions = $newActions;
		}

		ksort($actions);

		// Get the asset id.
		// Note that for global configuration, com_config injects asset_id = 1 into the form.
		$assetId       = $this->form->getValue($assetField);
		$newItem       = empty($assetId) && $isGlobalConfig === false && $section !== 'component';
		$parentAssetId = null;

		// If the asset id is empty (component or new item).
		if (empty($assetId))
		{
			// Get the component asset id as fallback.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('name') . ' = ' . $db->quote($component));

			$db->setQuery($query);

			$assetId = (int) $db->loadResult();

			/**
			 * @to do: incorrect info
			 * When creating a new item (not saving) it uses the calculated permissions from the component (item <-> component <-> global config).
			 * But if we have a section too (item <-> section(s) <-> component <-> global config) this is not correct.
			 * Also, currently it uses the component permission, but should use the calculated permissions for achild of the component/section.
			 */
		}

		// If not in global config we need the parent_id asset to calculate permissions.
		if (!$isGlobalConfig)
		{
			// In this case we need to get the component rules too.
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->quoteName('parent_id'))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('id') . ' = ' . $assetId);

			$db->setQuery($query);

			$parentAssetId = (int) $db->loadResult();
		}

		// Full width format.

		// Get the rules for just this asset (non-recursive).
		$assetRules = JAccess::getAssetRules($assetId, false, false);

		// Get the available user groups.
		$groups = $this->getUserGroups();

		return RedshopLayoutHelper::render(
			'field.rules.wrapper',
			array(
				'groups'         => $groups,
				'actions'        => $actions,
				'field'          => $this,
				'newItem'        => $newItem,
				'assetRules'     => $assetRules,
				'assetId'        => $assetId,
				'isGlobalConfig' => $isGlobalConfig,
				'component'      => $component,
				'showGroup'      => $showGroup,
				'section'        => $section
			)
		);
	}

	/**
	 * Get a list of the user groups.
	 *
	 * @return  array
	 *
	 * @since   11.1
	 */
	protected function getUserGroups()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level, a.parent_id')
			->from('#__usergroups AS a')
			->join('LEFT', $db->quoteName('#__usergroups') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->group('a.id, a.title, a.lft, a.rgt, a.parent_id')
			->order('a.lft ASC');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		return $options;
	}
}
