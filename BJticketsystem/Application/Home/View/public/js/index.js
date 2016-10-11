(function() {

	/**
	 * 从其他页面退回到查询页面的时候，将原来用户输入的参数还原
	 * @return void
	 */
	var getRequest = function() {
		var url = decodeURI(location.search);
		var theRequest = new Object();
		if (url.indexOf("?") != -1) {

			var str = url.substr(1);
			strs = str.split("&");
			for (var i = 0; i < strs.length; i++) {
				theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
			}
		}
		if (theRequest.startStation != undefined) {
			document.getElementById('startStation').innerText = theRequest.startStation;

		};
		if (theRequest.endStation != undefined) {
			document.getElementById('endStation').innerText = theRequest.endStation;
		};
		if (theRequest.date != undefined) {

			document.getElementById('date').value = theRequest.date;
		};



	}


	/**
	 * 获取站点细节
	 * @return void
	 */

	var jump = function() {

		var startStation = document.getElementById('startStation').innerText;
		var endStation = document.getElementById('endStation').innerText;

		//触发查询起始站点
		document.getElementsByClassName('start')[0].onclick = function() {
			var date = document.getElementById('date').value;



			window.location.href = startStationDetail_url + "?startStation=" + startStation + "&endStation=" + endStation + "&date=" + date;



		};

		//触发查询终点站点
		document.getElementsByClassName('end')[0].onclick = function() {

			var date = document.getElementById('date').value;
			window.location.href = endStationDetail_url + "?startStation=" + startStation + "&endStation=" + endStation + "&date=" + date;
		};


	/**
	 * 触发站点查询事件
	 * @return void
	 */
		document.getElementById('jump').onclick = function() {

			var date = new Date();

			if(date.getHours() < 6){

				alert("抱歉 凌晨00:00 －－ 06:00 为系统维护时段，不能预定车票。");
				return;
			}

			if(startStation == "请输入出发站点"){
				alert("请输入出发站点！");
				return;
			}else if(endStation == "请输入到达站点"){
				alert("请输入到达站点！");
				return;
			}

			var date = document.getElementById("date").value;
			window.location.href = schedulesQuery_url + "?startStation=" + startStation + "&endStation=" + endStation + "&date=" + date;
		}

	};


	/**
	 * 从后台获取第一个子站点，填写在页面的初始站和起始站选项
	 * @param {[type]} txt [description]
	 */
	var addStation = function(txt) {
		var str = "";
		var stationList = eval("(" + txt + ")");

		document.getElementById('startStation').innerText = stationList.result[0].detail[0];

		document.getElementById('endStation').innerText = stationList.result[0].detail[0];

	}

	/**
	 * 渲染date select中的日期，为七天
	 */
	var addDate = function() {
		var date = [];
		for (i = 0; i < 7; i++) {
			var d = new Date();
			d.setDate(d.getDate() + i);
			var month = d.getMonth() + 1;
			var day = d.getDate();
			var weekDayNumber = d.getDay();
			if (month < 10) {
				month = "0" + month;
			}
			if (day < 10) {
				day = "0" + day;
			}
			var week = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
			weekDay = week[weekDayNumber];
			var val = d.getFullYear() + "-" + month + "-" + day + " " + weekDay;
			date[i] = val;


		}

		// for (i = 0; i < date.length; i++) {
		// 	var str = document.getElementById('date').innerHTML;
		// 	str = str + "<option value='" + date[i] + "'>" + date[i] + "</option>"
		// 	document.getElementById('date').innerHTML = str;

		// }

		document.getElementById("dateSelection").innerHTML = date[0];
		document.getElementById("date").value = date[0];

	}


	function getAjaxRequest() {

		XMLHttpReq = new XMLHttpRequest();
		XMLHttpReq.onreadystatechange = processResponse;
		XMLHttpReq.open('get', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/index/getSite', true);
		XMLHttpReq.send();

	}

	function processResponse() {
		if (XMLHttpReq.readyState == 4) {

			var txt = XMLHttpReq.responseText;
			addStation(txt);
			getRequest();
			jump();


		}
	}

	// getAjaxRequest();
	addDate();
	getRequest();
	jump();


})();