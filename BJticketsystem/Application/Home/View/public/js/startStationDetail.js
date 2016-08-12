(function() {

	var drawState = function() {
		var str = ""

		for (var i = 0; i < this.length; i++) {
			if (i === 0) {
				str = "<tr>"
			}

			if (i % 3 === 0 && i != 0) {
				str = str + '</tr><tr>'
			}

			str = str + '<td>' + this[i] + '</td>';
		}

		str = str + '</tr>';

		return str;
	}

	var getStionList = function() {
		var XMLHttpReq;

		function getAjaxRequest() {

			XMLHttpReq = new XMLHttpRequest();
			XMLHttpReq.open('post', 'http://1.bjticketsystem.sinaapp.com/index.php/Home/index/getSite', true);
			XMLHttpReq.onreadystatechange = processResponse;
			XMLHttpReq.send(null);

		}

		function processResponse() {
			if (XMLHttpReq.readyState == 4) {
				// if(XMLHttpReq.status == 200)
				// {
				var txt = XMLHttpReq.responseText;
				draw(txt);



				// }

			}
		}

		getAjaxRequest();



	}

	getStionList();

	var draw = function(txt) {

		var str = "";
		var stationList = eval("(" + txt + ")");


		for (i = 0; i < stationList.result.length; i++) {
			var main = document.getElementsByClassName('main');
			str = str + "<div class='block'><h4><span>" + stationList.result[i].total + "</span></h4><table>" + drawState.call(stationList.result[i].detail) + "</table></div>";

			main[0].innerHTML = str;

		}
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
			addOnclick(theRequest);
			returnindex(theRequest);

		}
		getRequest();

		function addOnclick(theRequest) {
			var td = document.getElementsByTagName('td');
			for (i = 0; i < td.length; i++) {


				td[i].onclick = function() {
					window.location.href = "index.html?startStation=" + this.innerText + "&endStation=" + theRequest.endStation + "&date=" + theRequest.date;
				}

			}
		}



	}


	var returnindex = function(theRequest) {
		document.getElementById('img').onclick = function() {
			window.location.href = "index.html?startStation=" + theRequest.startStation + "&endStation=" + theRequest.endStation + "&date=" + theRequest.date;

		}
	}



})();