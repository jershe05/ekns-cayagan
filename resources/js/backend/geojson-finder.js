import { setupCache } from 'axios-cache-adapter'
import firebase from 'firebase/compat/app';
import 'firebase/compat/database';

const firebaseConfig = {
    apiKey: "AIzaSyDBygmTLYvUitCksp1VZdK7EfXMxteZd8U",
    authDomain: "ekns-332410.firebaseapp.com",
    databaseURL: "https://ekns-332410-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "ekns-332410",
    storageBucket: "ekns-332410.appspot.com",
    messagingSenderId: "86185869538",
    appId: "1:86185869538:web:c2da7082b9f0aba48adebb",
    measurementId: "G-344RZY63CL"
};;

firebase.initializeApp(firebaseConfig);
const db = firebase.database();

const geojsonFinder = ((mapData) => {
    const feature = {
        type: 'FeatureCollection',
        features: []
    };

    const islandObject = {
        Luzon: [1, 2, 3, 5, 7, 10, 11, 12],
        Visayas: [6, 9, 15],
        Mindanao: [0, 4, 8, 13, 14, 16]
    }

    const cityNameExemption = {
        Pozzorubio: 'Pozorrubio'
    }

    const regionSearchExemption = {
        'NCR': 'Metropolitan Manila',
        'BARMM': 'Autonomous Region of Muslim Mindanao'
    };

    const fetchData = async (key) => {
        if ('serviceWorker' in navigator && 'caches' in window) {
            const cache = await caches.open('json-cache');
            const response = await cache.match(key);

            // console.log(await response.text());
            if (response) {
                return response.json();
            }
            
            const geojsonFeatures = (await db.ref(key).once('value')).val();

            await cache.put(key, new Response(JSON.stringify(geojsonFeatures), {
                headers: {
                    'Content-Type': 'application/json'
                }
            }));

            return geojsonFeatures;
        } else {
            return (await db.ref(key).once('value')).val();
        }
    }

    const National = async () => {
        // const geojsonFeatures = fetchData('geojson/Region/features');
        // const geojsonFeatures = (await db.ref(key).once('value')).val();
        const geojsonFeatures = await fetchData('geojson/Region/features');

        for (const islandKey in islandObject) {
            /** @type {number[]} */
            const regionIds = islandObject[islandKey];

            regionIds.forEach((regionId) => {
                const geojsonFeature = geojsonFeatures[regionId];
                const {percentage, colors} = mapData.find(({name}) => name === islandKey);

                geojsonFeature.properties = {
                    ...geojsonFeature.properties,
                    percentage,
                    colors
                };

                feature.features.push(geojsonFeature);
            });
        }

        return feature;
    }

    const Island = async (islandKey) => {
        // const islandData = await getIsland(islandName);
        const geojsonFeatures = await fetchData(`geojson/Region/features`);

        const regionIds = islandObject[islandKey];

        regionIds.map((regionId) => {
            /** @type {number[]} */
            const geojsonFeature = geojsonFeatures[regionId];

            const { percentage, colors } = mapData.find((data) => {
                let { groups: { regionName } } = /\((?<regionName>.+)\)/.exec(data.region_name);

                if (Object.keys(regionSearchExemption).includes(regionName))
                    regionName = regionSearchExemption[regionName];

                return geojsonFeature.properties.REGION.toLocaleLowerCase().match(regionName.toLocaleLowerCase()) !== null;
            });

            geojsonFeature.properties = {
                ...geojsonFeature.properties,
                percentage,
                colors
            };

            feature.features.push(geojsonFeature);
        });

        return feature;
    }

    const Region = async (region_name) => {
        const geojsonFeatures = await fetchData(`geojson/Province/features`);

        geojsonFeatures.forEach((geojsonFeature) => {

            // console.log(region_name, mapData.province_name);
            mapData.forEach((data) => {
                let { groups: { regionName } } = /\((?<regionName>.+)\)/.exec(region_name);

                if (Object.keys(regionSearchExemption).includes(regionName))
                    regionName = regionSearchExemption[regionName];

                const isRegionMatched = geojsonFeature.properties.REGION.toLocaleLowerCase().match(regionName.toLocaleLowerCase()) !== null;
                const isProvinceMatched = geojsonFeature.properties.PROVINCE.toLocaleLowerCase() === data.province_name.toLocaleLowerCase();

                // console.log(isProvinceMatched, isProvinceMatched);
                if (isRegionMatched && isProvinceMatched) {
                    geojsonFeature.properties = {
                        ...geojsonFeature.properties,
                        percentage: data.percentage,
                        colors: data.colors
                    };

                    feature.features.push(geojsonFeature);
                }
            });
        });

        return feature;
    }

    const Province = async (region_name, province_name) => {
        const geojsonFeatures = await fetchData(`geojson/City/features`);

        geojsonFeatures.forEach((geojsonFeature) => {
            mapData.forEach((data) => {
                let { groups: { regionName } } = /\((?<regionName>.+)\)/.exec(region_name);

                if (Object.keys(regionSearchExemption).includes(regionName))
                    regionName = regionSearchExemption[regionName];

                const isRegionMatched = geojsonFeature.properties.REGION.toLocaleLowerCase().match(regionName.toLocaleLowerCase()) !== null;
                const isProvinceMatched = geojsonFeature.properties.PROVINCE.toLocaleLowerCase() === province_name.toLocaleLowerCase();

                /**@type {string} */
                let cityNameToMatch = geojsonFeature.properties.NAME_2;

                if (cityNameToMatch.match(/\s(city)/i)) {
                    cityNameToMatch = cityNameToMatch.replace(/\s(city)/i, '');
                }

                if (Object.keys(cityNameExemption).includes(cityNameToMatch)) {
                    cityNameToMatch = cityNameExemption[cityNameToMatch];
                }

                const isCityMatched = data.city_name.match(new RegExp(cityNameToMatch, 'gi')) !== null;

                if (isRegionMatched && isProvinceMatched && isCityMatched) {
                    geojsonFeature.properties = {
                        ...geojsonFeature.properties,
                        percentage: data.percentage,
                        colors: data.colors
                    };

                    feature.features.push(geojsonFeature);
                }
            });
        });

        return feature;
    }

    const City = async (region_name, province_name, city_name) => {
        const geojsonFeatures = await fetchData(`geojson/Barangay/features`);
        // const geojsonFeatures = (await db.ref(`geojson/Barangay/features`).once('value')).val();

        geojsonFeatures.forEach((geojsonFeature) => {
            mapData.forEach((data) => {
                let { groups: { regionName } } = /\((?<regionName>.+)\)/.exec(region_name);

                if (Object.keys(regionSearchExemption).includes(regionName))
                    regionName = regionSearchExemption[regionName];

                const isRegionMatched = geojsonFeature.properties.REGION.toLocaleLowerCase().match(regionName.toLocaleLowerCase()) !== null;
                const isProvinceMatched = geojsonFeature.properties.PROVINCE.toLocaleLowerCase() === province_name.toLocaleLowerCase();

                /**@type {string} */
                let cityNameToMatch = geojsonFeature.properties.NAME_2;

                if (cityNameToMatch.match(/\s(city)/i)) {
                    cityNameToMatch = cityNameToMatch.replace(/\s(city)/i, '');
                }

                if (Object.keys(cityNameExemption).includes(cityNameToMatch)) {
                    cityNameToMatch = cityNameExemption[cityNameToMatch];
                }

                const isCityMatched = city_name.match(new RegExp(cityNameToMatch, 'gi')) !== null;

                if (isRegionMatched && isProvinceMatched && isCityMatched) {
                    const barangayRegex = new RegExp(geojsonFeature.properties.NAME_3.split('.').reduce(function ($oldBarangayName, $currentBarangayName) {
                        $currentBarangayName = $currentBarangayName.replace('-', "\\-");
                        $currentBarangayName = $currentBarangayName.replace('/', "\\/");
                        $currentBarangayName = $currentBarangayName.replace('(', "\\(");
                        $currentBarangayName = $currentBarangayName.replace(')', "\\)");

                        return $oldBarangayName + $currentBarangayName.trim() + "\\.?\\s?";
                    }, ""));

                    if (data.barangay_name.match(barangayRegex) !== null) {
                        geojsonFeature.properties = {
                            ...geojsonFeature.properties,
                            percentage: data.percentage,
                            colors: data.colors
                        };

                        feature.features.push(geojsonFeature);
                    }
                }
            });
        });

        return feature;
    }

    return {
        National,
        Island,
        Region,
        Province,
        City
    }
});

export default geojsonFinder;
