"use strict";
(function () {

	//attach click handler
	document.body.addEventListener('click', fireHandler, false);
	function fireHandler(e) {
		e = e || window.event;
		var target = e.target || e.srcElement;
		if (target.className.match(/col/)) {
			var dataCoordinates = target.getAttribute("data-coordinates");
			var coordinates = dataCoordinates.split("-");
			fire(coordinates[0], coordinates[1]);
		}
	}

	/**
	 * Attack coordinates from the boards, defined by row and col
	 * @param {int} row
	 * @param {int} col
	 * @returns {undefined}
	 */
	function fire(row, col) {
		post("index.php?action=fire",
			{row: row,col: col},
			function (response) {
				if (response.status) {
					//reload board
					document.getElementById("board").innerHTML = response.html;
					//update tries
					document.getElementById("tries").innerHTML = response.tries;
				}
				//show message
				document.getElementById("msg").innerHTML = response.msg;
			}
		);
	}

	/**
	 * Posts AJAX request to an URL
	 * @param {String} url
	 * @param {object} data
	 * @param {function} callback
	 * @returns {undefined}
	 */
	function post(url, data, callback) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if (xhttp.readyState === 4 && xhttp.status === 200) {
				var response = JSON.parse(xhttp.responseText);
				if (typeof callback === 'function') {
					callback(response);
				}
			}
		};
		xhttp.open("POST", url, true);
		xhttp.setRequestHeader("Accept", "application/json; charset=utf-8");
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(urlEncode(data));
	}

	/**
	 * Returns URL encoded string
	 * @param {object} obj
	 * @param {String} prefix
	 * @returns {String}
	 */
	function urlEncode(obj, prefix) {
		var str = [];
		for (var p in obj) {
			if (obj.hasOwnProperty(p)) {
				var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
				str.push(typeof v === "object" ?
						serialize(v, k) :
						encodeURIComponent(k) + "=" + encodeURIComponent(v));
			}
		}
		return str.join("&");
	};

}());
