/**
 * Created by Ninico on 2015-12-04.
 * 一个自己编写的jquery插件
 */
(function($){


    /**
     * 定义jquery组件selectTagsInput
     */
    $.fn.SelectTagsInput = function(options){

        var defaults = {

        };

        var options = $.extend(defaults,options);


        /**
         * 为选择框绑定change事件
         */
        $(options.selectionInput).bind("change",function(){
            var $this = $(this);


            if($this.val()==="-1"){
                return false;
            }



            var start_site = $(options.tagsInput);

            var start_site_dom = start_site.get(0);

            start_site_dom.temp = start_site.get(0).temp?start_site.get(0).temp:{};


            //将选中过得值存储在dom的一个属性上，可以避免重复值
            start_site_dom.temp[$this.val()] = $this.val();

            addVal(start_site_dom.temp,start_site);



        });

        //为刚生成的span标签绑定点击事件，点击后移除
        var listener = function(valObj,inputObj){

//            var valObj = $("#start_site").get(0).temp;
            $(options.tagsSelector).bind("click",function(){
                $this = $(this);

                delete valObj[$this.parent().text()];

                addVal(valObj,inputObj);
            })
        };


        listener($(options.tagsInput).get(0).temp,$(options.tagsInput));

        //按存储在temp对象上的属性，生成dom对象和拼接input的值
        var addVal = function(valObj,inputObj){

            inputObj.val("");
            $(options.tagsContainer).html("");



            for(p in valObj){

                var attr = p;

                $(options.tagsContainer).prepend('<span class="tag label label-info">' +
                    ''+valObj[attr]+'' +
                    '<span '+options.tags+'>' +
                    '</span></span>');

                if(inputObj.val() === ""){

                    inputObj.val(valObj[attr]);
                }else{

                    inputObj.val(inputObj.val() + "，" + valObj[attr]);
                }
            }

            listener(valObj,inputObj);
        }

    };




})(jQuery);