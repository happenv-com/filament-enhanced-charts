<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Rêbendan',
            'Sibat',
            'Adar',
            'Nîsan',
            'Gulan',
            'Hezîran',
            'Tîrmeh',
            'Tebax',
            'Îlon',
            'Cotmeh',
            'Mijdar',
            'Berfanbar',
        ],
        'monthAbbr' => [
            'Rbn',
            'Sbt',
            'Adr',
            'Nsn',
            'Gln',
            'Hzr',
            'Trm',
            'Tbx',
            'Îln',
            'Cot',
            'Mjd',
            'Brf',
        ],
        'dayOfWeek' => [
            'Yekşem',
            'Duşem',
            'Sêşem',
            'Çarşem',
            'Pêncşem',
            'Înî',
            'Şemî',
        ],
        'dayOfWeekAbbr' => [
            'Yşm',
            'Dşm',
            'Sşm',
            'Çşm',
            'Pşm',
            'Înî',
            'Şem',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Hemû',
            'inverse' => 'Berevajî',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Bi çarçoveyê hilbijêre',
                'polygon' => 'Bi lasoyê hilbijêre',
                'lineX' => 'Bi asoyî hilbijêre',
                'lineY' => 'Bi stûnî hilbijêre',
                'keep' => 'Hilbijartinan biparêze',
                'clear' => 'Hilbijartinan paqij bike',
            ],
        ],
        'dataView' => [
            'title' => 'Dîtina daneyan',
            'lang' => [
                'Dîtina daneyan',
                'Bigire',
                'Nû bike',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Mezin bike',
                'back' => 'Mezinkirinê vegerîne',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Derbasî grafîka xêzî bibe',
                'bar' => 'Derbasî grafîka stûnî bibe',
                'stack' => 'Li ser hev rêz bike',
                'tiled' => 'Li kêleka hev rêz bike',
            ],
        ],
        'restore' => [
            'title' => 'Vegerîne',
        ],
        'saveAsImage' => [
            'title' => 'Wek wêne tomar bike',
            'lang' => [
                'Ji bo tomarkirina wêneyê klîka rast bike',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Grafîka gilover',
            'bar' => 'Grafîka stûnî',
            'line' => 'Grafîka xêzî',
            'scatter' => 'Grafîka belavbûnê',
            'effectScatter' => 'Grafîka belavbûnê ya bi pêlan',
            'radar' => 'Grafîka radarê',
            'tree' => 'Dar',
            'treemap' => 'Nexşeya darê',
            'boxplot' => 'Grafîka qutîkî',
            'candlestick' => 'Grafîka mûman',
            'k' => 'Grafîka K-line',
            'heatmap' => 'Nexşeya germahiyê',
            'map' => 'Nexşe',
            'parallel' => 'Grafîka koordînatên paralel',
            'lines' => 'Grafîka xetan',
            'graph' => 'Grafîka têkiliyan',
            'sankey' => 'Diyagrama Sankey',
            'funnel' => 'Grafîka hunî',
            'gauge' => 'Grafîka pîvanê',
            'pictorialBar' => 'Grafîka stûnî ya wêneyî',
            'themeRiver' => 'Grafîka çemê mijaran',
            'sunburst' => 'Grafîka Sunburst',
            'custom' => 'Grafîka taybet',
            'chart' => 'Grafîk',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ev grafîkek e li ser "{title}"',
            'withoutTitle' => 'Ev grafîkek e',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ', cureyê wê {seriesType} e û navê wê {seriesName} e.',
                'withoutName' => ', cureyê wê {seriesType} e.',
            ],
            'multiple' => [
                'prefix' => '. Ew ji {seriesCount} rêzeyan pêk tê.',
                'withName' => ' Rêzeya {seriesId} {seriesType} e û {seriesName} nîşan dide.',
                'withoutName' => ' Rêzeya {seriesId} {seriesType} e.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Dane wiha ne: ',
            'partialData' => '{displayCnt} hêmanên pêşîn ev in: ',
            'withName' => 'daneya {name} {value} e',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
