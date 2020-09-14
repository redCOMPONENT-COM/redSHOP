<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Language;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Language;
use Joomla\CMS\Language\LanguageHelper;

defined('_JEXEC') or die;

/**
 * Language Helper
 *
 * @since  __DEPLOY_VERION__
 */

class Helper extends Language
{
    /**
     * Returns a language object.
     *
     * @param   string   $lang   The language to use.
     * @param   boolean  $debug  The debug mode.
     *
     * @return  Language  The Language object.
     *
     * @since   __DEPLOY_VERSION__
     */
    public static function getInstance($lang = '', $debug = false)
    {
        $conf   = Factory::getConfig();
        $lang = !empty($lang) ? $lang : $conf->get('language');

        if ($debug === false) {
            $debug = $conf->get('debug_lang');
        }

        if (!isset(self::$languages[$lang . $debug]))
        {
            self::$languages[$lang . $debug] = new Helper($lang, $debug);
        }

        return self::$languages[$lang . $debug];
    }

    public function setLanguage($lang)
    {
        $previous = $this->lang;
        $this->lang = $lang;
        $this->metadata = LanguageHelper::getMetadata($this->lang);

        return $previous;
    }
}