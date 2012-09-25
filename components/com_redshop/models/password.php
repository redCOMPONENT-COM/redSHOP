<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class passwordModelpassword extends JModelLegacy
{
    public $_db = null;

    function __construct()
    {
        parent::__construct();
        $this->_db = &JFactory::getDBO();
    }

    function resetpassword($data)
    {
        $query = "SELECT id FROM #__users WHERE email='" . $data['email'] . "' ";
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();
        if ($id)
        {
            // Generate a new token
            $token = $this->genRandomString();
            $query = 'UPDATE #__users ' . 'SET activation="' . $token . '" ' . 'WHERE id="' . (int)$id . '" ' . 'AND block=0 ';
            $this->_db->setQuery($query);
            // Save the token
            if (!$this->_db->query())
            {
                return false;
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    function genRandomString()
    {
        $length     = 0;
        $length     = 35;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string     = null;
        for ($p = 0; $p < $length; $p++)
        {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }

    function changepassword($token)
    {
        $query = "SELECT id FROM #__users WHERE activation='" . $token . "' ";
        $this->_db->setQuery($query);

        // Check the results
        if (!($id = $this->_db->loadResult()) || trim($token) == "")
        {
            $this->setError(JText::_('COM_REDSHOP_RESET_PASSWORD_TOKEN_ERROR'));
            return false;
        }
        JRequest::setVar('uid', $id);
        return true;
    }

    function setpassword($data)
    {
        $query = 'UPDATE #__users SET password = "' . md5($data['password']) . '", activation = NULL ' . 'WHERE id="' . (int)$data['uid'] . '" ' . 'AND block=0 ';
        $this->_db->setQuery($query);
        // Saving new password
        if (!$this->_db->query())
        {
            return false;
        }
        return true;
    }
}
