<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ],
        'monthAbbr' => [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
        ],
        'dayOfWeek' => [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ],
        'dayOfWeekAbbr' => [
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Zawng zawng',
            'inverse' => 'Letling',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Box hmangin thlang rawh',
                'polygon' => 'Lasso hmangin thlang rawh',
                'lineX' => 'Horizontal-in thlang rawh',
                'lineY' => 'Vertical-in thlang rawh',
                'keep' => 'Thlan te vawng tlat rawh',
                'clear' => 'Thlan te paih rawh',
            ],
        ],
        'dataView' => [
            'title' => 'Data enna',
            'lang' => [
                'Data enna',
                'Khar rawh',
                'Tihthar rawh',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zoom rawh',
                'back' => 'Zoom a tir angin siam leh rawh',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Line chart-ah thlak rawh',
                'bar' => 'Bar chart-ah thlak rawh',
                'stack' => 'Chhawp khawm rawh',
                'tiled' => 'Inkiangah dah rawh',
            ],
        ],
        'restore' => [
            'title' => 'A tir angin siam leh rawh',
        ],
        'saveAsImage' => [
            'title' => 'Thlalak angin save rawh',
            'lang' => [
                'Thlalak save turin mouse ding lam click rawh',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Pie chart',
            'bar' => 'Bar chart',
            'line' => 'Line chart',
            'scatter' => 'Scatter plot',
            'effectScatter' => 'Ripple scatter plot',
            'radar' => 'Radar chart',
            'tree' => 'Tree chart',
            'treemap' => 'Treemap',
            'boxplot' => 'Boxplot',
            'candlestick' => 'Candlestick chart',
            'k' => 'K-line chart',
            'heatmap' => 'Heat map',
            'map' => 'Ramlem',
            'parallel' => 'Parallel coordinate chart',
            'lines' => 'Lines chart',
            'graph' => 'Inzawmna graph',
            'sankey' => 'Sankey diagram',
            'funnel' => 'Funnel chart',
            'gauge' => 'Gauge chart',
            'pictorialBar' => 'Pictorial bar chart',
            'themeRiver' => 'Theme river chart',
            'sunburst' => 'Sunburst chart',
            'custom' => 'Mahni duh anga chart',
            'chart' => 'Chart',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Hei hi "{title}" chungchang chart a ni',
            'withoutTitle' => 'Hei hi chart a ni',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => '. A type chu {seriesType} a ni a, a hming chu {seriesName} a ni.',
                'withoutName' => '. A type chu {seriesType} a ni.',
            ],
            'multiple' => [
                'prefix' => '. Series {seriesCount} a awm.',
                'withName' => ' Series {seriesId}-na hi {seriesType} a ni a, {seriesName} a entir.',
                'withoutName' => ' Series {seriesId}-na hi {seriesType} a ni.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Data te chu hengte hi an ni: ',
            'partialData' => 'A hmasa ber {displayCnt} te chu: ',
            'withName' => '{name} data chu {value} a ni',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
