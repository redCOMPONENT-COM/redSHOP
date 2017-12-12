<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<?php if ($this->item->twig_support): ?>
<script type="text/javascript">
    function twigEnable() {
        (function($){
            $.redshopAlert(
                "<?php echo JText::_('COM_REDSHOP_INFO') ?>",
                "<?php echo JText::_('COM_REDSHOP_TEMPLATE_TWIG_ENABLE_HINT') ?>",
                'info'
            );
        })(jQuery);
    }
    <?php if ($this->item->twig_enable): ?>
    (function($){
        $(document).ready(function(){
            $("#btn-live-render").click(function(e){
                e.preventDefault();

                $.post('index.php?option=com_redshop&task=template.liveRender',
                    {
                        "content": Joomla.editors.instances["jform_templateDesc"].getValue(),
                        "<?php echo JSession::getFormToken() ?>": 1
                    },
                    function (response) {
                        $("#render-body").removeClass("hidden").find(".panel-body").html(response);
                    }
                );
            })
        });
    })(jQuery);
    <?php endif; ?>
</script>
<?php endif; ?>
<div class="row">
    <div class="col-md-6">
		<?php echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this)) ?>
		<?php if ($this->item->twig_support && $this->item->twig_enable): ?>
			<?php echo $this->loadTemplate('twig') ?>
		<?php endif; ?>
    </div>
    <div class="col-md-6">
	    <?php if ($this->item->twig_support && $this->item->twig_enable): ?>
            <div class="row">
                <button class="btn btn-primary" id="btn-live-render"><i class="icon icon-play"></i><?php echo JText::_('COM_REDSHOP_TEMPLATE_TWIG_LIVE_RENDER_BUTTON') ?></button>
                <hr />
                <div class="panel panel-success hidden" id="render-body">
                    <div class="panel-body">
                    </div>
                </div>
            </div>
	    <?php elseif ($this->item->section): ?>
		    <?php echo $this->loadTemplate('hints') ?>
	    <?php endif; ?>
    </div>
</div>
