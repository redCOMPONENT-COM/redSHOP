<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Renders a searchtype Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldproductsearchtype extends JFormField
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
    public $type = 'productsearchtype';

    protected function getInput()
    {
        $searchType = array();

        $searchType[] = JHTML::_('select.option', 'p.product_name ASC', Text::_('COM_REDSHOP_PRODUCT_NAME'));
        $searchType[] = JHTML::_('select.option', 'p.product_price ASC', Text::_('COM_REDSHOP_PRODUCT_PRICE_ASC'));
        $searchType[] = JHTML::_('select.option', 'p.product_price DESC', Text::_('COM_REDSHOP_PRODUCT_PRICE_DESC'));
        $searchType[] = JHTML::_('select.option', 'p.product_number ASC', Text::_('COM_REDSHOP_PRODUCT_NUMBER_ASC'));
        $searchType[] = JHTML::_('select.option', 'p.product_id DESC', Text::_('COM_REDSHOP_NEWEST'));
        $searchType[] = JHTML::_('select.option', 'pc.ordering ASC', Text::_('COM_REDSHOP_ORDER'));
        $searchType[] = JHTML::_('select.option', 'm.manufacturer_name ASC', Text::_('COM_REDSHOP_MANUFACTURER_NAME'));

        return JHTML::_(
            'select.genericlist',
            $searchType,
            $this->name,
            'class="inputbox"',
            'value',
            'text',
            $this->value,
            $this->id
        );
    }
}
