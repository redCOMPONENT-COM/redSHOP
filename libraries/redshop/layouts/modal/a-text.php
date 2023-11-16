<?php
/**
 * @package     redshop.modal.a.text.php
 *
 * @copyright   Copyright (C) 2023 - redSHOP for Joomla - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       __DEPLOY_VERSION__
 */

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::script('com_redshop/glightbox.js', ['relative' => true]);
HTMLHelper::stylesheet('com_redshop/glightbox.css', ['relative' => true]);

/**
 * Layout variables
 * =======================
 * @var  array  $displayData              // List of data.
 * @var  string $selector                 // Id to trigger the modal
 * @var  string $params['title']          // Title for the modal
 * @var  string $params['aText']          // Text for the button
 * @var  string $params['aClass']         // Class for the buttom
 * @var  string $params['aId']            // Id for the buttom
 * @var  string $params['url']            // url for a iFrame modal
 * @var  string $params['imageThumbPath'] // url for a iFrame modal
 * @var  string $params['imageMainPath']  // url for a iFrame modal
 * @var  string $params['closebtn']       // true or false. Show or hide the closebtn in header
 * @var  string $params['width']          // Width for the iframe - Does not work
 * @var  string $params['height']         // Height for the iframe - Does not work
 * @var  string $params['description']    // Optional Text for the modal description
 * @var  string $imageAttributes[]        // Array of optional attributes e.g. ['alt' => 'Image name', 'xxx' => 'Xxxx']
 */

extract($displayData);

if (empty($selector)) {
    $selector = 'RedshopModalAButton';
}

if (empty($params['title'])) {
    $params['title'] = '';
}

if (empty($params['aText'])) {
    $params['aText'] = '';
}

if (empty($params['aClass'])) {
    $params['aClass'] = '';
}

if (empty($params['aId'])) {
    $params['aId'] = '';
}

if (empty($params['url'])) {
    $params['url'] = '';
}

if (empty($params['closeButton'])) {
    $params['closeButton'] = '';
}

if (empty($params['width'])) {
    $params['width'] = '';
}

if (empty($params['height'])) {
    $params['height'] = '';
}

if (empty($params['description'])) {
    $params['description'] = '';
}
?>

<a class="<?php echo $selector ?> <?php echo $params['aClass'] ?>" id="<?php echo $params['aId'] ?>"
    href="<?php echo $params['imageMainPath'] ?>" data-glightbox="<?php echo $params['description'] ?>">
    <?php echo $params['aText']; ?>
</a>

<script type="text/javascript">
    var lightboxDescription = GLightbox({
        selector: ".<?php echo $selector ?>",
        openEffect: "zoom",
        closeEffect: "zoom"
    });
</script>
