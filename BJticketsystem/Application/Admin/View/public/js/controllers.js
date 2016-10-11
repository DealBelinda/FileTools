var checkForm = function(){
    if(!$(".form-horizontal").get(0).checkValidity()){
        alert("请填写完所有选项！");
        return false;
    }

    return true;
}

aihuijia.controller("aihuijiaCtrl",function($scope,$http,$timeout){

    $scope.doDelete = function(id,url,fn){
        $(".loading-container").removeClass("loading-inactive");

        $http.get(url,{params:{"id":id}}).success(function(data){
            $(".loading-container").addClass("loading-inactive");
            Ninico.changeResultInfo($scope,data);
            fn();
        });

    };

    $scope.resultInfo = {
        statusinfo: "请求成功",
        resultinfo : "删除成功！",
        status: "success",
        openflag: false
    };

    $scope.getResultStatus = function(){

        if($scope.resultInfo.status == "success"){

            return true;
        }else if($scope.resultInfo.status == "error"){

            return false;
        }

    };

    $scope.hideResultInfo = function(){

        $scope.resultInfo.openflag = false;
    };

    $scope.$on("changeResultInfo",function(e,sendinfo){

        for(p in sendinfo){

            $scope.resultInfo[p] = sendinfo[p];
        };

        if($scope.getResultStatus()){

            $scope.resultInfo.statusinfo = "请求成功";
        }else if(!$scope.getResultStatus()){

            $scope.resultInfo.statusinfo = "请求失败";
        }

    });

    $scope.sendAutoAddRequset = function(){
    
       $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Division/autoAddDivision").success(function(data){
    
           Ninico.changeResultInfo($scope,data)
       });
    }
    
    
    $scope.autoAddDivision = function(){
    
       var openDate = new Date().getDay();
    
       $scope.sendAutoAddRequset();
    
       $timeout(function(){
    
           var newDate = new Date().getDay();
    
           console.log(openDate == newDate);
           if(openDate != newDate){
    
               openDate = newDate;
               $scope.sendAutoAddRequset();
           }
    
           $timeout(arguments.callee,1800000)
       },1000)
    
    };
    
    $scope.autoAddDivision();


});

siteModule.controller("SiteCtrl",function($scope,$http, $location){

    $scope.newSite = {};
    $scope.editSite = {};

    $scope.getdata = function(){
        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Site/checkSiteList").success(function(data){
            $(".loading-container").addClass("loading-inactive");
            $scope.sitesinfo = data.result;
        });
    };

    $scope.getdata();


    $(".loading-container").removeClass("loading-inactive");
    $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Site/checkParentSite").success(function(data){
        $(".loading-container").addClass("loading-inactive");

        $scope.parentSitesinfo = data.result;

    });

    $scope.toEdit= function(siteInfo){


        $scope.editSite = siteInfo;

        console.log(siteInfo);

    };

    $scope.doEditSite = function(){

        if(!checkForm())return;

        $(".loading-container").removeClass("loading-inactive");
        $location.path("/site");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Site/changeSiteinfo",{
            params: {
                pid: $scope.editSite.pid,
                name: $scope.editSite.name,
                id: $scope.editSite.id
            }
        }).success(function(data){
            Ninico.changeResultInfo($scope,data);
            $(".loading-container").addClass("loading-inactive");
        });
    };


    $scope.doNewSite = function(siteInfo){

        if(!checkForm())return;
        $(".loading-container").removeClass("loading-inactive");
        $location.path("/site");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Site/addSite",{
            params:{
                pid:$scope.newSite.pid,
                name:$scope.newSite.name
            }
        }).success(function(data){

            Ninico.changeResultInfo($scope,data);
            $(".loading-container").addClass("loading-inactive");

            $scope.getdata();

        });
    };

});


busModule.controller("BusCtrl",function($scope,$http, $location){

    $scope.newBus = {};

    $scope.getdata = function(){
        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Businfo/checkBusinfoList").success(function(data){

            $(".loading-container").addClass("loading-inactive");
            $scope.bussinfo = data.result;
        });
    };
    $scope.getdata();


    $scope.doShop = function(id){
        $(".loading-container").removeClass("loading-inactive");

        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Businfo/stopBusStatus",{
            params:{"id":id}
        }).success(function(data){

            $scope.getdata();
            Ninico.changeResultInfo($scope,data);
            $(".loading-container").addClass("loading-inactive");
        })
    };

    $scope.doStart = function(id){

        $(".loading-container").removeClass("loading-inactive");

        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Businfo/BeginBusStatus",{
            params:{"id":id}
        }).success(function(data){

            $scope.getdata();
            Ninico.changeResultInfo($scope,data);
            $(".loading-container").addClass("loading-inactive");
        })
    };

    $scope.toEdit = function(businfo){
        $scope.editBus = businfo;
    };

    $scope.doNewBus = function(){

        if(!checkForm())return;
        $location.path("/bus");
        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Businfo/addBusinfo",{
            params : $scope.newBus
        }).success(function(data){
            $(".loading-container").addClass("loading-inactive");

            Ninico.changeResultInfo($scope,data);
            $scope.getdata();

        })
    };

    $scope.doEditBus = function(){

        if(!checkForm())return;
        $location.path("/bus");
        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Businfo/changeBusinfo",{
            params: $scope.editBus
        }).success(function(data){
            $(".loading-container").addClass("loading-inactive");

            Ninico.changeResultInfo($scope,data);

        });
    }
});

/**
 * 路线管理控制器
 *
 */
divisionModule.controller("DivisionCtrl",function($scope,$http, $location,$timeout,$filter){

    $scope.startSationsInfo = [];
    $scope.newDivision = {};

    //通过巴士id来筛选路线所需要使用的变量，默认值是-1，代表不筛选
    $scope.saixuanBusid = -1;

    //分页需要用到的变量
    $scope.page = 0;
    $scope.total = 0;
    $scope.pagesArr = [];

    $scope.Math = Math;

    $scope.params = {};

    $scope.params.date = $filter("date")(new Date(),"yyyy-MM-dd");

    //按路线查看该路线的售票情况
    $scope.getSellStatus = function(id){

        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Division/checkUserordersByDivision",{params:{"divisionid":id}}).success(function(data){
            $(".loading-container").addClass("loading-inactive");
            $scope.ordersInfo = data;
        });
    };

    $scope.clearPage = function(){

        $scope.page = 0;
    };

    $scope.changeDate = function(){

        $scope.params.date = $("#id-date-picker-1").val();
    }


    //更新路线表单数据
    $scope.getdata = function(){

        $scope.params.page = $scope.page + 1;
        $scope.params.start = $scope.page * 15;
        $scope.params.limit = 15;
        $scope.params.busid = $("#busid").val();


        console.log($scope.params.date);

        if($scope.params.date === ""){

            delete $scope.params.date;
        }


        console.log($scope.params.busid);
        if($scope.params.busid == -1){

            delete $scope.params.busid;
        }


        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Division/checkDivisionList",{
            params:$scope.params
        }).success(function(data){
            $(".loading-container").addClass("loading-inactive");

            Ninico.pageinator($scope,data);
            $scope.divisions = data.result;
        });
    };

    $scope.getdata();


    $scope.clearDate = function(){

        $scope.params.date = null;

        $scope.getdata();
    };


    //按下分页中的按钮，更新数据
    $scope.update = function(e){

        if(e){
            $scope.page = e-1;
        }else{
            $scope.page = 0;
        }

        console.log(!e);

        $scope.getdata();
    };


    /**
     *
     * 数据初始化部分，获得路线模块中要使用的，巴士信息和站点信息，只执行一次
     */
    $(".loading-container").removeClass("loading-inactive");
    $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Site/checkSiteList").success(function(data){
        $(".loading-container").addClass("loading-inactive");

        $scope.Sitesinfo = data.result;
    });

    $(".loading-container").removeClass("loading-inactive");
    $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Businfo/checkBusinfoList").success(function(data){
        $(".loading-container").addClass("loading-inactive");
        $scope.bussinfo = data.result;
    });

    /***************************************************************************/


    //当angular 的repeat渲染结束后，执行ninico编程的jquery插件，初始化功能和样式
    $scope.initSelectInput = function($last,role){

        console.log($last);

        if($last){

            $timeout(function(){


                //--Bootstrap Date Picker--
                $('.date-picker').datepicker();

                $("#start_site_select").SelectTagsInput({selectionInput:"#start_site_select",tagsInput:"#start_site",tagsSelector:"[data-role=remove]",tags:"data-role=remove",tagsContainer:".startSite-tagsinput"});

                $("#reach_site_select").SelectTagsInput({selectionInput:"#reach_site_select",tagsInput:"#reach_site",tagsSelector:"[data-role=remove]",tags:"data-role=remove",tagsContainer:".reachSite-tagsinput"});


            });
        }
    };


    $scope.initDate = function(){

        $('.date-picker').datepicker();
    };



    //当angular的repeat渲染结束后，执行bootstrap的timepicker组件
    $scope.initTimepicker = function($last){

        if($last){

            $timeout(function(){

                //--Bootstrap Time Picker--
                $('.timepicker1').timepicker();
            });
        }
    };



    $scope.addStartSation = function(){


        var tempObj = new Object();
        tempObj.name = $scope.startsation;
        tempObj.time = "";

        $scope.startSationsInfo.push(tempObj);

        console.log($scope.startSationsInfo);

    };


    //更新路线
    $scope.doNewDivision = function(){

        if(!checkForm())return;
        $location.path("/division");


        $scope.newDivision.divisionDetail = angular.toJson($scope.divisionStart);

        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Division/addDivision",{
            params:$scope.newDivision
        }).success(function(data){
            $(".loading-container").addClass("loading-inactive");

            Ninico.changeResultInfo($scope,data);
            $scope.getdata();
        });
    };

    //更新路线细节预备方法
    $scope.toNewDivisionDetail = function(){

        if(!checkForm())return;
        $scope.newDivision.date = angular.element("#id-date-picker-1").val();

        $scope.newDivision.reach_site = angular.element("#reach_site").val();
        $scope.newDivision.start_site = angular.element("#start_site").val();

        var startSiteArr = (angular.element("#start_site").val()).split("，");

        $scope.divisionStart = [];

        for(var i = 0,len = startSiteArr.length; i < len ; i++){

            var tempObj = new Object();
            tempObj.name = startSiteArr[i];
            tempObj.time = "";

            $scope.divisionStart.push(tempObj);
        }

    };

    //使用编辑功能的时候初始化，ninico编写的selectinput组件功能和样式
    $scope.initEditSelectInput = function(arr,inputVal,temp,tagsInput){
        for(var i=0,len = arr.length;i<len;i++){

            temp[arr[i]] = arr[i];

            if(inputVal.val()==""){

                inputVal.val(arr[i]);
            }else {

                inputVal.val(inputVal.val() + "，" +arr[i]);
            }



            console.log("ninico");

            $(tagsInput).prepend('<span class="tag label label-info">' +
                ''+arr[i]+'' +
                '<span data-role=remove>' +
                '</span></span>');
        }

        //--Bootstrap Date Picker--
        $('.date-picker').datepicker();
    };

    //使用编辑功能的时候初始化，ninico编写的selectinput组件功能和样式
    $scope.drawSpan = function(){

        //for($scope.edit.start_site)
        var arr = $scope.editDivision.start_site.split("，");
        //
        //console.log($scope.editDivision.start_site);
        //
        var inputValS = $("#start_site");
        var tempS = inputValS.get(0).temp = {};

        var inputValR = $("#reach_site");
        var tempR = inputValR.get(0).temp = {};

        $scope.initEditSelectInput($scope.editDivision.start_site.split("，"),inputValS,tempS,".startSite-tagsinput");
        $scope.initEditSelectInput($scope.editDivision.reach_site.split("，"),inputValR,tempR,".reachSite-tagsinput");

        $("#start_site_select").SelectTagsInput({selectionInput:"#start_site_select",tagsInput:"#start_site",tagsSelector:"[data-role=remove]",tags:"data-role=remove",tagsContainer:".startSite-tagsinput"});

        $("#reach_site_select").SelectTagsInput({selectionInput:"#reach_site_select",tagsInput:"#reach_site",tagsSelector:"[data-role=remove]",tags:"data-role=remove",tagsContainer:".reachSite-tagsinput"});


    };


    //编辑的预备方法
    $scope.toEdit = function(divisioninfo){

        $scope.editDivision = divisioninfo;



    };

    //编辑路线细节的预备方法
    $scope.toEditDivisionDetail = function(){

        $location.path("division/editDivision/editDivisionDetail");

        $scope.editDivision.date = angular.element("#id-date-picker-1").val();

        $scope.editDivision.start_site = angular.element("#start_site").val();
        $scope.editDivision.reach_site = angular.element("#reach_site").val();

        $scope.editStartSation = [];

        $scope.editDivision.divisionDetailsDeleteid = null;

        var flag = true;

        var deleteId = '';

        var startSiteArr = $scope.editDivision.start_site.split("，");

        for(var i = 0,len = startSiteArr.length; i < len ; i++){

            var tempObj = new Object();
            tempObj.name = startSiteArr[i];
            tempObj.time = "";

            $scope.editStartSation.push(tempObj);
        }

        var editDivisionDetails = $scope.editDivision.Divisiondetails;

        console.log(editDivisionDetails.length);

        //整理上个页面传来的参数
        for(var i = 0, dlen = editDivisionDetails.length; i < dlen ; i++){


            for(var j = 0, slen = $scope.editStartSation.length; j < slen; j++){

                console.log(editDivisionDetails[i],$scope.editStartSation[j]);

                if(editDivisionDetails[i].sitename == $scope.editStartSation[j].name){

                    $scope.editStartSation[j].time = editDivisionDetails[i].time;
                    $scope.editStartSation[j].id = editDivisionDetails[i].id;
                    flag = false;
                }
            }

            if(flag){

                deleteId = deleteId +  editDivisionDetails[i].id + ',';
            }
            flag = true;
        };

        if(deleteId != ''){

            deleteId = deleteId.slice(0,-1);
        }

        console.log(deleteId);

        $scope.editDivision.divisionDetailsDeleteid = deleteId;


    };

    //编辑路线执行方法
    $scope.doEditDivision = function(){

        if(!checkForm())return;
        $location.path("/division");

        $scope.editDivision.divisionDetail = angular.toJson($scope.editStartSation);

        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Division/changeDivision",{
            params:$scope.editDivision
        }).success(function(data){

            Ninico.changeResultInfo($scope,data);
            $(".loading-container").addClass("loading-inactive");

            $scope.getdata();

        });
    }

    //停运路线
    $scope.doShop = function(id){

        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Division/stopDivisionStatus",{
            params:{"id":id}
        }).success(function(data){

            Ninico.changeResultInfo($scope,data);

            $scope.getdata();
        });
    };

    $scope.doStart = function(id){

        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Division/beginDivisionStatus",{
            params:{"id":id}
        }).success(function(data){

            Ninico.changeResultInfo($scope,data);

            $scope.getdata();
        });
    };
});

/**
 * 订单管理模块控制器
 *
 */
ordersModule.controller("ordersCtrl",function($scope,$http,$filter){

    $scope.page = 0;
    $scope.total = 0;
    $scope.pagesArr = [];

    $scope.Math = Math;

    $scope.params = {};

    $scope.params.date = $filter("date")(new Date(),"yyyy-MM-dd");

    $scope.getdata = function(){

        $scope.params.page = $scope.page + 1;
        $scope.params.start = $scope.page * 20;
        $scope.params.limit = 20;

        $(".loading-container").removeClass("loading-inactive");
        $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/User/checkUserOrders",{params:$scope.params}).success(function(data){
            $(".loading-container").addClass("loading-inactive");
            $scope.ordersInfo = data.orders;
            Ninico.pageinator($scope,data);

        })

    };

    $(".loading-container").removeClass("loading-inactive");
    $http.get("http://1.bjticketsystem.sinaapp.com/index.php/Admin/Businfo/checkBusinfoList").success(function(data){
        $(".loading-container").addClass("loading-inactive");
        $scope.bussinfo = data.result;
    });

    $scope.getdata();

    $scope.update = function(e){

        if(e){
            $scope.page = e-1;
        }else{
            $scope.page = 0;

            $scope.params.date = $("#id-date-picker-1").val();
            $scope.params.busid = $scope.saixuanBusid;
        }

        console.log(!e);


        $scope.getdata();
    };


    $scope.clearDate = function(){

        $scope.params.date = null;
        $scope.params.busid = null;

        $scope.getdata();
    }


});