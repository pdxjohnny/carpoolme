// Globals
var sitename = "carpool";
var dir = "scripts";
var table = "carpool_members"

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
var jsSlatd
var jsSlngd;
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
		console.log("reloaded");
		if(jsSincar != null) $('#clearRideSpan').html("<button id='clearRide' name='clearRide' onclick='clearRide()'>Remove me from "+jsSincar+"'s car</button>");
		else if((jsSincar == null) && (jsSridingwith != null)) $('#clearRideSpan').html("<button id='clearRide' name='clearRide' onclick='clearRide()'>Remove me from "+jsSridingwith+"'s car</button>");
		else $('#clearRideSpan').html("");
		if(jsSlngd != 0) $('#clearDest').show();
		else if(jsSlngd == 0) $('#clearDest').hide();
		if(jsStype==="offer"){
			getLeaveTime(table);
			myCar();
			}
		myRide(table);
		if(jsSmpg != null) $('#myMpg').html("Your current mpg is "+jsSmpg+".<br>");
		else $('#myMpg').html("The mpg of your car is not set.<br>");
		createMap();
		if(jsSlngd != 0){
			directionDisplay.setMap(null);
			if(jsSincar != null){
				route(jsSincar, true, "myRideCarInfo");
				if(jsStype==="offer"){
					route(jsSusername, false, "myCarInfo");
					}
				}
			else if(jsStype==="offer"){
				route(jsSusername, true, "myCarInfo");
				}
			}
		});
	}

// Call these every x seconds
function callEvery(table){
	if(jsStype==="offer"){
		getLeaveTime(table);
		myCar();
		}
	myRide(table);
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
		jsSlat = jsSESSION[3]*1;
		jsSlng = jsSESSION[4]*1;
		jsSlatd = jsSESSION[5]*1;
		jsSlngd = jsSESSION[6]*1;
		jsStype = jsSESSION[7];
		jsSmpg = jsSESSION[8];
		jsSincar = jsSESSION[9];
		jsSridingwith = jsSESSION[10];
		myPosition = new google.maps.LatLng(jsSlat, jsSlng);
		if(jsSlngd != 0) myDest = new google.maps.LatLng(jsSlatd, jsSlngd);
		nearby(0.05, function(nearMe){
			jsSnearby = nearMe;
			callback();
			});
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
	getFromTable("carpool_members", "username, password, email, latitude, longitude, dlatitude, dlongitude, type, mpg, incar, ridingwith ", "username ='"+user+"'", function(allUserInfo){
		callback(JSON.parse(allUserInfo));
		});
	}

function getFromTable(table, stuff, conditions, callback){
	//console.log("Table : "+table);
	//console.log("Getting : "+stuff);
	//console.log("Conditions : "+conditions);
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			get: stuff,
			conditions: conditions
			},
		success: function(data){
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
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
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
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
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
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
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
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
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
			}
		});
	}
