// Globals
var sitename = "carpool";
var dir = "test";

// jsS - the javascript session variables
var jsSride = [];
var jsSESSION = [];
var jsSid;
var jsSusername;
var jsSpassword;
var jsSemail;
var jsSphone;
var jsSlatitude;
var jsSlongitude;
var jsStype;
var jsSdlatitude;
var jsSdlongitude;
var jsSlatestleave;
var jsSspots;
var jsSridingwith;
var jsSincar;
var jsSavailablespots;
var jsSmpg;
var jsStripdistance;

// Reload jsS - the javascript session variables
function reload(myUsername){
	getMyUserInfo(myUsername, function(){
		$('#myname').html(jsSusername+" is logged in.");
		myRide("carpool_trip1");
		getLeaveTime();
		console.log("reloaded");
		/*createMap();
		if(jsSdlatitude != null){
			if(jsSincar != null){
				route(jsSincar, true, "myRideCarInfo");
				}
			else if(jsStype==="offer"){
				route(jsSusername, true, "myCarInfo");
				}
			}*/
		if((jsSincar != null) || (jsSridingwith != null)) $('#clearRideSpan').html("<button id='clearRide' name='clearRide' onclick='clearRide()'>Remove me from "+jsSincar+"'s car</button>");
		if(jsSdlatitude != null) $('#clearDest').show();
		else if(jsSdlatitude == null) $('#clearDest').hide();
		if(jsStype==="offer"){
			getLeaveTime();
			myCar();
			}
		if(jsSmpg != null) $('#myMpg').html("Your current mpg is "+jsSmpg+".<br>");
		else $('#myMpg').html("The mpg of your car is not set.<br>");
		});
	}

// Main
function readFile(filename){
	filename = "/"+filename;
	var http=new XMLHttpRequest();
	http.open("GET",filename,false);
	http.send();
	return http.responseText;
	}

function tryParseJSON (jsonString){
	try {
		var o = JSON.parse(jsonString);
		if (o && typeof o === "object" ) {
			return o;
			}
		}
	catch (e) { }
	return false;
	}

function getMyUserInfo(user, callback){
	getUserInfo(user, function(userInfo){
		jsSESSION = userInfo[0];
		jsSusername = jsSESSION[0];
		jsSpassword = jsSESSION[1];
		jsSemail = jsSESSION[2];
		jsSlat = jsSESSION[3];
		jsSlng = jsSESSION[4];
		jsStype = jsSESSION[5];
		jsSmpg = jsSESSION[6];
		//myPosition = new google.maps.LatLng(jsSlatitude, jsSlongitude);
		//if(jsSdlatitude != null) myDest = new google.maps.LatLng(jsSdlatitude, jsSdlongitude);
		//nearby(function(){
			callback();
		//	});
		});
	}

function dateSufix(date){
	if(date == 1) {
		return "st";
		}
	else if(date == 2){
		return "nd";
		}
	else if(date == 3){
		return "rd";
		}
	else {
		return "th";
		}
	}

function timeArray(time){
	if(time!=null){
		var temp1 = time.split('-');
		var temp2 = temp1[2].split(' ');
		var temp3 = temp2[1].split(':');
		var out = [temp1[0], temp1[1], temp2[0], temp3[0], temp3[1], temp3[2]];
		return out;
		}
	}

function readableDate(mysqltime){
	var pre = timeArray(mysqltime);
	if(!pre) return false;
	day = pre[2];
	if(day[0] == 0) day = day[1];

	hour = pre[3];
	if(hour[0] == 0) hour = hour[1];

	minute = pre[4];
	if(day.length == 1) sufix = dateSufix(day);
	else if (day[0] == 1) sufix = dateSufix(day);
	else sufix = dateSufix(day[1]);
	
	if(hour < 12) {
		var ampm = "am";
		}
	else if(hour == 24) {
		var ampm = "am";
		}
	else {
		var ampm = "pm";
		}

	if(hour > 12) {
		hour = hour-12;
		}
	if(hour == 0) hour = 12;

	var toreturn = hour+':'+minute+' '+ampm+" on the "+day+sufix;
	return toreturn;
	}

function getUserInfo(user, callback){
	getFromTable("carpool_test", "username, password, email, lat, lng, type, mpg ", "username", user, function(allUserInfo){
		callback(JSON.parse(allUserInfo));
		});
	}

function getFromTable(table, stuff, something, isthis, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			get: stuff,
			something: something,
			isthis: isthis
			},
		success: function(data){
			callback(data);
			}
		});
	}

function updateString(table, what, value, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			what: what,
			string: value,
			user: user
			},
		success: function(data){
			callback(data);
			}
		});
	}

function updateNum(table, what, value, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			what: what,
			num: value,
			user: user
			},
		success: function(data){
			callback(data);
			}
		});
	}	

function updateMultString(table, these, values, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			theseString: these,
			newvalues: values,
			id: id
			},
		success: function(data){
			callback(data);
			}
		});
	}	

function updateMultNum(table, these, values, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			theseNum: these,
			newvalues: values,
			user: user
			},
		success: function(data){
			callback(data);
			}
		});
	}
