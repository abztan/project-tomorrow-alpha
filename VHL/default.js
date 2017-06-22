function verify()
	{
		var username = document.getElementById('usme').value;
		var password = document.getElementById('pard').value;

		location.href="Verify.php?a="+username+"&b="+password;
	}

function stickIt() {

  var orgElementPos = $('.original').offset();
  orgElementTop = orgElementPos.top;

  if ($(window).scrollTop() >= (orgElementTop)) {
    // scrolled past the original position; now only show the cloned, sticky element.

    // Cloned element should always have same left position and width as original element.
    orgElement = $('.original');
    coordsOrgElement = orgElement.offset();
    leftOrgElement = coordsOrgElement.left;
    widthOrgElement = orgElement.css('width');
    $('.cloned').css('left',leftOrgElement+'px').css('top',0).css('width',widthOrgElement).show();
    $('.original').css('visibility','hidden');
  } else {
    // not scrolled past the menu; only show the original menu.
    $('.cloned').hide();
    $('.original').css('visibility','visible');
  }
}
//@ sourceURL=pen.js

function loadPeople_type()
{
    var formName = 'form1';
    var people = document[formName]['people'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?people='+people, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertPeople_type(xmlhttp);
    };
    xmlhttp.send(null);
}

function loadCityList()
{
    var formName = 'form1';
    var province = document[formName]['province'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&tag=true', true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertProvince(xmlhttp);
    };
    xmlhttp.send(null);
}

function loadPeople_Category()
{
    var formName = 'form1';
    var people = document[formName]['people'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?people='+people, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertProvince(xmlhttp);
    };
    xmlhttp.send(null);
}

function loadBarangayList()
{
    var formName = 'form1';
	var province = document[formName]['province'].value;
    var city = document[formName]['city'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&city='+city+'&tag=false', true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertBarangay(xmlhttp);
    };
    xmlhttp.send(null);
}

function loadCityList1()
{
    var formName = 'form1';
    var province = document[formName]['c_province'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&tag=true', true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertProvince1(xmlhttp);
    };
    xmlhttp.send(null);
}

function loadBarangayList1()
{
    var formName = 'form1';
	var province = document[formName]['c_province'].value;
    var city = document[formName]['c_city'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?province='+province+'&city='+city+'&tag=false', true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertBarangay1(xmlhttp);
    };
    xmlhttp.send(null);
}

function loadGeotype()
{
    var formName = 'form1';
    var geotype = document[formName]['geotype'].value;

    var xmlhttp = null;
    if(typeof XMLHttpRequest != 'udefined'){
        xmlhttp = new XMLHttpRequest();
    }else if(typeof ActiveXObject != 'undefined'){
        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }else
        throw new Error('You browser doesn\'t support ajax');

    xmlhttp.open('GET', '_insertvalues.php?geotype='+geotype, true);
    xmlhttp.onreadystatechange = function (){
        if(xmlhttp.readyState == 4 && xmlhttp.status==200)
			window.insertGeotype(xmlhttp);
    };
    xmlhttp.send(null);
}

function insertPeople_type(xhr){
    if(xhr.status == 200){
        document.getElementById('people_type').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function insertProvince(xhr){
    if(xhr.status == 200){
        document.getElementById('provinceContent').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function insertBarangay(xhr){
    if(xhr.status == 200){
        document.getElementById('barangayContent').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function insertProvince1(xhr){
    if(xhr.status == 200){
        document.getElementById('provinceContent1').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function insertBarangay1(xhr){
    if(xhr.status == 200){
        document.getElementById('barangayContent1').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

function insertGeotype(xhr){
    if(xhr.status == 200){
        document.getElementById('geotypeContent').innerHTML = xhr.responseText;
    }else
        throw new Error('Server has encountered an error\n'+
            'Error code = '+xhr.status);
}

var map;
var lastMarker;

function placeMarker(location)
{
	if (lastMarker != null)
	lastMarker.setMap(null);

	var marker = new google.maps.Marker({
	position: location,
	map: map
	});

	lastMarker = marker;
}

function mapa()
{
	var opts = {'center': new google.maps.LatLng(11.353, 122.363), 'zoom':20, 'mapTypeId': google.maps.MapTypeId.ROADMAP};
	map = new google.maps.Map(document.getElementById('mapdiv'),opts);


	google.maps.event.addListener(map,'click',function(event) {
	document.getElementById('clicklat').value = event.latLng.lat();
	document.getElementById('clicklng').value = event.latLng.lng();
	placeMarker(event.latLng);
	})

	google.maps.event.addListener(map,'mousemove',function(event) {
	document.getElementById('latspan').innerHTML = event.latLng.lat();
	document.getElementById('lngspan').innerHTML = event.latLng.lng();
	})

    // Try HTML5 geolocation
    if(navigator.geolocation)
    {
      navigator.geolocation.getCurrentPosition(function(position)
	  {
        var pos = new google.maps.LatLng(position.coords.latitude,
                  position.coords.longitude);

        var infowindow = new google.maps.InfoWindow({
         map: map,
         position: pos,
         content: 'This your current location :)'
	  });

      map.setCenter(pos);

      }
	  ,function()
	  {
        handleNoGeolocation(true);
      });
    }

    else{
    // Browser doesn't support Geolocation
      handleNoGeolocation(false);
    }
}

function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Error: The Geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t support geolocation.';
  }
 }

window.onload = mapa;
