(function(){

    // namespace 'dui'
    var dui = window.dui || {}, 

    // private methods and properties.
    _id = 'dui-dialog', 
    _ids = [],
    _current_dlg = null,
    _body = "body",
    _isIE6 = ($.browser.msie && $.browser.version === '6.0')? true : false,
    _cache = {},

    //button callback. _button_callback[button id] = function.
    _button_callback = {}

    _CSS_DLG = 'dui-dialog',
    _CSS_BTN_CLOSE = 'dui-dialog-close',
    _CSS_DIV_SHD = 'dui-dialog-shd',
    _CSS_DIV_CONTENT = 'dui-dialog-content',
    _CSS_IFRM = 'dui-dialog-iframe',
    _CSS_OVER = 'dui-dialog-overlay',

    _TXT_CONFIRM = '确定',
    _TXT_CANCEL = '取消',
    _TXT_TIP = '提示',
    _TXT_LOADING = '下载中，请稍候...',

    _templ = '<div id="{ID}" class="' + _CSS_DLG + '"{CSS_ISHIDE}><span class="' + _CSS_DIV_SHD + '"></span><div class="' + _CSS_DIV_CONTENT + '">{BN_CLOSE}{TITLE}<div class="bd"></div></div></div>',
    _templ_btn_close = '<a href="#" title="关闭" class="' + _CSS_BTN_CLOSE + '">X</a>',
    _templ_title = '<div class="hd"><h3>{TITLE}</h3></div>',
    _templ_iframe = '<iframe class="' + _CSS_IFRM + '"></iframe>',
    _templ_overlay = '<div id="' + _CSS_OVER + '"></div>',

    _button_config = {
        'confirm': {
            text: _TXT_CONFIRM,
            method: function(o){
                o.close();
            }
        }, 
        'cancel': {
            text: _TXT_CANCEL,
            method: function(o){
                o.close();
            }
        } 
    },

    _default_config = {
        url: '',
        content: '',
        title: _TXT_TIP,
        width: 0,
        height: 0,
        visible: false,
        iframe: false,
        maxWidth: 960,
        cache: true,
        buttons: [],
        callback: null,
        dataType: 'text',
        isHideClose: false,
        isHideTitle: false,
        overlay: true,
        isRemove: false
    },

    // mix config setting.
    _config = function (n, d) {
        var cfg = {}, i;
        for (i in d) {
            if (d.hasOwnProperty(i)) {
                cfg[i] = n[i] || d[i];
            }
        }
        return cfg;
    },

    _formCollection = function (frm) {
        var els = frm.elements, 
        i = 0, el, data = [],
        getValue = {
            'select-one': function (el) {
                return encodeURIComponent(el.name) + '=' + encodeURIComponent(el.options[el.selectedIndex].value); 
            },
            'select-multiple': function (el) {
                var i = 0, opt, values = []; 
                for (;opt = el.options[i++];) {
                    if (opt.selected) {
                        values.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(opt.value));
                    }
                }
                return values.join('&'); 
            },
            'radio': function (el) {
                if (el.checked) {
                    return encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value); 
                }
            },
            'checkbox': function (el) {
                if (el.checked) {
                    return encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value); 
                }
            }
        };
        for (; el = els[i++]; ) {
            if (getValue[el.type]) {
                data.push(getValue[el.type](el));
            } else {
                data.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value));
            } 
        }

        return data.join('&').replace(/\&{2,}/g, '&');
    },

    dialog = function(cfg){
        var c = cfg || {};
        this.config = _config(c, _default_config);
        this.init();
    };


    dialog.prototype = {
        init: function () {
            if (!this.config) {
                return;
            }

            this.render();
            this.bind();
        },

        render: function () {
            var cfg = this.config, id = _id  + _ids.length; 

            _ids.push(id);

            $(_body).append(
                _templ.replace('{ID}', id)
                .replace('{CSS_ISHIDE}', cfg.visible ? '': ' style="visibility:hidden;top:-999em;left:-999em;"')
                .replace('{TITLE}', _templ_title.replace('{TITLE}', cfg.title))
                .replace('{BN_CLOSE}', _templ_btn_close)
                );

            this.id = id;
            this.node = $('#' + id);
            this.title = $('.hd', this.node);
            this.body = $('.bd', this.node);
            this.btnClose = $('.' + _CSS_BTN_CLOSE, this.node);
            this.shadow = $('.' + _CSS_DIV_SHD, this.node);
            this.iframe = $('.' + _CSS_IFRM, this.node);
            this.overlay = $('#' + _CSS_OVER);

            this.set(cfg);
        },

        bind: function () {
            var o = this;

            $(window).bind({
                resize: function () {
                    if (_isIE6) {
                        return;
                    }
                    o.updatePosition();
                },
                scroll: function () {
                    if (!_isIE6) {
                        return;
                    }
                    o.updatePosition();
                }
            });

            this.btnClose.click(function(e){
                o.close();
                e.preventDefault();
            });

            $(_body).keypress(function(e){
                if (e.keyCode === 27) {
                    o.close();
                }
            });
        },

        updateSize: function (){
            var w = this.node.width(), h,
            screen_height = $(window).height(), 
            cfg = this.config; 

            $('.bd', this.node).css({
                'height': 'auto',
                'overflow-x': 'visible',
                'overflow-y': 'visible'
            });  

            h = this.node.height();

            if (w > cfg.maxWidth) {
                w = cfg.maxWidth;
                this.node.css('width', w + 'px');
            }

            if (h > screen_height) {
                $('.bd', this.node).css({
                    'height': (screen_height - 150) + 'px',
                    'overflow-x': 'hidden',
                    'overflow-y': 'auto'
                });  
            }

            h = this.node.height();

            this.shadow.width(w + 16).height(h + 16);
            this.iframe.width(w + 16).height(h + 16);
        },

        updatePosition: function () {
            var w = this.node.width(), h = this.node.height(), win = $(window), t = _isIE6 ? win.scrollTop() : 0; 
            this.node.css({
                left: Math.floor(win.width() / 2 - w / 2 - 8) + 'px',
                top: Math.floor(win.height() / 2 - h / 2 -30) + t + 'px'
            });
        },

        set: function (cfg) {
            var title, 
            close, 
            html_str, 
            el, 
            id = this.id, 
            num = [], 
            that = this, 
            genId = function (str) {
                num.push(0);
                return id + '-' + str + '-' + num.length; 
            };

            if (!cfg) {
                return;
            }

            // set width and height.
            if (cfg.width) {
                this.node.css('width', cfg.width + 'px');
                this.config.width = cfg.width;
            }

            if (cfg.height) {
                this.node.css('height', cfg.width + 'px');
                this.config.height = cfg.height;
            }

            // set buttons
            if ($.isArray(cfg.buttons) && cfg.buttons[0]) {
                el = $('.ft', this.node);
                html_str = [];

                $(cfg.buttons).each(function(){
                    var bn = arguments[1], bnId = genId('bn');
                    if (typeof bn === 'string' && _button_config[bn]) {
                        html_str.push('<span class="bn-flat"><input type="button" id="' + bnId + '" class="' + _id + '-bn-' + bn + '" value="' + _button_config[bn].text + '"></span> ');
                        _button_callback[bnId] = _button_config[bn].method;
                    } else {
                        html_str.push('<span class="bn-flat"><input type="button" id="' + bnId + '" class="' + _id + '-bn" value="' + bn.text + '"></span> ');
                        _button_callback[bnId] = bn.method;
                    }
                });

                if (!el[0]) {
                    el = this.body.parent().append('<div class="ft">' + html_str.join('') + '</div>'); 
                } else {
                    el.html(html_str.join(''));
                }

                // bind event.
                $('.ft input', this.node).click(function(e){
                    var func = _button_callback[this.id];
                    if (func) {
                        func(that);
                    }
                });

                this.footer = $('.ft', this.node);
            } else {
                this.footer = $('.ft', this.node);
                this.footer.html('');
            }


            // set hidden close button
            if (typeof cfg.isHideClose !== 'undefined') {
                if (cfg.isHideClose) {
                    this.btnClose.hide();
                } else {
                    this.btnClose.show();
                }
                this.config.isHideClose = cfg.isHideClose;
            }

            // set hidden title 
            if (typeof cfg.isHideTitle !== 'undefined') {
                if (cfg.isHideTitle) {
                    this.title.hide();
                } else {
                    this.title.show();
                }
                this.config.isHideTitle = cfg.isHideTitle;
            }

            // set title.
            if (cfg.title) {
                this.setTitle(cfg.title);
                this.config.title = cfg.title;
            }

            // set enable iframe
            if (typeof cfg.iframe !== 'undefined') {
                if (!cfg.iframe) {
                    this.iframe.hide();
                } else if (!this.iframe[0]) {
                    this.node.prepend(_templ_iframe);
                    this.iframe = $('.' + _CSS_IFRM, this.node);
                } else {
                    this.iframe.show();
                }
                this.config.iframe = cfg.iframe;
            }
      
            // set enable overlay
            if(typeof cfg.overlay !== 'undefined') {
                if (!cfg.overlay) {
                    this.overlay.hide();
                } else if (!this.overlay[0]) {
                    this.node.before(_templ_overlay);
                    this.overlay = $('#' + _CSS_OVER);
                    if(_isIE6) this.overlay.css({
                        height: $(_body).height() + 'px'
                        });
                } else {
                    this.overlay.show();
                }
                this.config.overlay = cfg.overlay;
            }

            // set content.
            if (cfg.content) {
                this.body.html(typeof cfg.content === 'object' ? $(cfg.content).html() : cfg.content );
                this.config.content = cfg.content;
            }


            // fetch content by URL.
            if (cfg.url) {
                if (cfg.cache && _cache[cfg.url]) {
                    if (cfg.dataType === 'text' || !cfg.dataType) {
                        this.setContent(_cache[cfg.url]);
                    }
                    if (cfg.callback) {
                        cfg.callback(_cache[cfg.url], this);
                    }
                } else if (cfg.dataType === 'json') {
                    this.setContent(_TXT_LOADING);
                    if (this.footer) {
                        this.footer.hide();
                    }
                    $.getJSON(cfg.url, function (data) {
                        that.footer.show();
                        _cache[cfg.url] = data;
                        if (cfg.callback) {
                            cfg.callback(data, that);
                        }
                    });
                } else {
                    this.setContent(_TXT_LOADING);
                    if (this.footer) {
                        this.footer.hide();
                    }
                    $.ajax({
                        url: cfg.url,
                        success: function (content) {
                            _cache[cfg.url] = content;
                            if (that.footer) {
                                that.footer.show();
                            }
                            that.setContent(content);
                            if (cfg.callback) {
                                cfg.callback(content, that);
                            }
                        }
                    });
                }
            }


            this.updateSize();
            this.updatePosition();

            return this;
        },
    
        setOverlay: function() {
            this.overlay.css({
                height: jQuery(document).height()
            });
        },

        update: function(){
            this.updateSize();
            this.updatePosition();
            return this;
        },

        setContent: function (str) {
            this.body.html(str);
            this.updateSize();
            this.updatePosition();
            return this;
        },

        setTitle: function (str) {
            $('h3',this.title).html(str);
            return this;
        },

        // submit form in dialog
        submit: function(callback) {
            var that = this, 
            frm = $('form', this.node);
            frm.submit(function(e){
                e.preventDefault();

                var url = this.getAttribute('action', 2), 
                type = this.getAttribute('method') || 'get',
                data = _formCollection(this);

                $[type.toLowerCase()](url, data, function (da) {
                    if (callback) {
                        callback(da);
                    }
                }, 'json');
            });

            frm.submit();
        },

        open: function () {
            this.overlay.show();
            this.node.css('visibility', 'visible');
            this.node.show();
            return this;
        },

        close: function () {
            if(this.config.isRemove){
                this.node.remove();
                _ids.pop();
            }else{
                this.node.hide();
            }
            var arr = $.grep($("." + _CSS_DLG),function(n){
                return $(n).css("display") != "none";
            });
            this.overlay.hide();
            return this;
        }
    };


    // add to dui
    dui.Dialog = function (cfg, isMulti) {
        // use sigleton dialog mode by default.
        if (!isMulti && _current_dlg) {
            return cfg? _current_dlg.set(cfg) : _current_dlg;
        }

        if (!_current_dlg && !isMulti) {
            _current_dlg = new dialog(cfg);
            return _current_dlg;
        }

        if (isMulti) $.extend(cfg, {
            isRemove: true
        });

        return new dialog(cfg);
    };
  
    dui.hideDialog = function(){
        if (_current_dlg) _current_dlg.close();
    };


    window.dui = dui;

})();
/* select */
(function($){
    $.fn.extend({
        sSelect: function(options) {
            settings = $.extend({
                title: true,
                width: null
            }, options);
            return this.each(function(i,obj){
                var selectId = (this.name||this.id)+'__jQSelect'+i||'__jQSelect'+i, selt = this, selected = $('option:selected',this);
                if(obj.style.display != 'none' && $(this).parents()[0].id.indexOf('__jQSelect')<0){
                    var tabindex = this.tabIndex||0;
                    $(this).before("<div class='dropdown' id="+selectId+" tabIndex="+tabindex+"></div>");
                    var selectZindex = $(this).css('z-index'),selectIndex = $('option',this).index(selected[0]);
                    $('#'+selectId).append('<div class="dropselectbox"><h4></h4><ul><li>111</li></ul></div>');
                    $('#'+selectId+' h4').empty().append(selected.text());
                    var selectWidth=(settings.width != null) ? settings.width : $(this).width();
                    //if($.browser.safari){selectWidth = selectWidth+15}
                    if(settings.title) $('#'+selectId+' h4').css({
                        width:selectWidth
                    });
                    var selectUlwidth = selectWidth + parseInt($('#'+selectId+' h4').css("padding-left")) + parseInt($('#'+selectId+' h4').css("padding-right"));
                    $('#'+selectId+' ul').css({
                        width:selectUlwidth+'px'
                        });
                    $(this).hide();
                    $('#'+selectId+' div').hover(function(){
                        $('#'+selectId+' h4').addClass("over");
                    },function(){
                        $('#'+selectId+' h4').removeClass("over");
                    });
                    $('#'+selectId)
                    .bind("focus",function(){
                        $.fn.clearSelectMenu(selectId,selectZindex);
                        $('#'+selectId+' h4').addClass("over");
                    })
                    .bind("click",function(e){
                        if($('#'+selectId+' ul').css("display") == 'block'){
                            $.fn.clearSelectMenu(selectId,selectZindex);
                            return false;
                        }else{
                            $('#'+selectId+' h4').addClass("current");
                            $('#'+selectId+' ul').show();
                            var selectZindex = $(this).css('z-index');
                            //if ($.browser.msie || $.browser.opera){$('.dropdown').css({'position':'relative','z-index':'0'});}
                            $('#'+selectId).css({
                                'position':'relative',
                                'z-index':'999'
                            });
                            $.fn.setSelectValue(selectId,selt);
                            selectIndex = $('#'+selectId+' li').index($('.selectedli')[0]);
                            var windowspace = ($(window).scrollTop() + document.documentElement.clientHeight) - $(this).offset().top;
                            var ulspace = $('#'+selectId+' ul').outerHeight(true);
                            var windowspace2 = $(this).offset().top - $(window).scrollTop() - ulspace;
                            windowspace < ulspace && windowspace2 > 0?$('#'+selectId+' ul').css({
                                top:-ulspace
                                }):$('#'+selectId+' ul').css({
                                top:$('#'+selectId+' h4').outerHeight(true)
                                });
                            $(window).scroll(function(){
                                windowspace = ($(window).scrollTop() + document.documentElement.clientHeight) - $('#'+selectId).offset().top;
                                windowspace < ulspace?$('#'+selectId+' ul').css({
                                    top:-ulspace
                                    }):$('#'+selectId+' ul').css({
                                    top:$('#'+selectId+' h4').outerHeight(true)
                                    });
                            });	
                            $('#'+selectId+' li').click(function(e){
                                selectIndex = $('#'+selectId+' li').index(this);
                                $.fn.keyDown(selectId,selectIndex,selt);
                                $('#'+selectId+' h4').empty().append($('option:selected',selt).text());
                                $.fn.clearSelectMenu(selectId,selectZindex);
                                e.stopPropagation();
                                e.cancelbubble = true;
                            })
                            .hover(
                                function(){
                                    $('#'+selectId+' li').removeClass("over");
                                    $(this).addClass("over").addClass("selectedli");
                                    selectIndex = $('#'+selectId+' li').index(this);
                                },
                                function(){
                                    $(this).removeClass("over");
                                }
                                );
                        };
                        e.stopPropagation();
                    })
                    .bind("dblclick", function(){
                        $.fn.clearSelectMenu(selectId,selectZindex);
                        return false;
                    })
                    .bind("blur",function(){
                        $.fn.clearSelectMenu(selectId,selectZindex);
                        return false;
                    })
                    .bind("selectstart",function(){
                        return false;
                    });
                }else if($(this).prev()[0].id.indexOf('__jQSelect')>0){
                    selectId = $(this).prev()[0].id;
                    $.fn.setSelectValue(selectId, this);
                    var selectWidth=$(this).width();
                    //if($.browser.safari){selectWidth = selectWidth+15}
                    $('#'+selectId+' h4').css({
                        width:selectWidth
                    });
                    var selectUlwidth = selectWidth + parseInt($('#'+selectId+' h4').css("padding-left")) + parseInt($('#'+selectId+' h4').css("padding-right"));
                    $('#'+selectId+' ul').css({
                        width:selectUlwidth+'px'
                        });
                    if(this.style.display != 'none'){
                        $(this).hide();
                    }
                }
            })
        },
    clearSelectMenu:function(selectId,selectZindex){
        if(typeof selectId !== 'undefined'){
            selectZindex = selectZindex || 'auto';
            $('#'+selectId+' ul').empty().hide();
            $('#'+selectId+' h4').removeClass("over").removeClass("current");
            //if(typeof selectZindex != 'undefined' && selectZindex != "auto") $('#'+selectId).css({'z-index':selectZindex});
            $('#'+selectId).css({
                'position':'static'
            });
        }
    },
    setSelectValue:function(sID,sel){
        var content = [];
        $.each($('option',sel), function(i){
            content.push("<li class='FixSelectBrowser'>"+$(this).text()+"</li>");
        });
        content = content.join('');
        $('#'+sID+' ul').html(content);
        $('#'+sID+' h4').html($('option:selected',sel).text());
        $('#'+sID+' li').eq($(sel)[0].selectedIndex).addClass("over").addClass("selectedli");
    },
    keyDown:function(sID,selectIndex,sel){
        var $obj = $(sel);
        $obj[0].selectedIndex = selectIndex;
        $obj.change();
        $('#'+sID+' li:eq('+selectIndex+')').toggleClass("over");
        $('#'+sID+' h4').html($('option:selected',sel).text());
    }
    });
})(jQuery);

function dropdown_sel(obj){
    var typearr = jQuery(obj).val().split('_');
    var type = typearr[0];
    if(type == 'musicbox'){
        type += '&op=add';
    }
    var id = typearr[1];
    var url = "music.php?ac="+type+"&id="+id;
    ajaxmenu2(url);
}


//confirm框
(function(){
    window.VPFbox = {
        okFun:function(){},
        cancleFun:function(){},
        confirm:function(msg, okFun, cancleFun, title1,title2){
			
            if(typeof okFun == 'function'){
                this.okFun = okFun;
            }

            if(typeof cancleFun == 'function'){
                this.cancleFun = cancleFun;
            }
			
            if(arguments.length == 5){
                var title1 = title1;
                var title2 = title2;
            }else{
                var title1 = '是';
                var title2 = '否';
            }
            Do('dialog',function(){
                dui.Dialog({
                    title: '提示',
                    content: '<div class="cus_save_succ"> <div class="succ_l"><i class="ibg1">&nbsp;</i></div> <div class="succ_r"> <ul> <li>'+msg+'</li> <li><button class="btn btn-warning" onclick="VPFbox.callOk()">'+title1+'</button>&nbsp;&nbsp;<button onclick="VPFbox.callCancle()" class="btn">'+title2+'</button></li> </ul> </div> </div>',
                    width: 260
                }).open();
            });				
        },
        callOk:function(){
            this.okFun();
            $('.dui-dialog-close').click();
        },
        callCancle:function(){
            this.cancleFun();
            $('.dui-dialog-close').click();
        },
        alert:function(msg, callback, buttontitle){
            if(typeof callback == 'function'){
                this.okFun = callback;
                if(arguments.length == 3){
                    var btTitle = buttontitle;
                }else{
                    var btTitle = '确定';
                }
                var content='<div class="cus_save_succ"> <div class="succ_l"><i class="ibg1">&nbsp;</i></div> <div class="succ_r"> <ul> <li>'+msg+'</li> <li><button onclick="VPFbox.callOk()" title="'+btTitle+'" class="btn btn-warning">'+btTitle+'</button></li> </ul> </ul></div></div>'; 
            }else{
                var content='<div class="cus_save_succ"> <div class="succ_l"><i class="ibg1">&nbsp;</i></div> <div class="succ_r"> <ul> <li>'+msg+'</li></ul></div></div>'; 
            }
            Do('dialog',function(){
                dui.Dialog({
                    title: '提示',
                    content: content,
                    width:260
                }).open();
            });
        },
        iframe:function(src,title, width, height, scroll) {
            if(arguments.length == 5){
                var scroll = scroll;
            }else{
                var scroll = 'no';
            }
            Do('dialog',function(){
                dui.Dialog({
                    title: title,
                    content : "<iframe allowTransparency='true' name='codeHandler' src='"+src+"' width='"+width+"' height='"+height+"'  frameborder='0' scrolling='"+scroll+"' marginwidth='0' marginheight='0'></iframe>",
                    width:width+15
                }).open();
            });
            $(".dui-dialog").easydrag();
        }
    }
})();

(function(){
    var a=document,i={},e={},h=function(k){
        return k.constructor===Array
        },c=function(k){
        if(window.console&&window.console.log){
            window.console.log(k)
            }
        },g={
    core_lib:[],
    mods:{}
},j=a.getElementsByTagName("script")[0],d=function(l,p,r,k,o){
    if(!l){
        return
    }
    if(i[l]){
        e[l]=false;
        if(k){
            k(l,o)
            }
            return
    }
    if(e[l]){
        setTimeout(function(){
            d(l,p,r,k,o)
            },1);
        return
    }
    e[l]=true;
    var q,m=p||l.toLowerCase().substring(l.lastIndexOf(".")+1);
    if(m==="js"){
        q=a.createElement("script");
        q.setAttribute("type","text/javascript");
        q.setAttribute("src",l);
        q.setAttribute("async",true)
        }else{
        if(m==="css"){
            q=a.createElement("link");
            q.setAttribute("type","text/css");
            q.setAttribute("rel","stylesheet");
            q.setAttribute("href",l);
            i[l]=true
            }
        }
    if(r){
    q.charset=r
    }
    if(m==="css"){
    j.parentNode.insertBefore(q,j);
    if(k){
        k(l,o)
        }
        return
}
q.onload=q.onreadystatechange=function(){
    if(!this.readyState||this.readyState==="loaded"||this.readyState==="complete"){
        i[this.getAttribute("src")]=true;
        if(k){
            k(this.getAttribute("src"),o)
            }
            q.onload=q.onreadystatechange=null
        }
    };

j.parentNode.insertBefore(q,j)
},b=function(r){
    if(!r||!h(r)){
        return
    }
    var n=0,q,l=[],p=g.mods,k=[],m={},o=function(v){
        var u=0,s,t;
        if(m[v]){
            return k
            }
            m[v]=true;
        if(p[v].requires){
            t=p[v].requires;
            for(;s=t[u++];){
                if(p[s]){
                    o(s);
                    k.push(s)
                    }else{
                    k.push(s)
                    }
                }
            return k
        }
        return k
    };
    
for(;q=r[n++];){
    if(p[q]&&p[q].requires&&p[q].requires[0]){
        k=[];
        m={};
        
        l=l.concat(o(q))
        }
        l.push(q)
    }
    return l
},f=function(k){
    if(!k||!h(k)){
        return
    }
    this.queue=k;
    this.current=null
    };
    
f.prototype={
    _interval:10,
    start:function(){
        var k=this;
        this.current=this.next();
        if(!this.current){
            this.end=true;
            return
        }
        this.run()
        },
    run:function(){
        var m=this,k,l=this.current;
        if(typeof l==="function"){
            l();
            this.start();
            return
        }else{
            if(typeof l==="string"){
                if(g.mods[l]){
                    k=g.mods[l];
                    d(k.path,k.type,k.charset,function(n){
                        m.start()
                        },m)
                    }else{
                    if(/\.js|\.css/i.test(l)){
                        d(l,"","",function(n,p){
                            p.start()
                            },m)
                        }else{
                        this.start()
                        }
                    }
            }
    }
},
next:function(){
    return this.queue.shift()
    }
};

this.Do=function(){
    var l=Array.prototype.slice.call(arguments,0),k=new f(b(g.core_lib.concat(l)));
    k.start()
    };
    
this.Do.add=function(l,k){
    if(!l||!k||!k.path){
        return
    }
    g.mods[l]=k
    };
    
Do(g.core_lib)
})();

/**
 *---------------------------------------------------------------
 * EasyDrag
 *---------------------------------------------------------------
 * @auth break
 * @create 2012
 * @link http://www.shizuwu.cn
 */
/**
* EasyDrag 1.4 - Drag & Drop jQuery Plug-in
*
* Thanks for the community that is helping the improvement
* of this little piece of code.
*
* For usage instructions please visit http://fromvega.com
*/

(function($){

    // to track if the mouse button is pressed
    var isMouseDown    = false;

    // to track the current element being dragged
    var currentElement = null;

    // callback holders
    var dropCallbacks = {};
    var dragCallbacks = {};

    // global position records
    var lastMouseX;
    var lastMouseY;
    var lastElemTop;
    var lastElemLeft;
	
    // track element dragStatus
    var dragStatus = {};	

    // returns the mouse (cursor) current position
    $.getMousePosition = function(e){
        var posx = 0;
        var posy = 0;

        if (!e) var e = window.event;

        if (e.pageX || e.pageY) {
            posx = e.pageX;
            posy = e.pageY;
        }
        else if (e.clientX || e.clientY) {
            posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
            posy = e.clientY + document.body.scrollTop  + document.documentElement.scrollTop;
        }

        return {
            'x': posx, 
            'y': posy
        };
    };

    // updates the position of the current element being dragged
    $.updatePosition = function(e) {
        var pos = $.getMousePosition(e);

        var spanX = (pos.x - lastMouseX);
        var spanY = (pos.y - lastMouseY);

        $(currentElement).css("top",  (lastElemTop + spanY));
        $(currentElement).css("left", (lastElemLeft + spanX));
    };

    // when the mouse is moved while the mouse button is pressed
    $(document).mousemove(function(e){
        if(isMouseDown && dragStatus[currentElement.id] == 'on'){
            // update the position and call the registered function
            $.updatePosition(e);
            if(dragCallbacks[currentElement.id] != undefined){
                dragCallbacks[currentElement.id](e, currentElement);
            }

            return false;
        }
    });

    // when the mouse button is released
    $(document).mouseup(function(e){
        if(isMouseDown && dragStatus[currentElement.id] == 'on'){
            isMouseDown = false;
            if(dropCallbacks[currentElement.id] != undefined){
                dropCallbacks[currentElement.id](e, currentElement);
            }

            return false;
        }
    });

    // register the function to be called while an element is being dragged
    $.fn.ondrag = function(callback){
        return this.each(function(){
            dragCallbacks[this.id] = callback;
        });
    };

    // register the function to be called when an element is dropped
    $.fn.ondrop = function(callback){
        return this.each(function(){
            dropCallbacks[this.id] = callback;
        });
    };
	
    // stop the element dragging feature
    $.fn.dragOff = function(){
        return this.each(function(){
            dragStatus[this.id] = 'off';
        });
    };
	
	
    $.fn.dragOn = function(){
        return this.each(function(){
            dragStatus[this.id] = 'on';
        });
    };

    // set an element as draggable - allowBubbling enables/disables event bubbling
    $.fn.easydrag = function(allowBubbling){

        return this.each(function(){

            // if no id is defined assign a unique one
            if(undefined == this.id || !this.id.length) this.id = "easydrag"+(new Date().getTime());

            // set dragStatus 
            dragStatus[this.id] = "on";
			
            // change the mouse pointer
            $(this).css("cursor", "move");

            // when an element receives a mouse press
            $(this).mousedown(function(e){

                // set it as absolute positioned
                $(this).css("position", "absolute");

                // set z-index
                $(this).css("z-index", "10000");

                // update track variables
                isMouseDown    = true;
                currentElement = this;

                // retrieve positioning properties
                var pos    = $.getMousePosition(e);
                lastMouseX = pos.x;
                lastMouseY = pos.y;

                lastElemTop  = this.offsetTop;
                lastElemLeft = this.offsetLeft;

                $.updatePosition(e);

                return allowBubbling ? true : false;
            });
        });
    };

})(jQuery);