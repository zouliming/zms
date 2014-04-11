/* 
 * 用来验证表单
 * author：liming.zou@vipshop.com
 */
(function($){
        var Validator = function(element,options){
                this.init(element,options);
        }
        Validator.prototype = {
                constructor:Validator,
                init:function(element,options){
                        this.opts = $.extend({},$.fn.va.defaults,options);
                        
                        this.checkResult = true;
                        this.checkRequire();
                        this.checkCompare();
                        if(this.checkResult==true){
                                if(this.opts.callback){
                                        this.opts.callback()
                                }
                                if(this.opts.autoSubmit==true){
                                    element.submit();
                                }else{
                                    return true;
                                }
                        }
                },
                checkRequire:function(){
                        if(this.checkResult && this.opts.required){
                              var requiredEle,e,i;
                                requiredEle = this.opts.required.ele.split(",");
                                for(i=0;i<requiredEle.length;i++){
                                        e = $(requiredEle[i]);
                                        if(e.val()==""){
                                                var msg = this.getRequiredError(e);
                                                this.showError(msg);
                                                this.checkResult = false;
                                                break;
                                        }
                                }  
                        }
                },
                checkCompare:function(){
                        if(this.checkResult && this.opts.compare){
                                var compareGroup,compareEle,tempV;
                                outerloop: for(i=0;i<this.opts.compare.length;i++){
                                        compareGroup = this.opts.compare[i];
                                        compareEle = compareGroup.ele.split(',');
                                        innerloop: for(j=0;j<compareEle.length;j++){
                                                if(j==0){
                                                        tempV = $(compareEle[j]).val();
                                                }else{
                                                        if(tempV != $(compareEle[j]).val()){
                                                                this.showError(this.opts.compare[i].errorMessage);
                                                                this.checkResult = false;
                                                                break outerloop;
                                                        }
                                                }
                                        } 
                                }
                        }
                },
                showError:function(message){
                        alert(message);
                },
                getRequiredError:function(e){
                        var errorMsg;
                        if(this.opts.required.errorAttr){
                                errorMsg = e.attr(this.opts.required.errorAttr);
                        }

                        if(errorMsg==undefined){
                                var name = e.attr('name');
                                var label = e.parentsUntil('form').find("label[for='"+name+"']");
                                if(label.length>0){
                                        errorMsg = label.html()+"不能为空";
                                }else{
                                        if(name!=undefined){
                                                errorMsg = name+"不能为空";
                                        }else{
                                                errorMsg = "不能为空";
                                        }
                                }
                        }
                        return errorMsg;
                }
        }
        $.fn.va = function(options){
                return new Validator(this,options);
        };
        
        $.fn.va.defaults = {
                /**
                * messageEle 文本框的错误提示信息的属性
                * submitEle  提交的form的表达式
                */
                'required':{
                        'ele':'',
                        'errorAttr':''
                },
                'compare':{
                        'ele':'',
                        'errorMessage':''
                },
                'callback':'',
                'autoSubmit':false
        };
        
})(jQuery);