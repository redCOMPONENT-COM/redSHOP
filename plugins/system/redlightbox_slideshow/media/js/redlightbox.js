function preloadSlimbox(isenable)
{
    (function($){
        $(document).ready(function(){
            $("a[rel^=\'myallimg\']").attr("rel","lightbox[gallery]");
            if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent))
            {
                $("a[rel^=\'lightbox\']").slimbox(isenable, null, function(el) {
                    return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
                });
            }
            else
            {
                $("a[rel^=\'lightbox\']").photoSwipe({ enableMouseWheel: false , enableKeyboard: false, captionAndToolbarAutoHideDelay: 0});
            }
        });
    })(jQuery);
}