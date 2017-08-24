<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Ask Question Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerAsk_Question extends RedshopControllerForm
{
	/**
	 * Method to send Ask Question Mail.
	 *
	 * @return bool
	 */
	public function submit()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app         = JFactory::getApplication();
		$data        = $app->input->post->get('jform', array(), 'array');
		$model       = $this->getModel('ask_question');
		$productId   = $app->input->getInt('pid', 0);
		$Itemid      = $app->input->getInt('Itemid', 0);
		$ask         = $app->input->getInt('ask', 0);
		$category_id = $app->input->getInt('category_id', 0);
		$userHelper  = rsUserHelper::getInstance();

		if ($ask)
		{
			$link = 'index.php?option=com_redshop&view=product&pid=' . $productId . '&cid=' . $category_id . '&Itemid=' . $Itemid;
		}
		else
		{
			$link = 'index.php?option=com_redshop&view=ask_question&pid=' . $productId . '&tmpl=component&Itemid=' . $Itemid;
		}

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			$this->setRedirect($link);

			return false;
		}

		// Save the data in the session.
		$app->setUserState('com_redshop.ask_question.data', $data);

		// Check captcha only for guests
		if (JFactory::getUser()->guest)
		{
			// Check exists captcha tag in question template form
			$redTemplate = Redtemplate::getInstance();
			$template = $redTemplate->getTemplate('ask_question_template');

			if (count($template) > 0 && strstr($template[0]->template_desc, '{captcha}') && !$userHelper->checkCaptcha($data, false))
			{
					$app->enqueueMessage(JText::_('COM_REDSHOP_INVALID_SECURITY'), 'warning');
					$this->setRedirect($link);

					return false;
			}
		}

		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
		}
		else
		{
			$data['product_id'] = $productId;
			$data['Itemid'] = $Itemid;

			if ($model->sendMailForAskQuestion($data))
			{
				// Flush the data from the session
				$app->setUserState('com_redshop.ask_question.data', null);
				$app->enqueueMessage(JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY'));

				if (!$ask)
				{
					$link .= '&questionSend=1';
				}
			}
			else
			{
				$app->enqueueMessage($model->getError(), 'warning');
			}
		}

		$this->setRedirect($link);
	}
}
