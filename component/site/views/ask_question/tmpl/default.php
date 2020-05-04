<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

if (!$app->input->getInt('questionSend', 0)) {
    echo RedshopTagsReplacer::_(
        'askquestion',
        '',
        array(
            'form' => $this->form,
            'ask'  => $app->input->getInt('ask', 0)
        )
    );
} else {
    ?>
    <script>
        setTimeout("window.parent.redBOX.close();", 5000);
    </script>
    <?php
}
