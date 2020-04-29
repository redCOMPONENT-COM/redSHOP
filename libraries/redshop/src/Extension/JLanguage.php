<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Extension;

defined('_JEXEC') || die;

use Joomla\CMS\Language\Language;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * JLanguage integration for Twig.
 *
 * @since  2.1.5
 */
final class JLanguage extends AbstractExtension
{
    /**
     *
     * @return array
     *
     * @since  2.1.5
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('jlang', [Language::class, 'getInstance'])
        ];
    }

    /**
     *
     * @return string
     *
     * @since  2.1.5
     */
    public function getName(): string
    {
        return 'jlang';
    }
}
