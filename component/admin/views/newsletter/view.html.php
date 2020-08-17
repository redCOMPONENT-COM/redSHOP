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
 * View newsletter
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewNewsletter extends RedshopViewForm
{
	/**
	 * Method for prepare field HTML
	 *
	 * @param   object  $field  Group object
	 *
	 * @return  boolean|string  False if keep. String for HTML content if success.
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws \Exception
	 */
	protected function prepareField($field)
	{
		switch ($field->getAttribute('name'))
        {
            case 'template_id':
                return RedshopLayoutHelper::render(
                    'newsletter.field_template',
                    [
                        'htmlField' => parent::/** @scrutinizer ignore-call */ prepareField($field),
                        'item' => $this->item
                    ]
                );
            case 'tags_default':
                return RedshopLayoutHelper::render('newsletter.tags_default');
            default:
                return parent::prepareField($field);
        }
	}

    /**
     * Method for get page title.
     *
     * @return  string
     *
     * @throws  Exception
     * @since   __DEPLOY_VERSION__
     */
    public function getTitle()
    {
        if ($this->getLayout() == 'statistics')
        {
            $title      = \JText::_('COM_REDSHOP_' . strtoupper($this->getInstanceName()));
            return $title . ' <small>[ statistics ]</small>';
        }

        return parent::getTitle();
    }

    /**
     * Method for add toolbar.
     *
     * @return  void
     * @throws  Exception
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function addToolbar()
    {
        if ($this->getLayout() == 'statistics')
        {
            JToolbarHelper::cancel($this->getInstanceName() . '.cancel');
        }
        else {
            parent::addToolbar();
        }
    }
}
