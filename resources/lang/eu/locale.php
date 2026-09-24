<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Urtarrila',
            'Otsaila',
            'Martxoa',
            'Apirila',
            'Maiatza',
            'Ekaina',
            'Uztaila',
            'Abuztua',
            'Iraila',
            'Urria',
            'Azaroa',
            'Abendua',
        ],
        'monthAbbr' => [
            'Urt.',
            'Ots.',
            'Mar.',
            'Api.',
            'Mai.',
            'Eka.',
            'Uzt.',
            'Abu.',
            'Ira.',
            'Urr.',
            'Aza.',
            'Abe.',
        ],
        'dayOfWeek' => [
            'Igandea',
            'Astelehena',
            'Asteartea',
            'Asteazkena',
            'Osteguna',
            'Ostirala',
            'Larunbata',
        ],
        'dayOfWeekAbbr' => [
            'Ig.',
            'Al.',
            'Ar.',
            'Az.',
            'Og.',
            'Or.',
            'Lr.',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Guztiak',
            'inverse' => 'Alderantzikatu',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Laukizuzen bidezko hautapena',
                'polygon' => 'Lazo bidezko hautapena',
                'lineX' => 'Hautapen horizontala',
                'lineY' => 'Hautapen bertikala',
                'keep' => 'Mantendu hautapenak',
                'clear' => 'Garbitu hautapenak',
            ],
        ],
        'dataView' => [
            'title' => 'Datuen ikuspegia',
            'lang' => [
                'Datuen ikuspegia',
                'Itxi',
                'Freskatu',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zooma',
                'back' => 'Berrezarri zooma',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Aldatu lerro-diagramara',
                'bar' => 'Aldatu barra-diagramara',
                'stack' => 'Pilatu',
                'tiled' => 'Jarri alboz albo',
            ],
        ],
        'restore' => [
            'title' => 'Leheneratu',
        ],
        'saveAsImage' => [
            'title' => 'Gorde irudi gisa',
            'lang' => [
                'Egin eskuineko klik irudia gordetzeko',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Diagrama zirkularra',
            'bar' => 'Barra-diagrama',
            'line' => 'Lerro-diagrama',
            'scatter' => 'Sakabanatze-diagrama',
            'effectScatter' => 'Uhin-efektudun sakabanatze-diagrama',
            'radar' => 'Radar-diagrama',
            'tree' => 'Zuhaitza',
            'treemap' => 'Zuhaitz-mapa',
            'boxplot' => 'Kutxa-diagrama',
            'candlestick' => 'Kandela-diagrama',
            'k' => 'K lerroen diagrama',
            'heatmap' => 'Bero-mapa',
            'map' => 'Mapa',
            'parallel' => 'Koordenatu paraleloen diagrama',
            'lines' => 'Lerro-grafikoa',
            'graph' => 'Erlazio-grafoa',
            'sankey' => 'Sankey diagrama',
            'funnel' => 'Inbutu-diagrama',
            'gauge' => 'Neurgailua',
            'pictorialBar' => 'Irudizko barra-diagrama',
            'themeRiver' => 'Gai-ibaiaren diagrama',
            'sunburst' => 'Eguzki-izpien diagrama',
            'custom' => 'Diagrama pertsonalizatua',
            'chart' => 'Diagrama',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Hau "{title}" gaiari buruzko diagrama bat da',
            'withoutTitle' => 'Hau diagrama bat da',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => '. {seriesType} motakoa da, eta {seriesName} du izena.',
                'withoutName' => '. {seriesType} motakoa da.',
            ],
            'multiple' => [
                'prefix' => '. {seriesCount} serie ditu.',
                'withName' => ' {seriesId}. seriea {seriesType} motakoa da, eta {seriesName} adierazten du.',
                'withoutName' => ' {seriesId}. seriea {seriesType} motakoa da.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Datuak hauek dira: ',
            'partialData' => 'Lehen {displayCnt} elementuak hauek dira: ',
            'withName' => '{name} datuaren balioa {value} da',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
