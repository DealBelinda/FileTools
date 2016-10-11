/*
    app aihuijia
 */
var aihuijia = angular.module('aihuijia', ['ui.router','siteModule','busModule','divisionModule','ordersModule']).
    run(function($rootScope,$state,$stateParams,$location){
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;

    }).
    config(function($stateProvider,$urlRouterProvider) {

        $urlRouterProvider.otherwise('/welcome');


        $stateProvider.state("site", {
            url: "/site",
            templateUrl: "http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/site/siteTable.html"
        }).state("site.newSite",{
            url:'/newSite',
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/site/newSite.html"
        }).state("site.editSite",{
            url:"/editSite",
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/site/editSite.html"
        }).state("bus",{
            url:"/bus",
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/bus/busTable.html"
        }).state("bus.newBus",{
            url:'/newBus',
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/bus/newBus.html"
        }).state("bus.editBus",{
            url:'/editBus',
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/bus/editBus.html"
        }).state("division",{
            url:'/division',
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/division/divisionTable.html"
        }).state("division.newDivision",{
            url:'/newDivision',
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/division/newDivision.html"
        }).state("division.newDivision.newDivisionDetail",{
            url:'/newDivisionDetail',
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/division/newDivisionDetail.html"
        }).state("division.editDivision",{
            url:'/editDivision',
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/division/editDivision.html"
        }).state("division.editDivision.editDivisionDetail",{
            url:"/editDivisionDetail",
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/division/editDivisionDetail.html"
        }).state("orders",{
            url:"/orders",
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/orders/ordersTable.html"
        }).state("division.sellStatus",{
            url:"/sellStatus",
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/division/sellStatus.html"
        }).state("welcome",{
            url:"/welcome",
            templateUrl:"http://1.bjticketsystem.sinaapp.com/Application/Admin/View/public/tpls/welcome.html"
        })
    });


var siteModule = angular.module("siteModule",[]);


var busModule = angular.module("busModule",[]);


var divisionModule = angular.module("divisionModule",[]);

var ordersModule = angular.module("ordersModule",[]);



aihuijia.filter("divisionStatus",function(){
    return function(inputArray){

        var status = '';

        if(inputArray == 0){
            status = "已停止运行";
        }else if(inputArray == 1){
            status = "正常运行";
        }

        return status;
    };


}).filter("orderStatus",function(){

    return function(inputArray){

        var status = '';

        if(inputArray == 0){
            status = "未完成";
        }else if(inputArray == 1){
            status = "已完成";
        }else if(inputArray == 2){
            status = "取消";
        }

        return status;
    }
});


