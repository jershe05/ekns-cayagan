import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getDatabase, ref, set, onValue} from "firebase/database";
import firebase from 'firebase/compat/app';
import 'firebase/compat/database';

import Leaflet from 'leaflet';
import 'leaflet-spin';

import geojsonFinder from "./geojson-finder";
window.axios = require('axios');
// import "spin.js/spin";

    // Import the functions you need from the SDKs you need

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDBygmTLYvUitCksp1VZdK7EfXMxteZd8U",
  authDomain: "ekns-332410.firebaseapp.com",
  databaseURL: "https://ekns-332410-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "ekns-332410",
  storageBucket: "ekns-332410.appspot.com",
  messagingSenderId: "86185869538",
  appId: "1:86185869538:web:c2da7082b9f0aba48adebb",
  measurementId: "G-344RZY63CL"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
function writeUserData() {
    const db = getDatabase();
    set(ref(db, 'leader/6/location_history'), {
        '2021-12-08 10:02:19 PM': {
            lat: 15.477101,
            lng: 120.593781
        },
        '2021-12-08 10:06:19 PM': {
            lat: 15.477034,
            lng: 120.593636
        },
        '2021-12-08 10:12:29 PM': {
            lat: 15.476987,
            lng: 120.593325
        },
        '2021-12-08 10:16:26 PM': {
            lat: 15.476951,
            lng: 120.593100
        },
        '2021-12-08 10:20:03 PM': {
            lat: 15.476899,
            lng: 120.592848
        },
        '2021-12-08 10:23:04 PM': {
            lat: 15.476894,
            lng: 120.592703
        },
        '2021-12-08 10:27:11 PM': {
            lat: 15.477008,
            lng: 120.592288
        },
        '2021-12-08 10:30:24 PM': {
            lat: 15.477075,
            lng: 120.592073
        },
        '2021-12-08 10:35:43 PM': {
            lat: 15.477142,
            lng: 120.591848
        },
        '2021-12-08 10:36:02 PM': {
            lat: 15.477095,
            lng: 120.591751
        },
        '2021-12-08 10:38:22 PM': {
            lat: 15.476945,
            lng: 120.591687
        },
        '2021-12-08 10:45:12 PM': {
            lat: 15.476785,
            lng: 120.591644
        },
        '2021-12-08 10:50:43 PM': {
            lat: 15.476723,
            lng: 120.591746
        },
        '2021-12-08 10:54:42 PM': {
            lat: 15.476661,
            lng: 120.591950
        },
        '2021-12-08 10:57:02 PM': {
            lat: 15.476598,
            lng: 120.592058
        },
        '2021-12-08 10:58:02 PM': {
            lat: 15.476612,
            lng: 120.592137
        },
        '2021-12-08 10:59:02 PM': {
            lat: 15.476541,
            lng: 120.592279
        },
        '2021-12-08 11:01:44 PM': {
            lat: 15.476472,
            lng: 120.592848
        },
        '2021-12-08 11:05:23 PM': {
            lat: 15.476317,
            lng: 120.592848
        },
        '2021-12-08 11:10:03 PM': {
            lat: 15.476082,
            lng: 120.592955
        },

        '2021-12-08 11:10:03 PM': {
            lat: 15.475884,
            lng: 120.593009
        },
        '2021-12-08 11:12:03 PM': {
            lat: 15.475620,
            lng: 120.593025
        },

        '2021-12-08 11:15:03 PM': {
            lat: 15.475325,
            lng: 120.593009
        },
        '2021-12-08 11:20:03 PM': {
            lat: 15.474836,
            lng: 120.592971
        },
        '2021-12-08 11:23:03 PM': {
            lat: 15.474544,
            lng: 120.592990
        },
        '2021-12-08 11:26:03 PM': {
            lat: 15.474538,
            lng:120.593286
        },
        '2021-12-08 11:29:03 PM': {
            lat: 15.474535,
            lng: 120.593576
        },
        '2021-12-08 11:30:03 PM': {
            lat: 15.474497,
            lng: 120.594155
        },
        '2021-12-08 11:33:03 PM': {
            lat: 15.474461,
            lng: 120.594415
        },
        '2021-12-08 11:35:03 PM': {
            lat: 15.474421,
            lng: 120.594668
        },
        '2021-12-08 11:37:03 PM': {
            lat: 15.474540,
            lng: 120.594952
        },
        '2021-12-08 11:39:03 PM': {
            lat: 15.474685,
            lng: 120.595430
        },
        '2021-12-08 11:40:03 PM': {
            lat: 15.475008,
            lng: 120.595639
        },
        '2021-12-08 11:42:03 PM': {
            lat: 15.475101,
            lng: 120.595808
        },
        '2021-12-08 11:45:03 PM': {
            lat: 15.474959,
            lng: 120.595907
        },
        '2021-12-08 11:48:03 PM': {
            lat: 15.474421,
            lng: 120.594668
        },
        '2021-12-08 11:49:03 PM': {
            lat: 15.474855,
            lng: 120.596016
        },
        '2021-12-08 11:51:03 PM': {
            lat: 15.474881,
            lng: 120.596088
        },
        '2021-12-08 11:53:03 PM': {
            lat: 15.475008,
            lng: 120.596179
        },

    });

}

//  writeUserData();



//  writeUserData(1, '15.4827722', '120.7120023');
const updateLocation = (data, L) => {


}

document.addEventListener('DOMContentLoaded', async () => {
    let tileLayerConfig = {
        url : 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
        config : {
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'sk.eyJ1IjoiamVyc2hlMDUiLCJhIjoiY2t3aGVzZ2NqMGh5cjJwbWRxMzdpN20xOSJ9._mfLykRzXw6FaRKBcHvjXw'
        }
    };

    let tileLayerConfigHistory = {
        url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    };


    let markers = [];
    let leaders;
    let marker;
    const setLocation = (dbKey, implementation) =>
    {
            const db = getDatabase();
            const locationRef = ref(db, dbKey);
            markers.forEach(function(value) {
                map.removeLayer(value);
            });

            if(marker)
            {
                map.removeLayer(marker);
            }
            onValue(locationRef, (snapshot) => {
                const data = snapshot.val();
                    implementation(data);
                });
    }

    const markLeadersLocation = async (implementation) => {
        const db = firebase.database();
        const leaders = (await db.ref(`leader`).once('value')).val();
        markers.forEach(function(value) {
            map.removeLayer(value);
        });

        implementation(leaders);
    }

     const getRealtimeLocation = (data) =>
    {

        marker = Leaflet.marker([data.lat, data.lng]).addTo(map)
            .openPopup();

            var latLngs = [ marker.getLatLng() ];
            var markerBounds = Leaflet.latLngBounds(latLngs);
            map.fitBounds(markerBounds);
    }

    const getLocationHistory = (data) =>
    {
        let counter = 0;
            $.each(data, function(key, value){
                if(value.lat) {
                    markers[counter] = Leaflet.marker([value.lat, value.lng]).addTo(map)
                    .bindPopup(key)
                    .openPopup();
                    counter++;
                }
            });
    }



    const getLeadersLocation = (data) =>
    {
        let counter = 0;
        $.each(leaders, function(key, value){
            let location = data[value.id];
            if(location) {
            markers[counter] = Leaflet.marker([location.current_location.lat, location.current_location.lng]).addTo(map)
                .bindPopup(value.name)
                .openPopup();
            }
            counter++;
        });

    }

    if(document.getElementById('currentLocationMap'))
    {

        var map = Leaflet.map('currentLocationMap').setView([15.4827729, 120.7120023], 13);
        Leaflet.tileLayer(tileLayerConfigHistory.url).addTo(map);

        $('#refresh-map').on('click', function() {
            map.invalidateSize();
        });

        setLocation('leader/' + $('#leader-id-location').val() + '/current_location', getRealtimeLocation);
    }

    if(document.getElementById('leadersLocationMap'))
    {
        var map = Leaflet.map('leadersLocationMap').setView([15.4827729, 120.7120023], 13);
        Leaflet.tileLayer(tileLayerConfigHistory.url).addTo(map);

    }

    $('#location-history-tab').on('click', function() {
        setLocation('leader/' + $('#leader-id-location').val() + '/location_history', getLocationHistory);
    });

    $('#real-time-location-tab').on('click', function() {
        setLocation('leader/' + $('#leader-id-location').val() + '/current_location', getRealtimeLocation);
    });

    if(document.getElementById('voters-map'))
    {
        let map = null;
        let currentGeoJSONLayer = null;
        let prevState = [
            {
                type: 'National',
                value: 'loadNational',
            }
        ];
        let currentClick = null;

        $(document).on('click', 'div:has(> canvas), button', function () {
            const attr = $(this).attr('wire:click');
            if (attr !== undefined) {
                currentClick = $(this);
            }
        });

        $(document).on('click', '#trigger-preview-btn', function () {
            $('#trigger-preview-btn').addClass('d-none');
            $('#trigger-preview-wire').trigger('click');
        });

        window.livewire.on('map', async ({map_type, args, labels, data}) => {
            if (map) map.remove();

            $('#trigger-preview-btn').addClass('d-none');

            map = Leaflet.map('voters-map', {
                zoom: 8,
                center: [15.950766, 120.505825],
            });

            Leaflet
                .tileLayer(tileLayerConfig.url, tileLayerConfig.config)
                .addTo(map);

            map.spin(true);

            console.time(map_type);
            const geojson = await geojsonFinder(data)[map_type](...args);
            console.timeEnd(map_type);

            if (map_type !== 'National') {
                $('#trigger-preview-btn').removeClass('d-none');
            }

            if (currentClick !== null) {
                if (prevState.filter((item) => item.type === map_type ).length === 0) {
                    $('#trigger-preview-wire').attr('wire:click', prevState[prevState.length - 1].value);
                    prevState.push({
                        type: map_type,
                        value: currentClick.attr('wire:click')
                    });
                } else {
                    prevState.length = prevState.map(item => item.type).indexOf(map_type);
                    if (prevState.length !== 0) {
                        $('#trigger-preview-wire').attr('wire:click', prevState[prevState.length - 1].value);
                    }
                }
            }

            currentGeoJSONLayer = Leaflet
                .geoJSON(geojson, {
                    style(feature) {
                        return {
                            fillOpacity: 1,
                            fillColor: feature.properties.colors[0]
                        }
                    }
                })
                .addTo(map);

            map.spin(false);
        });
    }

    window.livewire.on('markLeaderLocation', data => {

        window.axios.post('/admin/leaders/list', {
            type: data.type,
            code : data.code
        })
          .then((response) => {
                leaders = response.data;
                markLeadersLocation(getLeadersLocation);
          }, (error) => {

          });
    });
});

