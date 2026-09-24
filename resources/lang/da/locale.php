<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Januar',
            'Februar',
            'Marts',
            'April',
            'Maj',
            'Juni',
            'Juli',
            'August',
            'September',
            'Oktober',
            'November',
            'December',
        ],
        'monthAbbr' => [
            'Jan.',
            'Feb.',
            'Mar.',
            'Apr.',
            'Maj',
            'Jun.',
            'Jul.',
            'Aug.',
            'Sep.',
            'Okt.',
            'Nov.',
            'Dec.',
        ],
        'dayOfWeek' => [
            'Søndag',
            'Mandag',
            'Tirsdag',
            'Onsdag',
            'Torsdag',
            'Fredag',
            'Lørdag',
        ],
        'dayOfWeekAbbr' => [
            'Søn.',
            'Man.',
            'Tirs.',
            'Ons.',
            'Tors.',
            'Fre.',
            'Lør.',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Alle',
            'inverse' => 'Invertér',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Rektangulær markering',
                'polygon' => 'Lasso-markering',
                'lineX' => 'Vandret markering',
                'lineY' => 'Lodret markering',
                'keep' => 'Behold markeringer',
                'clear' => 'Ryd markeringer',
            ],
        ],
        'dataView' => [
            'title' => 'Datavisning',
            'lang' => [
                'Datavisning',
                'Luk',
                'Opdater',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zoom',
                'back' => 'Nulstil zoom',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Skift til linjediagram',
                'bar' => 'Skift til søjlediagram',
                'stack' => 'Stabl',
                'tiled' => 'Side om side',
            ],
        ],
        'restore' => [
            'title' => 'Gendan',
        ],
        'saveAsImage' => [
            'title' => 'Gem som billede',
            'lang' => [
                'Højreklik for at gemme billedet',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Cirkeldiagram',
            'bar' => 'Søjlediagram',
            'line' => 'Linjediagram',
            'scatter' => 'Punktdiagram',
            'effectScatter' => 'Punktdiagram med bølgeeffekt',
            'radar' => 'Radardiagram',
            'tree' => 'Trædiagram',
            'treemap' => 'Trækort',
            'boxplot' => 'Boksplot',
            'candlestick' => 'Lysestagediagram',
            'k' => 'K-linjediagram',
            'heatmap' => 'Varmekort',
            'map' => 'Kort',
            'parallel' => 'Diagram med parallelle koordinater',
            'lines' => 'Linjegraf',
            'graph' => 'Relationsgraf',
            'sankey' => 'Sankey-diagram',
            'funnel' => 'Tragtdiagram',
            'gauge' => 'Måler',
            'pictorialBar' => 'Piktogramsøjlediagram',
            'themeRiver' => 'Temaflodsdiagram',
            'sunburst' => 'Solstrålediagram',
            'custom' => 'Brugerdefineret diagram',
            'chart' => 'Diagram',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Dette er et diagram om "{title}"',
            'withoutTitle' => 'Dette er et diagram',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' af typen {seriesType} med navnet {seriesName}.',
                'withoutName' => ' af typen {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Det består af {seriesCount} serier.',
                'withName' => ' Serie {seriesId} er af typen {seriesType} og repræsenterer {seriesName}.',
                'withoutName' => ' Serie {seriesId} er af typen {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Dataene er som følger: ',
            'partialData' => 'De første {displayCnt} elementer er: ',
            'withName' => 'værdien for {name} er {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
