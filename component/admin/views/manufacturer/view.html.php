<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Manufacturer
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1.0
 */
class RedshopViewManufacturer extends RedshopViewForm
{
	/**
	 * Form layout. (box, tab)
	 *
	 * @var    string
	 *
	 * @since  2.0.6
	 */
	protected $formLayout = 'tab';

	/**
	 * @var    RedshopModelManufacturer
	 *
	 * @since  2.0.6
	 */
	public $model;

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string $tpl Template name
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public function beforeDisplay(&$tpl)
	{
		// Get data from the model
		$this->item = $this->model->getItem();
		$this->form = $this->model->getForm();

		$media = RedshopEntityManufacturer::getInstance($this->item->id)->getMedia();

		if ($media->isValid())
		{
			$this->form->setFieldAttribute('media', 'media-id', $media->get('media_id'));
			$this->form->setFieldAttribute('media', 'media-reference', $this->item->id);
		}

		$this->checkPermission();
		$this->loadFields();
	}

	/**
	 * Method for prepare fields in group and also HTML content
	 *
	 * @param   object  $group  Group object
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since  2.0.6
	 */
	protected function prepareFields($group)
	{
		if ($group->name == 'fields')
		{
			$group->fields = RedshopHelperExtrafields::listAllField(RedshopHelperExtrafields::SECTION_MANUFACTURER, $this->item->id);
			$group->html   = $group->fields;

			return;
		}

		parent::prepareFields($group);
	}

	/**
	 * Method for prepare field HTML
	 *
	 * @param   object $field Group object
	 *
	 * @return  boolean|string  False if keep. String for HTML content if success.
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	protected function prepareField($field)
	{
		if ((string) $field->getAttribute('name') != 'excluding_category_list')
		{
			return parent::prepareField($field);
		}

		// Special case for excluding category
		$pluginManufacturer = RedshopHelperOrder::getParameters('plg_manucaturer_excluding_category');
		$showExcluding      = !empty($pluginManufacturer) && $pluginManufacturer[0]->enabled;

		if (!$showExcluding)
		{
			return parent::prepareField($field);
		}

		$this->form->setFieldAttribute('excluding_category_list', 'type', 'text');

		return '<div class="form-group row-fluid">'
			. $this->form->getLabel($field->getAttribute('name'))
			. '<div class="col-md-10">'
			. RedshopHelperCategory::listAll(
				"excluding_category_list[]",
				0,
				$this->item->excluding_category_list,
				10,
				false,
				true,
				array(),
				'100%'
			)
			. '</div></div>';
	}
}
