(function() {
	//获取数据库数据
	function getAjaxRequest() {

		XMLHttpReq = new XMLHttpRequest();
		XMLHttpReq.open('post', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/User/checkUserinfo', true);
		XMLHttpReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		XMLHttpReq.onreadystatechange = processResponse;
		XMLHttpReq.send(null);

	}

	function processResponse() {
		if (XMLHttpReq.readyState == 4) {

			var txt = XMLHttpReq.responseText;
			var message = eval("(" + txt + ")");
			addMessage(txt);


		}
	}

	getAjaxRequest();

	function addMessage(txt) {

		var message = eval("(" + txt + ")");
		document.getElementsByClassName('userName')[0].innerHTML = message[0].name;
		document.getElementsByClassName('zs')[0].innerHTML = message[0].travel_point;
		document.getElementsByClassName('jf')[0].innerHTML = message[0].home_point;
		document.getElementsByClassName('img')[0].src = message[0].headimgurl;

	}



	var jump = function() {
		document.getElementsByClassName('order')[0].onclick = function() {

			window.location.href = orderDetail_url;
		}
	}
	jump();



})();