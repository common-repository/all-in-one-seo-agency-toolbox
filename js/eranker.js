var $ = jQuery; //TODO FIX THIS HACK!
(function ($) {
    $(window).load(function () {        
        if (typeof is_processed !== "undefined" && !is_processed) {
            processErReport();
        }
    });
})(jQuery);