<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 * Attribute Prices Model.
 *
 * @package        redSHOP
 * @subpackage     Models
 * @since          1.2
 */
class RedshopModelAttributeprices extends JModelList
{
    /**
     * Context string for the model type.  This is used to handle uniqueness
     * when dealing with the getStoreId() method and caching data structures.
     *
     * @var    string
     */
    protected $context = 'price_id';

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Load the filter state.
        $section = $this->getUserStateFromRequest($this->context . 'section', 'section', '', 'string');
        $this->setState('filter.section', $section);

        $sectionId = $this->getUserStateFromRequest($this->context . 'section_id', 'section_id', 0, 'int');
        $this->setState('filter.section_id', $sectionId);

        parent::populateState();
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     */
    protected function getListQuery()
    {
        $db = JFactory::getDbo();

        $section  = $this->getState('filter.section');
        $sectionId = (int) $this->getState('filter.section_id');

        if ($section === 'property')
        {
            $field = 'ap.property_name';
            $leftJoin = '#__redshop_product_attribute_property AS ap ON p.section_id = ap.property_id';
        }
        else
        {
            $field = 'ap.subattribute_color_name AS property_name';
            $leftJoin = '#__redshop_product_subattribute_color AS ap ON p.section_id = ap.subattribute_color_id';
        }

        $query = $db->getQuery(true)
            ->select('p.*, g.shopper_group_name')
            ->select($field)
            ->from('#__redshop_product_attribute_price AS p')
            ->leftJoin('#__redshop_shopper_group AS g ON p.shopper_group_id = g.shopper_group_id')
            ->leftJoin($leftJoin)
            ->where('p.section_id =' . $sectionId)
            ->where('p.section =' . $db->quote($section));

        return $query;
    }
}

