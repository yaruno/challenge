;(function($) {
    $(function() {
        $('.js-do-search').click(function(event) {
            event.preventDefault();



            var search = $('#search-input').val();
            

            $.ajax({
                url: 'api/search?q=' + search,
                type: 'GET',
                success: function(data) {
                    // TODO: implement showing of data.

                    var content = '';


                    //If map div exists, remove it and create a new one, set height and other leaflet configs
                    //add markers from data to map
                    if ($("#map")){
                        
                        $("#map").remove();
                        $(".results").append("<div id='map'></div>");
                        $('#map').css('height','300px');
                    }
                    var map = L.map('map').setView([61.505, 25], 4);

                    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
                        maxZoom: 18
                    }).addTo(map);


                    //content += '<tbody>'; -- **superfluous**
                    for (var i = 0; i < data.length; i++) {
                        content += '<tr>';
                        content += '<td>' + data[i].postcode + '</td>';
                        content += '<td>' + data[i].city + '</td>';
                        content += '<td>' + data[i].pop + '</td>';
                        content += '</tr>';



                        var marker_content = "<b>Pop: "+data[i].pop +"</b> <br> Postcode: " + data[i].postcode +"<br> City: "+ data[i].city;

                        var marker = L.marker([data[i].lat, data[i].lon]).bindPopup(marker_content).addTo(map);


                    }
                  
                     $('#results-table').html(content);
                    console.log(data);
                }
            });
        });
    });
})(window.jQuery);