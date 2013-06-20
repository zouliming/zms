!function($){
    $(function(){
        // side bar
        setTimeout(function () {
            $('.bs-docs-sidenav').affix({
                offset: {
                    top: 70,
                    bottom: 500
                }
            })
        }, 100);
    })
}(window.jQuery);