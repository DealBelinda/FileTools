/**
 * Created by Administrator on 2015-12-27.
 */

var Ninico = {};

Ninico.pageinator = function($scope,data){


    $scope.total = data.total;

    if(($scope.total/$scope.params.limit) > 5){

        if($scope.page >= 4){

            $scope.pagesArr = [];

            if($scope.page >= Math.ceil($scope.total/$scope.params.limit)-4){

                for(var i = 0; i < 5; i++){

                    $scope.pagesArr.unshift(Math.ceil($scope.total/$scope.params.limit)-i)
                }
            }else{

                for(var i = 0; i < 5 ; i++){

                    $scope.pagesArr.push($scope.page -1 + i);
                }
            }

        }else{


            $scope.pageArr = [];
            $scope.pagesArr = [1,2,3,4,5]
        }

    }else if(($scope.total/$scope.params.limit) <= 5){

        $scope.pagesArr = [];
        for(var i = 0 ; i < ($scope.total/$scope.params.limit) ; i++){

            console.log($scope.page == Math.ceil($scope.total/$scope.params.limit)-1)
            $scope.pagesArr.push(i+1);
        }
    }

};


Ninico.changeResultInfo = function($scope,data){
    data.openflag = true;

    $scope.$emit("changeResultInfo",data);
};