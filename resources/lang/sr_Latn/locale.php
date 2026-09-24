<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Januar',
            'Februar',
            'Mart',
            'April',
            'Maj',
            'Jun',
            'Jul',
            'Avgust',
            'Septembar',
            'Oktobar',
            'Novembar',
            'Decembar',
        ],
        'monthAbbr' => [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Maj',
            'Jun',
            'Jul',
            'Avg',
            'Sep',
            'Okt',
            'Nov',
            'Dec',
        ],
        'dayOfWeek' => [
            'Nedelja',
            'Ponedeljak',
            'Utorak',
            'Sreda',
            'Četvrtak',
            'Petak',
            'Subota',
        ],
        'dayOfWeekAbbr' => [
            'Ned',
            'Pon',
            'Uto',
            'Sre',
            'Čet',
            'Pet',
            'Sub',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Sve',
            'inverse' => 'Obrni',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Pravougaoni izbor',
                'polygon' => 'Izbor lasom',
                'lineX' => 'Horizontalni izbor',
                'lineY' => 'Vertikalni izbor',
                'keep' => 'Zadrži izbor',
                'clear' => 'Obriši izbor',
            ],
        ],
        'dataView' => [
            'title' => 'Prikaz podataka',
            'lang' => [
                'Prikaz podataka',
                'Zatvori',
                'Osveži',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zumiranje',
                'back' => 'Poništi zumiranje',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Prebaci na linijski grafikon',
                'bar' => 'Prebaci na stubičasti grafikon',
                'stack' => 'Prikaži naslagano',
                'tiled' => 'Prikaži jedno pored drugog',
            ],
        ],
        'restore' => [
            'title' => 'Vrati na početno',
        ],
        'saveAsImage' => [
            'title' => 'Sačuvaj kao sliku',
            'lang' => [
                'Desni klik za čuvanje slike',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Kružni grafikon',
            'bar' => 'Stubičasti grafikon',
            'line' => 'Linijski grafikon',
            'scatter' => 'Dijagram rasejanja',
            'effectScatter' => 'Dijagram rasejanja sa efektom talasanja',
            'radar' => 'Radarski grafikon',
            'tree' => 'Stablo',
            'treemap' => 'Mapa stabla',
            'boxplot' => 'Kutijasti dijagram',
            'candlestick' => 'Grafikon japanskih sveća',
            'k' => 'K-linijski grafikon',
            'heatmap' => 'Toplotna mapa',
            'map' => 'Mapa',
            'parallel' => 'Grafikon paralelnih koordinata',
            'lines' => 'Linijski dijagram',
            'graph' => 'Graf odnosa',
            'sankey' => 'Sankijev dijagram',
            'funnel' => 'Levkasti grafikon',
            'gauge' => 'Merač',
            'pictorialBar' => 'Slikovni stubičasti grafikon',
            'themeRiver' => 'Grafikon tematske reke',
            'sunburst' => 'Sunčani dijagram',
            'custom' => 'Prilagođeni grafikon',
            'chart' => 'Grafikon',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ovo je grafikon o „{title}“',
            'withoutTitle' => 'Ovo je grafikon',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' tipa {seriesType} pod nazivom {seriesName}.',
                'withoutName' => ' tipa {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Broj serija: {seriesCount}.',
                'withName' => ' Serija {seriesId} je tipa {seriesType} i prikazuje {seriesName}.',
                'withoutName' => ' Serija {seriesId} je tipa {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Podaci su sledeći: ',
            'partialData' => 'Prvih {displayCnt} stavki: ',
            'withName' => 'vrednost za {name} je {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
