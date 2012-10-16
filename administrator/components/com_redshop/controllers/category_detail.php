<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller';

class RedshopControllerCategory_detail extends RedshopCoreController
{
    public $redirectViewName = 'category';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post                       = $this->input->getArray($_POST);
        $category_description       = $this->input->post->getString('category_description', '');
        $category_short_description = $this->input->post->getString('category_short_description', '');

        $post["category_description"] = $category_description;

        $post["category_short_description"] = $category_short_description;

        if (is_array($post["category_more_template"]))
        {
            $post["category_more_template"] = implode(",", $post["category_more_template"]);
        }

        $option               = $this->input->get('option');
        $cid                  = $this->input->post->get('cid', array(0), 'array');
        $post ['category_id'] = $cid [0];
        $model                = $this->getModel('category_detail');

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_CATEGORY_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_CATEGORY_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=category_detail&task=edit&cid[]=' . $row->category_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=category', $msg);
        }
    }
}
