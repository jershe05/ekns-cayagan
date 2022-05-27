// import axios from 'axios';
// import {Loader, LoaderOptions} from 'google-maps';
// import * as turf from '@turf/turf';
// import geojsonList from './geojson.json';
import Leaflet from 'leaflet';
// import 'leaflet/src'


const initMap = () => {
  // return L.map(document.getElementById('map'), {
  //   zoom: 8,
  //   center: [15.950766, 120.505825],
  // });
  // const loader = new Loader('AIzaSyDBygmTLYvUitCksp1VZdK7EfXMxteZd8U', {});
  // const google = await loader.load();
  
  // return new google.maps.Map(document.getElementById("map"), {
  //   zoom: 8,
  //   center: { lat: 15.950766, lng: 120.505825 },
  // });
}

document.addEventListener('DOMContentLoaded', () => {
  let map = null;
  let currentGeoJSONLayer = null;
  
  window.livewire.on('map', async ({ geojson, percentage, color }) => {
    if (map) map.remove();

    map = Leaflet.map('voters-map', {
      zoom: 8,
      center: [15.950766, 120.505825],
    });
    
    Leaflet
      .tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {
        foo: 'bar', 
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      })
      .addTo(map);

    // Leaflet
    //   .tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    //     attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    //     maxZoom: 18,
    //     id: 'mapbox/streets-v11',
    //     tileSize: 512,
    //     zoomOffset: -1,
    //     accessToken: 'sk.eyJ1IjoiamVyc2hlMDUiLCJhIjoiY2t3aGVzZ2NqMGh5cjJwbWRxMzdpN20xOSJ9._mfLykRzXw6FaRKBcHvjXw'
    //   })
    //   .addTo(map);


    // map = initMap();

    // if (currentGeoJSONLayer) {
    //   currentGeoJSONLayer.clearLayers();
    // }
    
    currentGeoJSONLayer = Leaflet
      .geoJSON(geojson, {
        style(feature) {
          return {
            fillOpacity: 1,
            fillColor: color
          }
        }
      })
      .addTo(map);

    // map.data.addGeoJson(geojson);

    // map.data.setStyle((feature) => {
    //   feature.forEachProperty((key) => {
    //     console.log(key);
    //   });

      // return {
      //   fillOpacity: 1,
      //   fillColor: color
      // }
    // });

    // console.log(polygons);

    // const polygons = new google.maps.Polygon({
    //   paths: coords
    // });

    // polygons.setMap(map);
    // console.log(polygons);
  });
});

// const createForm = async () => {
    
// }

// const removeGeojsonLayer = (map) => {
//   map.data.forEach(function(feature) {
//     // filter...
//     map.data.remove(feature);
//   });
// }

// window.addEventListener('load', async () => {
//   $('#loading-modal').modal({
//     backdrop: 'static',
//     keyboard: false
//   });
//   const map = await initMap();
  
//   $('#loading-modal').modal('show');
//   const { data: regionsGeojsonData } = await axios.get(`/api/v1/geojson/regions`);
//   map.data.addGeoJson(regionsGeojsonData);
//   $('#loading-modal').modal('hide');
  
//   const {data: regionsAddressData} = await axios.get('/api/v1/address/regions');

//   $('#address').append(`
//     <select class="form-control" name="region" id="region">
//       <option value="" selected hidden disabled>Select Region</option>
//       ${regionsAddressData.map((regionAddress) => `<option value="${regionAddress.id}">${regionAddress.region_description}</option>`).join('')}
//     </select>
//   `);

//   $('#region').on('change', async function () {
//     const regionId = $(this).val();
//     const {data: provincesAddressData} = await axios.get(`/api/v1/address/regions/${regionId}/provinces`);

//     $('#province').remove();
//     $('#municities').remove();
//     $('#barangays').remove();

//     $('#loading-modal').modal('show');
//     const { data: provincesGeojsonData } = await axios.get(`/api/v1/geojson/regions/${regionId}/provinces`);
//     removeGeojsonLayer(map);
//     map.data.addGeoJson(provincesGeojsonData);
//     $('#loading-modal').modal('hide');

//     $('#address').append(`
//       <select class="form-control" name="province" id="province" data-region_id=${regionId}>
//         <option value="" selected hidden disabled>Select Province</option>
//         ${provincesAddressData.map((provincesAddress) => `<option value="${provincesAddress.id}">${provincesAddress.province_description}</option>`)}
//       </select>
//     `);
//   });

//   $(document).on('change', '#province', async function () {
//     const regionId = $(this).data('region_id');
//     const provinceId = $(this).val();
//     const {data: municitiesAddressData} = await axios.get(`/api/v1/address/regions/${regionId}/provinces/${provinceId}/cities`);
    
//     $('#municities').remove();
//     $('#barangays').remove();

//     $('#loading-modal').modal('show');
//     const { data: municitiesGeojsonData } = await axios.get(`/api/v1/geojson/regions/${regionId}/provinces/${provinceId}/cities`);
//     removeGeojsonLayer(map);
//     map.data.addGeoJson(municitiesGeojsonData);
//     $('#loading-modal').modal('hide');

//     $('#address').append(`
//       <select class="form-control" name="municities" id="municities" data-region_id=${regionId} data-province_id=${provinceId}>
//         <option value="" selected hidden disabled>Select Municipality/City</option>
//         ${municitiesAddressData.map((municitiesAddress) => `
//           <option value="${municitiesAddress.id}">${municitiesAddress.city_municipality_description}</option>
//         `)}
//       </select>
//     `);
//   });

//   $(document).on('change', '#municities', async function () {
//     const regionId = $(this).data('region_id');
//     const provinceId = $(this).data('province_id');
//     const municityId = $(this).val();
//     const {data: barangaysAddressData} = await axios.get(`/api/v1/address/regions/${regionId}/provinces/${provinceId}/cities/${municityId}/barangays`);
    
//     $('#barangays').remove();

//     $('#loading-modal').modal('show');
//     const { data: barangaysGeojsonData } = await axios.get(`/api/v1/geojson/regions/${regionId}/provinces/${provinceId}/cities/${municityId}/barangays`);
//     removeGeojsonLayer(map);
//     map.data.addGeoJson(barangaysGeojsonData);
//     $('#loading-modal').modal('hide');

//     $('#address').append(`
//       <select class="form-control" name="barangays" id="barangays" data-region_id=${regionId} data-province_id=${provinceId} data-municity_id=${municityId}>
//         <option value="" selected hidden disabled>Select Barangay</option>
//         ${barangaysAddressData.map((barangaysAddress) => `
//           <option value="${barangaysAddress.id}">${barangaysAddress.barangay_description}</option>
//         `)}
//       </select>
//     `);
//   });

//   $(document).on('change', '#barangays', async function () {
//     const regionId = $(this).data('region_id');
//     const provinceId = $(this).data('province_id');
//     const municityId = $(this).data('municity_id');
//     const barangayId = $(this).val();

//     $('#loading-modal').modal('show');
//     const { data: barangayGeojsonData } = await axios.get(`/api/v1/geojson/regions/${regionId}/provinces/${provinceId}/cities/${municityId}/barangays/${barangayId}`);
//     removeGeojsonLayer(map);
//     map.data.addGeoJson(barangayGeojsonData);
//     $('#loading-modal').modal('hide');
//   });
// });
