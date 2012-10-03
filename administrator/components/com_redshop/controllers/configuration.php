<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'currency.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'extra_field.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'default.php';

class RedshopControllerConfiguration extends RedshopCoreControllerDefault
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->_configpath1 = JPATH_SITE . DS . "administrator" . DS . "components" . DS . "com_redshop" . DS . "helpers" . DS . "newtxt.php";
    }

    public function apply()
    {
        $this->save(1);
    }

    public function save($apply = 0)
    {
        $post = $this->input->getArray($_POST);

        for ($p = 0; $p < $post['tot_prod']; $p++)
        {
            if ($post['prodmng' . $p] != "")
            {
                $selected_prod[] = $post['prodmng' . $p];
            }
        }
        if (count($selected_prod) > 0)
        {
            $quicklink_icon = implode(",", $selected_prod);
        }

        for ($p = 0; $p < $post['tot_ord']; $p++)
        {
            if ($post['ordermng' . $p] != "")
            {
                $selected_ord[] = $post['ordermng' . $p];
            }
        }

        if (count($selected_ord) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_ord);
        }

        for ($p = 0; $p < $post['tot_dist']; $p++)
        {
            if ($post['distmng' . $p] != "")
            {
                $selected_dist[] = $post['distmng' . $p];
            }
        }

        if (count($selected_dist) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_dist);
        }

        for ($p = 0; $p < $post['tot_comm']; $p++)
        {
            if ($post['commmng' . $p] != "")
            {
                $selected_comm[] = $post['commmng' . $p];
            }
        }

        if (count($selected_comm) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_comm);
        }

        for ($p = 0; $p < $post['tot_imp']; $p++)
        {
            if ($post['impmng' . $p] != "")
            {
                $selected_imp[] = $post['impmng' . $p];
            }
        }

        if (count($selected_imp) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_imp);
        }

        for ($p = 0; $p < $post['tot_vat']; $p++)
        {
            if ($post['vatmng' . $p] != "")
            {
                $selected_vat[] = $post['vatmng' . $p];
            }
        }

        if (count($selected_vat) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_vat);
        }

        for ($p = 0; $p < $post['tot_cust']; $p++)
        {
            if ($post['custmng' . $p] != "")
            {
                $selected_cust[] = $post['custmng' . $p];
            }
        }

        if (count($selected_cust) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_cust);
        }
        for ($p = 0; $p < $post['tot_alt']; $p++)
        {
            if ($post['altmng' . $p] != "")
            {
                $selected_alt[] = $post['altmng' . $p];
            }
        }

        if (count($selected_alt) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_alt);
        }

        for ($p = 0; $p < $post['tot_user']; $p++)
        {
            if ($post['usermng' . $p] != "")
            {
                $selected_user[] = $post['usermng' . $p];
            }
        }

        if (count($selected_user) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_user);
        }
        for ($p = 0; $p < $post['tot_shipping']; $p++)
        {
            if ($post['shippingmng' . $p] != "")
            {
                $selected_shipping[] = $post['shippingmng' . $p];
            }
        }

        if (count($selected_shipping) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_shipping);
        }
        for ($p = 0; $p < $post['tot_acc']; $p++)
        {
            if ($post['accmng' . $p] != "")
            {
                $selected_acc[] = $post['accmng' . $p];
            }
        }

        if (count($selected_acc) > 0)
        {
            $quicklink_icon .= "," . implode(",", $selected_acc);
        }

        //$quicklink_icon=array_merge($selected_prod, $selected_ord, $selected_dist);

        $quicklink_icon;

        $post['quicklink_icon'] = $quicklink_icon;

        $post['custom_previous_link'] = $this->input->post->getString('custom_previous_link', '');

        $post['custom_next_link']          = $this->input->post->getString('custom_next_link', '');
        $post['default_next_suffix']       = $this->input->post->getString('default_next_suffix', '');
        $post['default_previous_prefix']   = $this->input->post->getString('default_previous_prefix', '');
        $post['return_to_category_prefix'] = $this->input->post->getString('return_to_category_prefix', '');

        // administrator email notifications ids
        if (is_array($post['administrator_email']))
        {

            $post['administrator_email'] = implode(",", $post['administrator_email']);
        }

        $option                = $this->input->get('option');
        $country_list          = $this->input->get('country_list');
        $newsletter_test_email = $this->input->get('newsletter_test_email');

        $model = $this->getModel('configuration');

        $i                = 0;
        $country_listCode = '';
        if ($country_list)
        {
            foreach ($country_list as $key=> $value)
            {

                $country_listCode .= $value;
                $i++;
                if ($i < count($country_list))
                {
                    $country_listCode .= ',';
                }
            }
        }
        $post['country_list'] = $country_listCode;

        if (isset($post['product_download_root']))
        {

            if (!is_dir($post['product_download_root']))
            {

                $msg = "";

                JError::raiseWarning(21, JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DIRECTORY_DOES_NO_EXIST'));
            }
            elseif (!$model->configurationWriteable())
            {

                JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_WRITABLE'));
            }
            elseif (!$model->configurationReadable())
            {

                JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_READABLE'));
            }
            else
            {

                // handle .htaccess file generation
                $model->handleHtaccess($post['product_download_root']);

                if ($model->store($post))
                {
                    $msg = JText::_('COM_REDSHOP_CONFIG_SAVED');

                    if ($newsletter_test_email)
                    {
                        $model->newsletterEntry($post);
                        echo $msg = JText::_('COM_REDSHOP_NEWSLETTER_SEND_TO_TEST_EMAIL');
                    }

                    # Thumb folder deleted/created
                    if ($post['image_quality_output'] != IMAGE_QUALITY_OUTPUT)
                    {
                        $this->removeThumbImages();
                    }
                }
                else
                {
                    $msg = JText::_('COM_REDSHOP_ERROR_IN_CONFIG_SAVE');
                }
            }
        }
        if ($apply)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=configuration', $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option);
        }
    }

    /**
     * remove all thumbanil
     * for Image quality percentage change variable IMAGE_QUALITY_OUTPUT
     *
     */
    public function removeThumbImages()
    {
        $thumb_folder = array('product', 'category', 'manufacturer', 'product_attributes', 'property', 'subcolor', 'wrapper', 'shopperlogo');

        for ($i = 0; $i < count($thumb_folder); $i++)
        {
            $unlink_path = REDSHOP_FRONT_IMAGES_RELPATH . $thumb_folder[$i] . '/thumb';

            $files = JFolder::files($unlink_path, '.', false, true, array());

            if (is_dir($unlink_path))
            {
                if (!empty($files))
                {
                    if (JFolder::delete($unlink_path) !== true)
                    {
                        // JFile::delete throws an error
                        return false;
                    }
                    else
                    {
                        if (JFolder::create($unlink_path) !== true)
                        {
                            return false;
                        }
                        else
                        {
                            $src = REDSHOP_FRONT_IMAGES_RELPATH . 'index.html';
                            JFile::COPY($src, $unlink_path . '/index.html');
                        }
                    }
                }
            }
        }
    }

    public function removeimg()
    {
        ob_clean();
        $imname      = $this->input->getString('imname', '');
        $spath       = $this->input->getString('spath', '');
        $data_id     = $this->input->getInt('data_id', 0);
        $extra_field = new    extra_field();

        if ($data_id)
        {
            $extra_field->deleteExtraFieldData($data_id);
        }

        if (JPATH_ROOT . DS . $spath . DS . $imname)
        {
            unlink(JPATH_ROOT . DS . $spath . DS . $imname);
        }
        exit;
    }

    public function cancel()
    {
        $option = $this->input->get('option');
        $this->setRedirect('index.php?option=' . $option);
    }

    public function display($cachable = false, $urlparams = false)
    {
        $model         = $this->getModel('configuration');
        $currency_data = $model->getCurrency();
        $this->input->set('currency_data', $currency_data);

        parent::display();
    }

    public function clearsef()
    {
        $model     = $this->getModel('configuration');
        $cleardata = $model->cleardata();
        echo $cleardata;
        exit;
    }

    public function resetTemplate()
    {
        $model  = $this->getModel('configuration');
        $option = $this->input->get('option');
        $model->resetTemplate();

        $msg = JText::_('COM_REDSHOP_TEMPLATE_HAS_BEEN_RESET');
        $this->setRedirect('index.php?option=' . $option, $msg);
    }

    public function resetTermsCondition()
    {
        $userhelper = new rsUserhelper();
        $userhelper->updateUserTermsCondition();
        die();
    }

    public function resetOrderId()
    {
        $order_functions = new order_functions();
        $order_functions->resetOrderId();
        die();
    }
}
