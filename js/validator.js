/* 
 * 用来验证表单
 * author：liming.zou@vipshop.com
 */
(function($){
    $.fn.va = function(options){
        /**
         * messageEle 文本框的错误提示信息的属性
         * submitEle  提交的form的表达式
         */
        var defaults = {
            'required':{
                'ele':'',
                'errorAttr':','
            },
            'compare':{
                'ele':'',
                'errorMessage':''
            },
            'callback':'',
            'passFunction':''
        };
        var opts = $.extend(defaults,options);
        var checkResult = true;
        var requiredEle = opts.required.ele.split(",");
        $.each(requiredEle,function(i,n){
            var e = $(n);
            if(e.val()==""){
                alert(e.attr(opts.required.errorAttr));
                checkResult = false;
                return false;
            }
        })
        if(checkResult == true && opts.compare.ele!=""){
            var compareEle = opts.compare.ele.split(',');
            var tempV;
            $.each(compareEle,function(i,n){
                if(i==0){
                    tempV = $(n).val();
                }else{
                    if(tempV != $(n).val()){
                        alert(opts.compare.errorMessage);
                        checkResult = false;
                        return false;
                    }
                }
            })
        }
        if(checkResult==true){
            if(opts.callback!=''){
                opts.callback()
            }
            if(opts.passFunction!=''){
                opts.passFunction()
            }else{
                $(this).submit()
            }
        }
    }
})(jQuery);