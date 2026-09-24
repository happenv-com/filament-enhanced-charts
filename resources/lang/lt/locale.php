<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Sausis',
            'Vasaris',
            'Kovas',
            'Balandis',
            'Gegužė',
            'Birželis',
            'Liepa',
            'Rugpjūtis',
            'Rugsėjis',
            'Spalis',
            'Lapkritis',
            'Gruodis',
        ],
        'monthAbbr' => [
            'Saus.',
            'Vas.',
            'Kov.',
            'Bal.',
            'Geg.',
            'Birž.',
            'Liep.',
            'Rugp.',
            'Rugs.',
            'Spal.',
            'Lapkr.',
            'Gruod.',
        ],
        'dayOfWeek' => [
            'Sekmadienis',
            'Pirmadienis',
            'Antradienis',
            'Trečiadienis',
            'Ketvirtadienis',
            'Penktadienis',
            'Šeštadienis',
        ],
        'dayOfWeekAbbr' => [
            'Sk',
            'Pr',
            'An',
            'Tr',
            'Kt',
            'Pn',
            'Št',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Visi',
            'inverse' => 'Invertuoti',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Žymėti stačiakampiu',
                'polygon' => 'Žymėti lasu',
                'lineX' => 'Žymėti horizontaliai',
                'lineY' => 'Žymėti vertikaliai',
                'keep' => 'Išlaikyti žymėjimą',
                'clear' => 'Išvalyti žymėjimą',
            ],
        ],
        'dataView' => [
            'title' => 'Duomenų rodinys',
            'lang' => [
                'Duomenų rodinys',
                'Uždaryti',
                'Atnaujinti',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Keisti mastelį',
                'back' => 'Atkurti mastelį',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Perjungti į linijinę diagramą',
                'bar' => 'Perjungti į stulpelinę diagramą',
                'stack' => 'Sudėtinis rodinys',
                'tiled' => 'Rodinys greta',
            ],
        ],
        'restore' => [
            'title' => 'Atkurti',
        ],
        'saveAsImage' => [
            'title' => 'Įrašyti kaip paveikslėlį',
            'lang' => [
                'Spustelėkite dešiniuoju pelės mygtuku, kad įrašytumėte paveikslėlį',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Skritulinė diagrama',
            'bar' => 'Stulpelinė diagrama',
            'line' => 'Linijinė diagrama',
            'scatter' => 'Taškinė diagrama',
            'effectScatter' => 'Taškinė diagrama su bangavimo efektu',
            'radar' => 'Radarinė diagrama',
            'tree' => 'Medis',
            'treemap' => 'Medžio žemėlapis',
            'boxplot' => 'Stačiakampė diagrama',
            'candlestick' => 'Žvakių diagrama',
            'k' => 'K linijos diagrama',
            'heatmap' => 'Šilumos žemėlapis',
            'map' => 'Žemėlapis',
            'parallel' => 'Lygiagrečiųjų koordinačių diagrama',
            'lines' => 'Linijų diagrama',
            'graph' => 'Ryšių grafas',
            'sankey' => 'Sankey diagrama',
            'funnel' => 'Piltuvo diagrama',
            'gauge' => 'Matuoklis',
            'pictorialBar' => 'Piktografinė stulpelinė diagrama',
            'themeRiver' => 'Temų upės diagrama',
            'sunburst' => 'Saulės spindulių diagrama',
            'custom' => 'Pasirinktinė diagrama',
            'chart' => 'Diagrama',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Tai diagrama apie „{title}“',
            'withoutTitle' => 'Tai diagrama',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ', tipas – {seriesType}, pavadinimas – {seriesName}.',
                'withoutName' => ', tipas – {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Ją sudaro duomenų serijų: {seriesCount}.',
                'withName' => ' Serija {seriesId} yra {seriesType}, vaizduojanti duomenis: {seriesName}.',
                'withoutName' => ' Serija {seriesId} yra {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Duomenys yra tokie: ',
            'partialData' => 'Pirmieji elementai ({displayCnt}): ',
            'withName' => '{name}: {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
