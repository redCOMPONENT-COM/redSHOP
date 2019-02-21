<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @return boolean
	 * @throws Exception
	 */
	public function submit()
	{
		// Check for request forgeries.
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$app        = JFactory::getApplication();
		$data       = $app->input->post->get('jform', array(), 'array');
		$productId  = $app->input->getInt('pid', 0);
		$itemId     = $app->input->getInt('Itemid', 0);
		$ask        = $app->input->getInt('ask', 0);
		$categoryId = $app->input->getInt('category_id', 0);

		/** @var RedshopModelAsk_Question $model */
		$model = $this->getModel('ask_question');

		if ($ask)
		{
			$link = 'index.php?option=com_redshop&view=product&pid=' . $productId . '&cid=' . $categoryId . '&Itemid=' . $itemId;
		}
		else
		{
			$link = 'index.php?option=com_redshop&view=ask_question&pid=' . $productId . '&tmpl=component&Itemid=' . $itemId;
		}

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			/** @scrutinizer ignore-deprecated */ JError::raiseError(500, $model->getError());
			$this->setRedirect(JRoute::_($link, false));

			return false;
		}

		// Save the data in the session.
		$app->setUserState('com_redshop.ask_question.data', $data);

		// Check captcha only for guests
		if (JFactory::getUser()->guest)
		{
			// Check exists captcha tag in question template form
			$template = RedshopHelperTemplate::getTemplate('ask_question_template');

			if (count($template) > 0 && strstr($template[0]->template_desc, '{captcha}')
				&& Redshop\Helper\Utility::checkCaptcha($data, false))
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_INVALID_SECURITY'), 'warning');
				$this->setRedirect(JRoute::_($link, false));

				return false;
			}
		}

		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors = /** @scrutinizer ignore-deprecated */ $model->getErrors();

			foreach ($errors as $index => $error)
			{
				if ($error instanceof Exception)
				{
					$app->enqueueMessage($error->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($error, 'warning');
				}

				if ($index > 2)
				{
					break;
				}
			}
		}
		else
		{
			$data['product_id'] = $productId;
			$data['Itemid']     = $itemId;

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
				$app->enqueueMessage(/** @scrutinizer ignore-deprecated */ $model->getError(), 'warning');
			}
		}

		$this->setRedirect(JRoute::_($link, false));
	}
}
