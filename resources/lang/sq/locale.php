<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Janar',
            'Shkurt',
            'Mars',
            'Prill',
            'Maj',
            'Qershor',
            'Korrik',
            'Gusht',
            'Shtator',
            'Tetor',
            'Nëntor',
            'Dhjetor',
        ],
        'monthAbbr' => [
            'Jan',
            'Shk',
            'Mar',
            'Pri',
            'Maj',
            'Qer',
            'Korr',
            'Gush',
            'Sht',
            'Tet',
            'Nën',
            'Dhj',
        ],
        'dayOfWeek' => [
            'E diel',
            'E hënë',
            'E martë',
            'E mërkurë',
            'E enjte',
            'E premte',
            'E shtunë',
        ],
        'dayOfWeekAbbr' => [
            'Die',
            'Hën',
            'Mar',
            'Mër',
            'Enj',
            'Pre',
            'Sht',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Të gjitha',
            'inverse' => 'Përmbys',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Përzgjedhje drejtkëndëshe',
                'polygon' => 'Përzgjedhje me laso',
                'lineX' => 'Përzgjedhje horizontale',
                'lineY' => 'Përzgjedhje vertikale',
                'keep' => 'Mbaj përzgjedhjet',
                'clear' => 'Pastro përzgjedhjet',
            ],
        ],
        'dataView' => [
            'title' => 'Pamja e të dhënave',
            'lang' => [
                'Pamja e të dhënave',
                'Mbyll',
                'Rifresko',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zmadho',
                'back' => 'Rivendos zmadhimin',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Kalo në grafik me vija',
                'bar' => 'Kalo në grafik me shtylla',
                'stack' => 'Grumbullo',
                'tiled' => 'Vendos krah për krah',
            ],
        ],
        'restore' => [
            'title' => 'Rikthe',
        ],
        'saveAsImage' => [
            'title' => 'Ruaj si imazh',
            'lang' => [
                'Kliko me të djathtën për të ruajtur imazhin',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Grafik rrethor',
            'bar' => 'Grafik me shtylla',
            'line' => 'Grafik me vija',
            'scatter' => 'Grafik shpërndarjeje',
            'effectScatter' => 'Grafik shpërndarjeje me valëzim',
            'radar' => 'Grafik radar',
            'tree' => 'Pemë',
            'treemap' => 'Hartë peme',
            'boxplot' => 'Diagram kutie',
            'candlestick' => 'Grafik me qirinj',
            'k' => 'Grafik me vija K',
            'heatmap' => 'Hartë nxehtësie',
            'map' => 'Hartë',
            'parallel' => 'Grafik me koordinata paralele',
            'lines' => 'Diagram me vija',
            'graph' => 'Graf marrëdhëniesh',
            'sankey' => 'Diagram Sankey',
            'funnel' => 'Grafik në formë hinke',
            'gauge' => 'Matës',
            'pictorialBar' => 'Shtylla figurative',
            'themeRiver' => 'Grafik lumi tematik',
            'sunburst' => 'Grafik sunburst',
            'custom' => 'Grafik i personalizuar',
            'chart' => 'Grafik',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ky është një grafik për "{title}"',
            'withoutTitle' => 'Ky është një grafik',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' i llojit {seriesType} me emrin {seriesName}.',
                'withoutName' => ' i llojit {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Përbëhet nga {seriesCount} seri.',
                'withName' => ' Seria {seriesId} është e llojit {seriesType} dhe përfaqëson {seriesName}.',
                'withoutName' => ' Seria {seriesId} është e llojit {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Të dhënat janë si më poshtë: ',
            'partialData' => '{displayCnt} elementet e para janë: ',
            'withName' => 'vlera për {name} është {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
