(function() {
	var getRequest = function() {
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

		}

	}
	getRequest();



	function updateStation(theRequest) {
		var station = document.getElementsByClassName('main');

		if( theRequest.time.split(":")[0] < 10){
			theRequest.time = "0" +  theRequest.time;
		}

		station[1].innerText = theRequest.startStation;
		station[3].innerText = theRequest.endStation;
		document.getElementById('number').innerText = theRequest.number;
		document.getElementsByClassName('price')[0].innerText = theRequest.price;
		document.getElementsByClassName('price')[1].innerText = theRequest.price;
		document.getElementById('date').innerText = theRequest.date;
		document.getElementById('time').innerText = theRequest.time;
		document.getElementsByClassName('surplus')[0].innerText = theRequest.ticket -1;
	}
	var addTicket = function() {
		document.getElementById('add').onclick = function() {
			var ticketNumber = parseInt(document.getElementsByClassName('ticketNumber')[0].innerText);
			var ticketSurplus = parseInt(document.getElementsByClassName('surplus')[0].innerText);
			var price = parseInt(document.getElementsByClassName('price')[0].innerText);
			if (ticketSurplus > 0 && ticketNumber < 5) {
				document.getElementsByClassName('ticketNumber')[0].innerText = ticketNumber + 1;
				document.getElementsByClassName('surplus')[0].innerText = ticketSurplus - 1;
				var money = price * (ticketNumber + 1);
				document.getElementsByClassName('price')[1].innerText = money;


			}

		}
	}
	addTicket();
	var minusTicket = function() {
		document.getElementById('minus').onclick = function() {
			var ticketNumber = parseInt(document.getElementsByClassName('ticketNumber')[0].innerText);
			var ticketSurplus = parseInt(document.getElementsByClassName('surplus')[0].innerText);
			var price = parseInt(document.getElementsByClassName('price')[0].innerText);
			if (ticketNumber > 1) {
				document.getElementsByClassName('ticketNumber')[0].innerText = ticketNumber - 1;
				document.getElementsByClassName('surplus')[0].innerText = ticketSurplus + 1;
				var money = price * (ticketNumber - 1);
				document.getElementsByClassName('price')[1].innerText = money;
			}
		}

	}
	minusTicket();



	function Back(theRequest) {
		document.getElementsByTagName('img')[0].onclick = function() {
			window.location.href = schedulesQuery_url + "?startStation=" + theRequest.startStation + "&endStation=" + theRequest.endStation + "&date=" + theRequest.date;
		}
	}

	function validate_name(field, alerttxt) {
		with(field) {
			if (value == null || value == "") {
				alert(alerttxt);
				return false
			} else {
				return true
			}
		}
	}

	function validate_cNumber(field, alerttxt) {
		with(field) {
			if (value == null || value == "" || value.length < 11) {
				alert(alerttxt);
				return false
			} else {
				return true
			}

		}
	}


	function validate_form(form) {

		with(form) {
			if (validate_name(name, "请填写取票人") == false) {
				name.focus();
				return false
			}

			if (validate_cNumber(cNumber, "请填写正确手机号码") == false) {
				cNumber.focus();
				return false
			}
			document.getElementsByClassName('bottomDiv')[0].onclick = null;
			submitMessage();



		}
	}
	var form = document.getElementById('userMessage');

	function validate() {
		validate_form(form);


	}
	document.getElementsByClassName('bottomDiv')[0].onclick = validate;

	function submitMessage() {

		function getAjaxRequest() {

			var url = decodeURI(location.search);
			var theRequest = new Object();
			if (url.indexOf("?") != -1) {

				var str = url.substr(1);
				strs = str.split("&");
				for (var i = 0; i < strs.length; i++) {
					theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
				}

			}

			var phone = document.getElementsByClassName('cNumber')[0].value;
			var name = document.getElementsByClassName('name')[0].value;
			var ticketNumber = parseInt(document.getElementsByClassName('ticketNumber')[0].innerText);
			var amount = document.getElementsByClassName('price')[1].innerText;
			var code = document.getElementById('checkCode').value;
			data = "divisionid=" + theRequest.id + "&passenger_phone=" + phone + "&passenger_name=" + name + "&purchase_quantity=" + ticketNumber + "&amount=" + amount + "&code=" + code + "&start_site=" + theRequest.startStation + "&reach_site=" + theRequest.endStation + "&time=" + theRequest.time;


			XMLHttpReq = new XMLHttpRequest();
			XMLHttpReq.open('post', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/Booking/Realisticpayment', true);
			XMLHttpReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			XMLHttpReq.onreadystatechange = processResponse;
			XMLHttpReq.send(data);

			function processResponse() {
				if (XMLHttpReq.readyState == 4) {
					// if(XMLHttpReq.status == 200)
					// {
					var txt = XMLHttpReq.responseText;
					if (txt == '验证失败') {
						document.getElementsByClassName('bottomDiv')[0].onclick = validate;
						alert('验证码错误，请重新输入');
					}
					if (txt == '无票') {
						document.getElementsByClassName('bottomDiv')[0].onclick = validate;
						alert('票余量不足，请重新订票');
					}
					if (txt == '订单创建成功！') {
						window.location.href = success_url;
					} else if (txt != '验证失败' && txt != '无票' && txt != '订单创建成功！') {
						document.getElementsByClassName('bottomDiv')[0].onclick = validate;
						alert('服务器出错，请稍后重试');;
						curCount = 0;

					}



					// }

				}

			}

		}
		getAjaxRequest();

	}

	var curCount; //当前剩余秒数  
	document.body.onclick = function() {
		var e = e || event;
		var current = e.target || e.srcElement
		if (current.id == 'btnSendCode') {
			var InterValObj; //timer变量，控制时间  
			var count = 60; //间隔函数，1秒执行  

			function sendMessage() {
				curCount = count;
				var phone = document.getElementsByClassName('cNumber')[0].value; //手机号码  
				if (phone != "") {
					//产生验证码  

					//设置button效果，开始计时
					document.getElementById('btnSendCode').disabled = true;
					document.getElementById('btnSendCode').value = "请在" + curCount + "秒内输入验证码";
					InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次  
					//向后台发送处理数据
					var XMLHttpReq;
					data = "phone=" + phone;


					function getAjaxRequest() {
						XMLHttpReq = new XMLHttpRequest();
						XMLHttpReq.open('post', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/Booking/sendSMS', true);
						XMLHttpReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						XMLHttpReq.onreadystatechange = processResponse;
						XMLHttpReq.send(data);

					}

					function processResponse() {
						if (XMLHttpReq.readyState == 4) {
							// if(XMLHttpReq.status == 200)
							// {
							var txt = XMLHttpReq.responseText;


							// }

						}
					}

					getAjaxRequest();

					//timer处理函数  
					function SetRemainTime() {
						if (curCount == 0) {
							window.clearInterval(InterValObj); //停止计时器 
							document.getElementById('btnSendCode').disabled = false;
							document.getElementById('btnSendCode').value = "重新发送验证码";
							code = ""; //清除验证码。如果不清除，过时间后，输入收到的验证码依然有效      
						} else {
							curCount--;
							document.getElementById('btnSendCode').value = "请在" + curCount + "秒内输入验证码";
						}
					}

				} else {
					alert('请输入手机号码');
				}
			}
			sendMessage();
		}
	}
})();