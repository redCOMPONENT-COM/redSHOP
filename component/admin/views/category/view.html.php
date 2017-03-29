<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

/**
 * View Category
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */
class RedshopViewCategory extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	protected $form;

	protected $item;

	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   12.2
	 */
	public function display($tpl = null)
	{
		$redTemplate      = Redtemplate::getInstance();
		$product_category = new product_category;
		$producthelper    = productHelper::getInstance();

		$document = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/validation.js');
		$document->addScript('components/com_redshop/assets/js/fields.js');
		$document->addScript('components/com_redshop/assets/js/json.js');

		$model = $this->getModel('category');

		// Initialise variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Accessory of Category
		$categoryAccessoryProduct = array();

		if ($this->item->category_id)
		{
			$categoryAccessoryProduct = $producthelper->getProductAccessory(0, 0, 0, $this->item->category_id);
		}

		$this->lists['categroy_accessory_product'] = $categoryAccessoryProduct;
		$this->extraFields                         = $model->getExtraFields($this->item);
		$this->tabmenu                             = $this->getTabMenu();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));

			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Tab Menu
	 *
	 * @return  object  Tab menu
	 *
	 * @since   1.7
	 */
	private function getTabMenu()
	{
		$app = JFactory::getApplication();

		$tabMenu = RedshopAdminMenu::getInstance()->init();
		$tabMenu->section('tab')
					->title('COM_REDSHOP_CATEGORY_INFORMATION')
					->addItem(
						'#information',
						'COM_REDSHOP_CATEGORY_INFORMATION',
						true,
						'information'
					)->addItem(
						'#seo',
						'COM_REDSHOP_META_DATA_TAB',
						false,
						'seo'
					)->addItem(
						'#extrafield',
						'COM_REDSHOP_FIELDS',
						false,
						'extrafield'
					)->addItem(
						'#accessory',
						'COM_REDSHOP_ACCESSORY_PRODUCT',
						false,
						'accessory'
					)->addItem(
						'#access',
						'JCONFIG_PERMISSIONS_LABEL',
						false,
						'access'
					);

		return $tabMenu;
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$isNew = ($this->item->category_id < 1);
		$user       = JFactory::getUser();

		// Prepare text for title
		$title = JText::_('COM_REDSHOP_CATEGORY') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';

		JToolBarHelper::title($title, 'redshop_categories48');

		if ($isNew && (count($user->authorise('com_redshop', 'core.create')) > 0))
		{
			JToolBarHelper::apply('category.apply');
			JToolBarHelper::save('category.save');
			JToolBarHelper::save2new('category.save2new');
			JToolBarHelper::cancel('category.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			if ((count($user->authorise('com_redshop', 'core.edit')) > 0))
			{
				JToolbarHelper::apply('category.apply');
				JToolbarHelper::save('category.save');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ((count($user->authorise('com_redshop', 'core.create')) > 0))
				{
					JToolbarHelper::save2new('category.save2new');
				}
			}

			JToolBarHelper::cancel('category.cancel', JText::_('JTOOLBAR_CLOSE'));

			$objhelper = redhelper::getInstance();
			$itemId    = (int) $objhelper->getCategoryItemid($this->item->category_id);

			$link  = JURI::root() . 'index.php?option=com_redshop'
					. '&view=&view=category&layout=detail'
					. '&cid=' . $this->item->category_id
					. '&Itemid=' . $itemId;

			RedshopToolbarHelper::link($link, 'preview', 'JGLOBAL_PREVIEW', '_blank');
		}
	}
}
