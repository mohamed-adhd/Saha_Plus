<?php
/*
 * Map settings. Leaflet is only the map LIBRARY: the background images ("tiles")
 * come from a provider, and each provider has its own terms of use.
 *
 * To change the default background, change 'default' to 'satellite' or 'streets'.
 * To add a provider, add an entry to 'providers' (url + attribution are mandatory).
 */
return [
    'default' => 'satellite',

    'providers' => [
        // Esri World Imagery. Attribution is mandatory. For anything beyond a small school
        // prototype, create a free ArcGIS developer account and use Esri's keyed service.
        'satellite' => [
            'label' => 'Satellite',
            'url' => 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            'attribution' => 'Imagery &copy; Esri, Maxar, Earthstar Geographics, and the GIS User Community',
            'maxZoom' => 17,
        ],
        // OpenStreetMap standard tiles: free for light use, attribution mandatory.
        'streets' => [
            'label' => 'Streets',
            'url' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            'maxZoom' => 18,
        ],
    ],

    // Tunisia only: the map cannot leave this rectangle [south, west] – [north, east].
    'bounds' => [[Governorate::LAT_MIN, Governorate::LNG_MIN], [Governorate::LAT_MAX, Governorate::LNG_MAX]],
];
