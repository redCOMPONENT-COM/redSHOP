<?php
/**
 * @package     redSHOP
 * @subpackage  Elements
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');
/**
 * Renders a Productfinder Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldshippingmethod extends JFormField
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */

    public $type = 'shippingmethod';

    protected function getInput()
    {
        $db = JFactory::getDBO();

        $query = 'SELECT s.* FROM #__extensions AS s ' . 'WHERE s.type="plugin" and s.folder="redshop_shipping" and enabled =1';

        $db->setQuery($query);
        $options = $db->loadObjectList();

        $html = '';
        $name = $this->name;

        for ($i = 0, $n = count($options); $i < $n; $i++)
        {
            $row = &$options[$i];

            $html .= "&nbsp;<input type='hidden' id='" . $row->id . "' name='" . $name . "'  value='" . $row->element . "'   /><br/>";
        }

        return $html;
    }
}
