(function() {
	var undeterminedOrder = function() {
		var XMLHttpReq;

		function getAjaxRequest() {

			XMLHttpReq = new XMLHttpRequest();
			XMLHttpReq.open('get', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/User/checkWaitFinishUserorders', true);
			XMLHttpReq.onreadystatechange = processResponse;
			XMLHttpReq.send(null);

		}

		function processResponse() {
			if (XMLHttpReq.readyState == 4) {
				// if(XMLHttpReq.status == 200)
				// {
				var txt = XMLHttpReq.responseText;
				// var order = eval("(" + txt + ")");
				// alert(order[0].divisionid);
				drawNotFinishUserorders(txt);



				// }

			}
		}

		getAjaxRequest();



	}

	undeterminedOrder();


// 获得日期格式 2015-05-16 得到星期几

	var getWeekDay = function(valStr){


		var dateArr = valStr.split('-');

		var year = dateArr[0], month = dateArr[1]-1, date = dateArr[2];// month=6表示7月
		var dt = new Date(year, month, date);
		var weekDay = ["星期天", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];

		return weekDay[dt.getDay()];
	}

	var drawNotFinishUserorders = function(txt) {
		var order = eval("(" + txt + ")");
		var length = order.length;
		var str = "";

		

		for (i = 0; i < length; i++) {

			var weekDay = getWeekDay(order[i].Division.date);
			console.log(weekDay);

			if(order[i].time.split(":")[0] < 10){
				order[i].time = "0" + order[i].time;
			}

			str = str + "<div class='Message' id=''><div class='userMessage list'><span class='orderNumber'>订单编号：</td><td>" + order[i].order_num + "</span><span class='time'>发车时间：</td><td>" + order[i].Division.date + "&nbsp" + weekDay +"&nbsp"+ order[i].time + "</span></div><div class='orderMessage list maintable'><table><tr><td>上车站点：</td><td>" + order[i].start_site + "</td></td></tr><tr><td>到达站点：</td><td>" + order[i].reach_site + "</td></tr><tr><td>途经站点：</td><td>"+ order[i].passsite+"</td><tr><tr><td>车牌号码：</td><td>"+ order[i].plate_number+"</td></tr><tr><td class='long'>车上电话：</td><td>" + order[i].Division.service_phone + "</td></tr><tr><td class='long'>订票数量：</td><td>" + order[i].purchase_quantity + "张</td><td class='count right'>总价：<span class='price'>" + order[i].purchase_price + "</span></td></tr></table></div><div class='orderMessage list'><table><tr><td class='right'><button class='bt'id='" + order[i].order_num + "'>取消订单</button></td></tr></table></div></div>";

			document.getElementById('content').innerHTML = str;

		}
		addonclick();



	}
	var drawFinishUserorders = function(txt) {
		var order = eval("(" + txt + ")");
		var length = order.length;
		var str = "";
		document.getElementById('content').innerHTML = str;

		

		for (i = 0; i < length; i++) {

			var weekDay = getWeekDay(order[i].Division.date);
			console.log(weekDay);

			if(order[i].time.split(":")[0] < 10){
				order[i].time = "0" + order[i].time;
			}

			str = str + "<div class='Message' id=''><div class='userMessage list'><span class='orderNumber'>订单编号：" + order[i].order_num + "</span><span class='time'>发车时间：" + order[i].Division.date + "&nbsp" +weekDay+"&nbsp"+ order[i].time + "</span></div><div class='orderMessage list maintable'><table><tr><td>上车站点：</td><td>" + order[i].start_site + "</td></td></tr><tr><td>到达站点：</td><td>" + order[i].reach_site + "</td></tr><tr><td>途经站点：</td><td>"+ order[i].passsite+"</td><tr><tr><td>车牌号码：</td><td>"+ order[i].plate_number+"</td></tr><tr><td class='long'>车上电话：</td><td>" + order[i].Division.service_phone + "</td></tr><tr><td class='long'>订票数量：</td><td>" + order[i].purchase_quantity + "张</td><td class='count right'>总价：<span class='price'>" + order[i].purchase_price + "</span></td></tr></table><div class='orderMessage list'></div></div></div>";

			document.getElementById('content').innerHTML = str;

		}



	}

	var complete = function() {
		var XMLHttpReq;

		function getAjaxRequest() {

			XMLHttpReq = new XMLHttpRequest();
			XMLHttpReq.open('get', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/User/checkHistoryorders', true);
			XMLHttpReq.onreadystatechange = processResponse;
			XMLHttpReq.send(null);

		}

		function processResponse() {
			if (XMLHttpReq.readyState == 4) {
				// if(XMLHttpReq.status == 200)
				// {
				var txt = XMLHttpReq.responseText;

				// var order = eval("(" + txt + ")");
				drawFinishUserorders(txt);



				// }

			}
		}

		getAjaxRequest();



	};

	undeterminedOrder();

	// document.getElementsByClassName('undetermined')[0].onclick = undeterminedOrder;
	// document.getElementsByClassName('complete')[0].onclick = complete;

	function addonclick() {
		var btl = document.getElementsByClassName('bt').length;
		for (var i = 0; i < btl; i++) {
			document.getElementsByClassName('bt')[i].onclick = function() {
				var XMLHttpReq;
				var id = this.id;
				var data = "id=" + id;

				function getAjaxRequest() {

					XMLHttpReq = new XMLHttpRequest();
					XMLHttpReq.open('post', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/User/cancelUserorder', true);
					XMLHttpReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					XMLHttpReq.onreadystatechange = processResponse;
					XMLHttpReq.send(data);

				}

				function processResponse() {
					if (XMLHttpReq.readyState == 4) {
						// if(XMLHttpReq.status == 200)
						// {
						var txt = XMLHttpReq.responseText;
						alert(txt);
						undeterminedOrder();
						// var order = eval("(" + txt + ")");
						// alert(order[0].divisionid);


						// }

					}
				}

				getAjaxRequest();


			}
		}
	}

	document.getElementById('img').onclick = function() {
		window.location.href = personalCenter_url;
	};


	var titles = document.getElementsByClassName("title");
		console.log(titles);

		for(var i = 0,len = titles.length; i < len ; i++){

				(function(i){
					titles[i].onclick = function(){

						document.getElementById('content').innerHTML = "";
						if(i == 0){
							undeterminedOrder();
						}else if( i == 1){
							complete();
						}

					for(var j = 0,len =titles.length; j < len ; j++){

						(function(j){
							var className = titles[j].className.replace("active","");
							titles[j].setAttribute('calssName',className);
							titles[j].className = className;
						})(j)
						
					}

					this.className = this.className + " active";
				}
			})(i)
			
		}


})();