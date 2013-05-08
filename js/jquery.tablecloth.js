(function($){
    $.fn.tablecloth = function(options){
        var defaults = {
            theme:'default',
            bordered:true,
            striped:true,
            sortable:false,
            clean:false,
            cleanElements:'*'
        };
        var opts = $.extend(defaults,options);
        if (opts.clean) {
            this.removeAttr('class')
            .removeAttr('style')
            .removeAttr('cellpadding')
            .removeAttr('cellspacing')
            .removeAttr('bgcolor')
            .removeAttr('align')
            .removeAttr('width')
            .removeAttr('nowrap');
 	      
            this.find(opts.cleanElements).each(function() {
                $(this).removeAttr('style')
                .removeAttr('cellpadding')
                .removeAttr('cellspacing')
                .removeAttr('bgcolor')
                .removeAttr('align')
                .removeAttr('width')
                .removeAttr('nowrap');
            });
        }
        // 设置表格主题
        if (opts.theme == "default") {
            this.addClass("table");
        }else if (opts.theme == "dark") {
            this.addClass("table table-dark");
        }else if (opts.theme == "stats") {
            this.addClass("table table-stats");
        }else if (opts.theme == "paper") {
            this.addClass("table table-paper");
        }
	 	
        // 设置自定义Css类
        if (opts.customClass != "") {
            this.addClass(opts.customClass);
        }
	 	
        // 设置表格参数
        //压缩
        if (opts.condensed) {
            this.addClass("table-condensed");
        }
        //边框
        if (opts.bordered) {
            this.addClass("table-bordered");
        }
        //斑马纹
        if (opts.striped) {
            this.addClass("table-striped");
        }
        //排序
        if (opts.sortable) {
            this.addClass("table-sortable");
            if (jQuery().tablesorter) {
                this.tablesorter({
                    cssHeader: "headerSortable"
                });
            } else {
                console.log('Tablesorter is not loaded');
            }
        }
    }
})(jQuery);