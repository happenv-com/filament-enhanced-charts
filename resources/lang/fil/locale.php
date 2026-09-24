<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Enero',
            'Pebrero',
            'Marso',
            'Abril',
            'Mayo',
            'Hunyo',
            'Hulyo',
            'Agosto',
            'Setyembre',
            'Oktubre',
            'Nobyembre',
            'Disyembre',
        ],
        'monthAbbr' => [
            'Ene',
            'Peb',
            'Mar',
            'Abr',
            'May',
            'Hun',
            'Hul',
            'Ago',
            'Set',
            'Okt',
            'Nob',
            'Dis',
        ],
        'dayOfWeek' => [
            'Linggo',
            'Lunes',
            'Martes',
            'Miyerkules',
            'Huwebes',
            'Biyernes',
            'Sabado',
        ],
        'dayOfWeekAbbr' => [
            'Lin',
            'Lun',
            'Mar',
            'Miy',
            'Huw',
            'Biy',
            'Sab',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Lahat',
            'inverse' => 'Baligtarin',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Pagpili gamit ang kahon',
                'polygon' => 'Pagpili gamit ang lasso',
                'lineX' => 'Pahalang na pagpili',
                'lineY' => 'Patayong pagpili',
                'keep' => 'Panatilihin ang mga pinili',
                'clear' => 'I-clear ang mga pinili',
            ],
        ],
        'dataView' => [
            'title' => 'Tingnan ang data',
            'lang' => [
                'Tingnan ang data',
                'Isara',
                'I-refresh',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Mag-zoom',
                'back' => 'I-reset ang zoom',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Lumipat sa line chart',
                'bar' => 'Lumipat sa bar chart',
                'stack' => 'I-stack',
                'tiled' => 'Ilagay nang magkatabi',
            ],
        ],
        'restore' => [
            'title' => 'Ibalik',
        ],
        'saveAsImage' => [
            'title' => 'I-save bilang larawan',
            'lang' => [
                'I-right click para i-save ang larawan',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Pie chart',
            'bar' => 'Bar chart',
            'line' => 'Line chart',
            'scatter' => 'Scatter plot',
            'effectScatter' => 'Scatter plot na may ripple effect',
            'radar' => 'Radar chart',
            'tree' => 'Tree diagram',
            'treemap' => 'Treemap',
            'boxplot' => 'Boxplot',
            'candlestick' => 'Candlestick chart',
            'k' => 'K-line chart',
            'heatmap' => 'Heat map',
            'map' => 'Mapa',
            'parallel' => 'Tsart ng parallel coordinates',
            'lines' => 'Grap ng mga linya',
            'graph' => 'Grap ng ugnayan',
            'sankey' => 'Sankey diagram',
            'funnel' => 'Funnel chart',
            'gauge' => 'Gauge',
            'pictorialBar' => 'Pictorial bar chart',
            'themeRiver' => 'Theme river chart',
            'sunburst' => 'Sunburst chart',
            'custom' => 'Custom na tsart',
            'chart' => 'Tsart',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ito ay isang tsart tungkol sa "{title}"',
            'withoutTitle' => 'Ito ay isang tsart',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' na may uring {seriesType} at pangalang {seriesName}.',
                'withoutName' => ' na may uring {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Binubuo ito ng {seriesCount} serye.',
                'withName' => ' Ang serye {seriesId} ay isang {seriesType} na kumakatawan sa {seriesName}.',
                'withoutName' => ' Ang serye {seriesId} ay isang {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Ang data ay ang sumusunod: ',
            'partialData' => 'Ang unang {displayCnt} item ay: ',
            'withName' => 'ang data para sa {name} ay {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
