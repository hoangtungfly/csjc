/*BEGIN MAP*/
var geocoder;
var map;
var infowindow;
function map_initialize(){
    geocoder=new google.maps.Geocoder();

    var mapOptions={
        zoom    : parseInt(jQuery('#zoom1').val()),
        center  : new google.maps.LatLng(jQuery('#ToadoX').val(),jQuery('#ToadoY').val()),
    };
    
    map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
    
    var marker = new google.maps.Marker({
        map:map,
        position:map.getCenter(),
        title:document.getElementById("mapinfo").value,
    });

    var infowindow = new google.maps.InfoWindow({
        "content"       : document.getElementById("mapinfo").value,
    });
    infowindow.open(map,marker);
}
function codeAddress(){
    geocoder.geocode(
            {'address':jQuery('#address').val()},
            function(results,status){
                if(status==google.maps.GeocoderStatus.OK){
                    map.setCenter(results[0].geometry.location);
                    var marker=new google.maps.Marker({map:map,position:results[0].geometry.location});
                }else{
                    alert('Geocode was not successful for the following reason: '+status);
                }
            }
    );
}
/*END MAP*/