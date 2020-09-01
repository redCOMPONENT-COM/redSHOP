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
 * Media entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class Media extends Entity
{
    /**
     * @var  Entity
     * @since __DEPLOY_VERSION__
     */
    protected $sectionEntity;

    /**
     * Method for get section entity
     *
     * @return  Entity
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getSection()
    {
        if ($this->sectionEntity === null) {
            $this->loadSectionEntity();
        }

        return $this->sectionEntity;
    }

    /**
     * Method for load section entity
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    public function loadSectionEntity()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $entityClass         = '\\Redshop\\Entity\\' . ucfirst($this->get('media_section'));
        $this->sectionEntity = $entityClass::getInstance($this->get('section_id'));

        return $this;
    }
}
