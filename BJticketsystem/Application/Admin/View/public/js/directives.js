//aihuijia.directive("onFinishRenderFilters",function($timeout,$location){
//    return {
//        restrict: 'A',
//
//        link:function($scope,iElm,iAtrrs,controller){
//            if($scope.$last === true){
//
//                $timeout(function(){
//                    if($location.path() === "/site"){
//                        InitiateSimpleDataTable.init();
//                    }
//                })
//            }
//        }
//    }
//})


aihuijia.directive("deletebtn",function(){
   return {
       restrict: "AE",

       scope:{
          delete:"&"
       },
       template:'<a class="btn btn-danger btn-xs delete" ng-click="delete()"><i class="fa fa-trash-o ficon" >&#xe824;</i> 删除</a>'
   }
});

aihuijia.directive("eidtbtn",function(){
   return {
       restrict:"AE",
       scope:{
           eidtone:"&"
       },
       template:'<a href="#" class="btn btn-info btn-xs edit" ng-click="eidtone()"><i class="fa fa-edit ficon">&#xe820;</i> 编辑</butto>'
   }
});