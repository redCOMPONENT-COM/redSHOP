<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
JLoader::import('basic', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers');

/**
 * Render an List choose by use Helper from CMC
 *
 * @package     RedSHOP.Plugins
 * @subpackage  Cmc_Integrate
 * @since       1.0.0
 */
class JFormFieldCmclist extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var     string
	 */
	public $type = 'Cmclist';

	/**
	 * Get the options
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		$lists = CmcHelperBasic::getLists();
		$input = JFActory::getApplication()->input;

		$options = array();

		foreach ($lists as $list)
		{
			$options[] = JHTML::_('select.option', $list->mc_id, $list->list_name);
		}

		if ($input->get('filter_list'))
		{
			$this->value = $input->get('filter_list');
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
