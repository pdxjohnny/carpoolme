var sitename = "carpool";

var site = document.URL;
site = site.substring(site.indexOf("/") + 2);
site = site.substring(0, site.indexOf('/'));
function links(file,dir,linktype){

	var strings = readFile(file).split('\n');
	var links = stringsToLinks(strings,dir,linktype);
	for(var i=0;i<links.length;i++){
		document.write(links[i]);
		}
	}

function upload(subdir){
	document.write("<form action='scripts/upload.php' method='post' enctype='multipart/form-data'><input type='hidden' name='uploadto' value="+subdir+"/><input type='submit' name='submit' value='Upload'> <input type='file' name='file' id='file'></form>");
	}

function removeLeading(string,find){

	var temp = [];
	temp[0]='\0';
	var i=0;
	if(typeof find !== 'undefined'){
		for(var i=0;i<string.length;i++){
			if(string[i]==find){
				break;
				}
			}
		}
	for(var j=0;i<string.length;i++){
		temp[j]=string[i];
		temp[j+1]='\0';
		j++;
		}
	return temp;
	}

function stringsToLinks(list,directory,linktype){
	
	for (var i = 0 ; i < list.length ; i++){
		var n = list[i].indexOf('.');
		var temp = list[i].substring(0, n != -1 ? n : list[i].length);
		temp = temp.replace('_',' ');

		if(typeof linktype !== 'undefined'){
			if(linktype === "images"){
				if(typeof directory === 'undefined'){
					console.log("No directory passed to 'function stringsToImageLinks'");
					}
				else{
					if(i==0) var links = ["<li id="+temp+" class='figure'><a href="+directory+list[i]+"><img src="+directory+list[i]+" alt="+temp+" /><span class='figcaption'><b>"+temp+"</b></span></a></li>"];
					else links.push("<li id="+temp+" class='figure'><a href="+directory+list[i]+"><img src="+directory+list[i]+" alt="+temp+" /><span class='figcaption'><b>"+temp+"</b></span></a></li>");
					}
				}
			else if(linktype === "list"){
				if(typeof directory === 'undefined'){
					if(i==0) var links = ["<li class='first'><a href=/>Home</a></li>"];
					if (temp!=="index") links.push("<li class='first'><a href="+list[i]+">"+temp+"</a></li>");
					}
				else{
					if(i==0) var links = ["<li class='first'><a href="+directory+list[i]+">"+temp+"</a></li>"];
					else links.push("<li class='first'><a href="+directory+list[i]+">"+temp+"</a></li>");
					}
				}
			}
		else{
			if(typeof directory === 'undefined'){
				if(i==0) var links = ["<a href=/>Home</a>"];
				if ((temp!=="index")&&(temp!=="login")) links.push("<a href="+list[i]+">"+temp+"</a>");
				}
			else{
				if(i==0) var links = ["<a href="+directory+list[i]+">"+temp+"</a><br>"];
				else links.push("<a href="+directory+list[i]+">"+temp+"</a><br>");
				}
			}
		}
	return links;
	}		

function readFile(filename){
	var oRequest = new XMLHttpRequest();
	var sURL = /*"http://"
        	 + site
        	 +*/ "/readfiles/"
		 + filename;

	oRequest.open("GET",sURL,false);
	oRequest.setRequestHeader("User-Agent",navigator.userAgent);
	oRequest.send(null);

	if (oRequest.status==200){
		var res = oRequest.responseText;
		return res;
		}
	else alert("Error executing XMLHttpRequest call!");
	}
function oneOnMap(lat,lng){

	var x=document.getElementById("demo");
	latlon=new google.maps.LatLng(lat, lng)
	mapholder=document.getElementById('mapholder')
	mapholder.style.height='250px';
	mapholder.style.width='100%';

 	 var myOptions={
 	 center:latlon,zoom:14,
	mapTypeId:google.maps.MapTypeId.ROADMAP,
	mapTypeControl:false,
 	 navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
 	 };
  	var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
  	var marker=new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
	}

function johnnyMap(lat,lng){

	}

