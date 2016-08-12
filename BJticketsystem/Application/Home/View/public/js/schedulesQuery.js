(function() {
	var getSchedules = function() {
		var url = decodeURI(location.search);
		var theRequest = new Object();

		if (url.indexOf("?") != -1) {

			var str = url.substr(1);

			strs = str.split("&");
			for (var i = 0; i < strs.length; i++) {
				theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
			}
		}
		var XMLHttpReq;
		var date;
		if (document.getElementsByClassName('date')[0].innerText == '') {
			date = theRequest.date.split(' ')[0];
			document.getElementsByClassName('date')[0].innerHTML = theRequest.date;

		} else {
			date = document.getElementsByClassName('date')[0].innerText.split(' ')[0];
		}


		var param = "start_site=" + theRequest.startStation + "&reach_site=" + theRequest.endStation + "&date=" + date;


		function getAjaxRequest() {

			XMLHttpReq = new XMLHttpRequest();
			XMLHttpReq.open('post', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/Booking/selectTicket', true);
			XMLHttpReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			XMLHttpReq.onreadystatechange = processResponse;
			XMLHttpReq.send(param);


		}

		function processResponse() {
			if (XMLHttpReq.readyState == 4) {
				// if(XMLHttpReq.status == 200)
				// {
				var txt = XMLHttpReq.responseText;
				draw(txt, theRequest);



				// }

			}
		}

		getAjaxRequest();



	}


	getSchedules();

	var draw = function(txt, theRequest) {
		var detail = document.getElementById('detail');
		var str = "";
		var className = "";
		var starStr = '';
		if (txt != '没有您指定的线路！') {
			var schedules = eval("(" + txt + ")");

			for(var k = 0,clen = schedules.length;k<clen;k++){

				if(schedules[k].time.split(":")[0] < 10){

					schedules[k].time = '0' + schedules[k].time;
				}
			}

			for(var i = 0,alen = schedules.length; i < alen ; i ++){



				for(var j = 0,blen =schedules.length; j < blen - i -1; j++){

					if(schedules[j].time > schedules[j+1].time){
						var temp = schedules[j];
						schedules[j] = schedules[j+1];
						schedules[j+1] = temp;
					}
				}
			}

			var length = schedules.length
			for (i = 0; i < length; i++) {
				var star = schedules[i].bus_recommend_star;
				for (var j = 0; j < star; j++) {

					starStr = starStr + "<span><img id='star' src='http://1.bjticketsystem.sinaapp.com/Application/Home/View/public/img/star.png'/></span>"

				}
				for (var k = 0; k < (5 - j); k++) {
					starStr = starStr + "<span><img id='star' src='http://1.bjticketsystem.sinaapp.com/Application/Home/View/public/img/star-null.png'/></span>"
				};



				var pcarray = schedules[i].price.split("?");

				schedules[i].price = pcarray[0];

				schedules[i].cb = pcarray[1];

				if(schedules[i].cb == "0"){

					schedules[i].cb = "无餐食";
				}else {

					schedules[i].cb = "有餐食";
				}

				if(schedules[i].ticket == 0){

					schedules[i].ticket = "已订完";
					className = "no-sell";
				}

				str = str + "<div class='message "+className+"' id='" + schedules[i].busid + "'><span class='span-main time'>&nbsp" + schedules[i].time + "</span><span class='span-main clr'><img  class='star stationimg'  src='http://1.bjticketsystem.sinaapp.com/Application/Home/View/public/img/s.png'><span class='station'>" + theRequest.startStation + "</span><span class='price'><span class='mark'>¥</span>" + schedules[i].price + "</span></span><span class='span-main clr'><img  class='end stationimg' src='http://1.bjticketsystem.sinaapp.com/Application/Home/View/public/img/z.png'><span class='station'>" + theRequest.endStation + "</span>" + starStr + "<span class='bc'>"+ schedules[i].cb+"</span><span class='ticket'>余票&nbsp" + schedules[i].ticket + "</span></span></div>";

				document.getElementById('detail').innerHTML = str;
				starStr = '';
			}
		} else {
			document.getElementById('detail').innerHTML = str + txt;
		}

		getRequest(txt, theRequest);



	}
	var getRequest = function(txt, theRequest) {
		var url = decodeURI(location.search);
		var theRequest = new Object();
		if (url.indexOf("?") != -1) {

			var str = url.substr(1);

			strs = str.split("&");
			for (var i = 0; i < strs.length; i++) {
				theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
			}
			updateStation(theRequest);
			Back(theRequest);
			jump(txt, theRequest);

			updateDate();
		}

	}
	var updateStation = function(theRequest) {

		document.getElementsByTagName('h1')[0].innerHTML = theRequest.startStation + '<img src="http://1.bjticketsystem.sinaapp.com/Application/Home/View/public/img/iconfont-go-right.png" alt="" class="go-right">' + theRequest.endStation;
	}

	var updateDate = function() {

		function addDate(date, days) {

			var d = new Date(date);
			d.setDate(d.getDate() + days);
			var month = d.getMonth() + 1;
			var day = d.getDate();
			if (month < 10) {
				month = "0" + month;
			}
			if (day < 10) {
				day = "0" + day;
			}
			var val = d.getFullYear() + "-" + month + "-" + day;
			return val;
		}


		var datelist = [];
		var weeklist = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
		var dateSelect = document.getElementsByClassName('date')[0].innerText;
		date1 = dateSelect.split(' ');

		for (var i = 0; i < weeklist.length; i++) {
			if (date1[1] == weeklist[i]) {
				break;
			}
		}


		for (j = 0; j < 7; j++) {
			datelist[j] = addDate(new Date, j);
		}
		for (k = 0; k < datelist.length; k++) {
			if (date1[0] == datelist[k]) {
				break;
			}
		}


		document.getElementById('nextDate').onclick = function() {
			if (i == 6) {
				i = -1;

			} else if (datelist[k + 1] != undefined) {

				document.getElementsByClassName('date')[0].innerText = datelist[k + 1] + ' ' + weeklist[i + 1];
				k++;
				i++;
				getSchedules();
			}
		}
		document.getElementById('lastDate').onclick = function() {
			if (i == 0) {
				i = 7;
			} else if (datelist[k - 1] != undefined) {

				document.getElementsByClassName('date')[0].innerText = datelist[k - 1] + ' ' + weeklist[i - 1];
				k--;
				i--;
				getSchedules();

			}
		}


	}



	var jump = function jump(txt, theRequest) {
		var message = document.getElementsByClassName('message');
		var date = document.getElementsByClassName('date')[0].innerText;
		for (i = 0; i < message.length; i++) {
			document.getElementsByClassName('message')[i].onclick = function(e) {
				var schedules = eval("(" + txt + ")");
				var length = schedules.length;


				for (var i = 0; i < length; i++) {
					if (schedules[i].busid == this.id) {

						var j = i;
					};
				};

				if(schedules[j].ticket == 0){

					return;
				}

				schedules[j].price = schedules[j].price.split("?")[0];


				window.location.href = order_url + "?startStation=" + theRequest.startStation + "&endStation=" + theRequest.endStation + "&number=" + schedules[j].bus_plate_number + "&price=" + schedules[j].price + "&time=" + schedules[j].time + "&date=" + date + "&ticket=" + schedules[j].ticket + "&id=" + schedules[j].id;

			}
		}
	}

	var Back = function(theRequest) {
		document.getElementsByTagName('img')[0].onclick = function() {
			var date = document.getElementsByClassName('date')[0].innerHTML;

			window.location.href = "index.html?startStation=" + theRequest.startStation + "&endStation=" + theRequest.endStation + "&date=" + date;
		}
	}



})();