<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity;

defined('_JEXEC') or die;

/**
 * Field Value Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class FieldValue extends Entity
{
    /**
     * Get the associated table
     *
     * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
     *
     * @return  \RedshopTable|bool
     * @since   __DEPLOY_VERSION__
     */
    public function getTable($name = null)
    {
        return \Joomla\CMS\Table\Table::getInstance('Fields_Value', 'Table');
    }
}
