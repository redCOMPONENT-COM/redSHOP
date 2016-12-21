<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Shopper Group controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       __DEPLOY_VERSION__
 */
class RedshopControllerShopper_Group extends RedshopControllerForm
{
	/**
	 * Save question
	 *
	 * @param   integer  $send    Send Question?
	 * @param   string   $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @todo    I know, I know this is not a proper way. But we needs to move to form way.
	 *
	 * @return  boolean  True if successful, false otherwise.
	 */
	/*public function save($send = 0, $urlVar = null)
	{
		$post = $this->input->post->getArray();
		$data = $post['jform'];

		$model = $this->getModel('Shopper_Group');
		$row   = $model->save($data);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_SAVE_SUCCESSFUL');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_SAVE_ERROR');
		}

		$this->setRedirect('index.php?option=com_redshop&view=shopper_groups', $msg);
	}*/
}
