<?php
/**
 * @package     Aesir.iframe.php
 *
 * @copyright   Copyright (C) 2016 - 2022 Aesir. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       __DEPLOY_VERSION__
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Utilities\ArrayHelper;

/**
* Layout variables
* =======================
* @var  array  $displayData          // List of data.
* @var  string $modalButton          // Selector class on the button to trigger the modal
* @var  string $modalFrame           // Id to render the modal
* @var  string $params['width']      // Section ID (Ex: Product ID if $type is product)
* @var  string $params['height']     // Section media (Ex: product)
* @var  string $params['backdrop']   // true or false. Sets the dark background behind the modal
* @var  string $params['animation']  // true or false. Allows the animation or not
* @var  string $params['closebtn']   // true or false. Show or hide the closebtn
* @var  string $params['keyboard']   // true or false. Allows closing the modal with the esc key
* @var  string $params['footer']     // Optional markup for the modal footer
* @var  string $params['bodyHeight'] // Sets the height of the body
* @var  string $params['modalWidth'] // Another way to set the width
*/

extract($displayData);

if (empty($modalButton)) {
    $modalButton = '.RedshopModalButton';
}

if (empty($modalFrame)) {
    $modalFrame = 'RedshopModalFrame';
}

if (empty($params)) {
    $params = [
        'height' => '400px',
        'width'  => '800px',
    ];
}

$iframeAttributes = [];

if (isset($params['height'])) {
    $iframeAttributes['height'] = $params['height'];
}

if (isset($params['width'])) {
    $iframeAttributes['width'] = $params['width'];
}

?>
<script>
    (function($){
        $(document).ready(function () {
            $('<?php echo $modalButton ?>').on('click', function () {
                var modal = $('#<?php echo $modalFrame ?>');
                var frame = modal.find('iframe');
                if (frame.length) {
                    frame.remove();
                }
                modal.find('.modal-body').append('<iframe <?php echo ArrayHelper::toString($iframeAttributes); ?> style="padding:10px" src="'+$(this).data('url')+'" />')
                modal.modal('show');
            });
        });
    })(jQuery);
</script>
<?php

echo HTMLHelper::_(
    'bootstrap.renderModal', $modalFrame, $params
);