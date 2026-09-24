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
            'Juni',
            'Juli',
            'August',
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
            'Aug',
            'Sep',
            'Okt',
            'Nov',
            'Dec',
        ],
        'dayOfWeek' => [
            'Nedjelja',
            'Ponedjeljak',
            'Utorak',
            'Srijeda',
            'Četvrtak',
            'Petak',
            'Subota',
        ],
        'dayOfWeekAbbr' => [
            'Ned',
            'Pon',
            'Uto',
            'Sri',
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
                'rect' => 'Pravougaona selekcija',
                'polygon' => 'Laso selekcija',
                'lineX' => 'Horizontalna selekcija',
                'lineY' => 'Vertikalna selekcija',
                'keep' => 'Zadrži selekciju',
                'clear' => 'Očisti selekciju',
            ],
        ],
        'dataView' => [
            'title' => 'Prikaz podataka',
            'lang' => [
                'Prikaz podataka',
                'Zatvori',
                'Osvježi',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zumiraj',
                'back' => 'Poništi zumiranje',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Prebaci na linijski grafikon',
                'bar' => 'Prebaci na stubičasti grafikon',
                'stack' => 'Naslagani prikaz',
                'tiled' => 'Uporedni prikaz',
            ],
        ],
        'restore' => [
            'title' => 'Vrati',
        ],
        'saveAsImage' => [
            'title' => 'Sačuvaj kao sliku',
            'lang' => [
                'Kliknite desnom tipkom miša da sačuvate sliku',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Kružni grafikon',
            'bar' => 'Stubičasti grafikon',
            'line' => 'Linijski grafikon',
            'scatter' => 'Dijagram raspršenja',
            'effectScatter' => 'Dijagram raspršenja s efektom talasa',
            'radar' => 'Radarski grafikon',
            'tree' => 'Stablo',
            'treemap' => 'Mapa stabla',
            'boxplot' => 'Kutijasti dijagram',
            'candlestick' => 'Svijećni grafikon',
            'k' => 'K-linijski grafikon',
            'heatmap' => 'Toplotna mapa',
            'map' => 'Mapa',
            'parallel' => 'Grafikon paralelnih koordinata',
            'lines' => 'Grafikon linija',
            'graph' => 'Graf odnosa',
            'sankey' => 'Sankey dijagram',
            'funnel' => 'Grafikon lijevka',
            'gauge' => 'Mjerač',
            'pictorialBar' => 'Slikovni stubičasti grafikon',
            'themeRiver' => 'Tematski riječni grafikon',
            'sunburst' => 'Grafikon sunčevih zraka',
            'custom' => 'Prilagođeni grafikon',
            'chart' => 'Grafikon',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ovo je grafikon na temu „{title}“',
            'withoutTitle' => 'Ovo je grafikon',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' tipa „{seriesType}“ s nazivom {seriesName}.',
                'withoutName' => ' tipa „{seriesType}“.',
            ],
            'multiple' => [
                'prefix' => '. Sastoji se od {seriesCount} serija.',
                'withName' => ' Serija {seriesId} je tipa „{seriesType}“ i predstavlja {seriesName}.',
                'withoutName' => ' Serija {seriesId} je tipa „{seriesType}“.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Podaci su sljedeći: ',
            'partialData' => 'Prvih {displayCnt} stavki: ',
            'withName' => 'vrijednost za {name} je {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
