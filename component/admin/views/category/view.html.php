<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

JLoader::import('joomla.application.component.view');

/**
 * View Category
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.6
 */
class RedshopViewCategory extends RedshopViewForm
{
    /**
     * @var    integer
     *
     * @since  2.1.2
     */
    protected $is_new = 0;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed         A string if successful, otherwise an Error object.
     *
     * @throws  Exception
     * @since   12.2
     *
     * @see     \Joomla\CMS\MVC\View\HtmlView::loadTemplate()
     */
    public function display($tpl = null)
    {
        HTMLHelper::script('com_redshop/redshop.validation.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.fields.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/json.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/json.min.js', ['relative' => true]);

        $model = $this->getModel('category');

        // Initialise variables.
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        // Accessory of Category
        $categoryAccessoryProduct = array();

        if ($this->item->id) {
            $categoryAccessoryProduct = RedshopHelperAccessory::getProductAccessories(0, 0, 0, $this->item->id);
        } else {
            $this->is_new = 1;
        }

        $this->lists['categroy_accessory_product'] = $categoryAccessoryProduct;
        $this->extraFields                         = $model->getExtraFields($this->item);
        $this->tabmenu                             = $this->getTabMenu();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        parent::display($tpl);
    }

    /**
     * Tab Menu
     *
     * @return  object  Tab menu
     *
     * @throws  Exception
     * @since   1.7
     */
    private function getTabMenu()
    {
        $tabMenu = new RedshopMenu();

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
                '#product_filter',
                'COM_REDSHOP_PRODUCT_FILTERS',
                false,
                'product_filter'
            );

        return $tabMenu;
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @throws  Exception
     * @since   1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $user = Factory::getApplication()->getIdentity();

        if ($this->is_new && (!empty($user->authorise('com_redshop', 'core.create')))) {
            JToolbarHelper::apply('category.apply');
            JToolbarHelper::save('category.save');
            JToolbarHelper::save2new('category.save2new');
            JToolbarHelper::cancel('category.cancel');
        } else {
            // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
            if ((!empty($user->authorise('com_redshop', 'core.edit')))) {
                JToolbarHelper::apply('category.apply');
                JToolbarHelper::save('category.save');

                // We can save this record, but check the create permission to see if we can return to make a new one.
                if ((!empty($user->authorise('com_redshop', 'core.create')))) {
                    JToolbarHelper::save2new('category.save2new');
                }
            }

            $itemId = (int) RedshopHelperRouter::getCategoryItemid($this->item->id);

            $link = JURI::root() . 'index.php?option=com_redshop'
                . '&view=&view=category&layout=detail'
                . '&cid=' . $this->item->id
                . '&Itemid=' . $itemId;

            JToolbarHelper::preview($link, Text::_('JGLOBAL_PREVIEW'), 'eye', 80, 90);

            JToolbarHelper::cancel('category.cancel', Text::_('JTOOLBAR_CLOSE'));
        }
    }
}