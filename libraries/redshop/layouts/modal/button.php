<?php
/**
 * @package     redshop.modal.button.php
 *
 * @copyright   Copyright (C) 2023 - redSHOP for Joomla - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::script('com_redshop/glightbox.js', ['relative' => true]);
HTMLHelper::stylesheet('com_redshop/glightbox.css', ['relative' => true]);

/**
 * Layout variables
 * =======================
 * @var  array  $displayData            // List of data.
 * @var  string $selector               // Class to trigger the modal
 * @var  string $params['buttonText']   // Text for the button
 * @var  string $params['buttonClass']  // Class for the buttom
 * @var  string $params['buttonId']     // Id for the buttom
 * @var  string $params['url']          // url for a iFrame modal
 * @var  string $params['width']        // Width for the iframe - Does not work
 * @var  string $params['height']       // Height for the iframe - Does not work
 * @var  string $params['description']  // Optional Text for the modal description - "title:Xxxx; description:Xxxx"
 * @var  string $params['descPosition'] // Optional position for description - top, bottom, right, left - default bottom
 */

extract($displayData);

if (empty($selector)) {
    $selector = 'RedshopModalButton';
}

if (empty($params['buttonText'])) {
    $params['buttonText'] = '';
}

if (empty($params['buttonClass'])) {
    $params['buttonClass'] = '';
}

if (empty($params['buttonId'])) {
    $params['buttonId'] = '';
}

if (empty($params['url'])) {
    $params['url'] = '';
}

if (empty($params['height'])) {
    $params['height'] = '';
}

if (empty($params['width'])) {
    $params['width'] = '';
}

if (empty($params['description'])) {
    $params['description'] = '';
    $descPosition          = '';
} else {
    if (empty($params['descPosition'])) {
        $descPosition = '"bottom",';
    } else {
        $descPosition = 'descPosition: "' . $params['descPosition'] . '"';
    }

}
?>

<a class="<?php echo $selector ?> <?php echo $params['buttonClass'] ?> hasTooltip"
    id="<?php echo $params['buttonId'] ?>" href="<?php echo $params['url'] ?>"
    data-glightbox="<?php echo $params['description'] ?>">
    <?php echo $params['buttonText'] ?>
</a>

<script type="text/javascript">
    var lightboxDescription = GLightbox({
        selector: ".<?php echo $selector ?>",
        openEffect: "zoom",
        closeEffect: "zoom",
        <?php echo $descPosition ?>
    });
</script>
