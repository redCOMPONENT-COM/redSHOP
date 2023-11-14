<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplication;

$document  = Factory::getDocument();
$msgOutput = '';
$alert     = [
        CMSApplication::MSG_EMERGENCY => 'danger',
        CMSApplication::MSG_ALERT     => 'danger',
        CMSApplication::MSG_CRITICAL  => 'danger',
        CMSApplication::MSG_ERROR     => 'danger',
        CMSApplication::MSG_WARNING   => 'warning',
        CMSApplication::MSG_NOTICE    => 'info',
        CMSApplication::MSG_INFO      => 'info',
        CMSApplication::MSG_DEBUG     => 'info',
        'message'                     => 'success'
];

// Load JavaScript message titles
Text::script('ERROR');
Text::script('MESSAGE');
Text::script('NOTICE');
Text::script('WARNING');

// Load other Javascript message strings
Text::script('JCLOSE');
Text::script('JOK');
Text::script('JOPEN');

// Alerts progressive enhancement
$document->getWebAssetManager()
        ->useStyle('webcomponent.joomla-alert')
        ->useScript('messages');

/**
 * Layout variables
 * ---------------------
 *    $msgList      : (array)    The Multi-Dimentional array having system messages
 *    $showHeading  : (boolean)  An optional variable to show/hide heading text in system message
 *    $allowClose   : (boolean)  An optional variable to allow close system message
 */

/* @var $displayData array */
$msgList     = $displayData['msgList'];
$showHeading = (isset($displayData['showHeading'])) ? $displayData['showHeading'] : true;
$allowClose  = (isset($displayData['allowClose'])) ? $displayData['allowClose'] : true;

if (is_array($msgList) && !empty($msgList)) {
        $messages = [];

        foreach ($msgList as $type => $msgs) {
                // JS loaded messages
                $messages[] = [$alert[$type] ?? $type => $msgs];
                // Noscript fallback
                if (!empty($msgs)) {
                        $msgOutput .= '<div class="alert alert-' . ($alert[$type] ?? $type) . '">';
                        foreach ($msgs as $msg):
                                $msgOutput .= $msg;
                        endforeach;
                        $msgOutput .= '</div>';
                }
        }

        if ($msgOutput !== '') {
                $msgOutput = '<noscript>' . $msgOutput . '</noscript>';
        }

        $document->addScriptOptions('joomla.messages', $messages);
}

?>
<div id="system-message-container" aria-live="polite">
        <?php echo $msgOutput; ?>
</div>
<?php if (is_array($msgList) && !empty($msgList)): ?>
        <div id="system-message">
                <?php foreach ($msgList as $type => $msgs): ?>
                        <div class="alert alert-<?php echo $type; ?>">
                                <?php // This requires JS so we should add it trough JS. Progressive enhancement and stuff. ?>
                                <?php if ($allowClose): ?>
                                        <a class="close" data-dismiss="alert">×</a>
                                <?php endif; ?>
                                <?php if (!empty($msgs)): ?>

                                        <?php if ($showHeading): ?>
                                                <h4 class="alert-heading">
                                                        <?php echo Text::_($type); ?>
                                                </h4>
                                        <?php endif; ?>
                                        <div>
                                                <?php foreach ($msgs as $msg): ?>
                                                        <p>
                                                                <?php echo $msg; ?>
                                                        </p>
                                                <?php endforeach; ?>
                                        </div>
                                <?php endif; ?>
                        </div>
                <?php endforeach; ?>
        </div>
<?php endif; ?>
</div>