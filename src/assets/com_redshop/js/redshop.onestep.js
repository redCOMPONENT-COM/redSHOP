(function($){
    $(document).ready(function(){
        $(".onestep-createaccount-toggle").change(function(evt){
            evt.preventDefault();
            $("#onestep-createaccount-wrapper").slideToggle('medium');
        });
    });
})(jQuery);