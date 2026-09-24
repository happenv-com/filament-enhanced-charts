<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Siječanj',
            'Veljača',
            'Ožujak',
            'Travanj',
            'Svibanj',
            'Lipanj',
            'Srpanj',
            'Kolovoz',
            'Rujan',
            'Listopad',
            'Studeni',
            'Prosinac',
        ],
        'monthAbbr' => [
            'Sij',
            'Velj',
            'Ožu',
            'Tra',
            'Svi',
            'Lip',
            'Srp',
            'Kol',
            'Ruj',
            'Lis',
            'Stu',
            'Pro',
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
                'rect' => 'Pravokutni odabir',
                'polygon' => 'Laso odabir',
                'lineX' => 'Vodoravni odabir',
                'lineY' => 'Okomiti odabir',
                'keep' => 'Zadrži odabire',
                'clear' => 'Poništi odabire',
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
                'bar' => 'Prebaci na stupčasti grafikon',
                'stack' => 'Složi',
                'tiled' => 'Prikaži jedan uz drugi',
            ],
        ],
        'restore' => [
            'title' => 'Vrati',
        ],
        'saveAsImage' => [
            'title' => 'Spremi kao sliku',
            'lang' => [
                'Desni klik za spremanje slike',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Tortni grafikon',
            'bar' => 'Stupčasti grafikon',
            'line' => 'Linijski grafikon',
            'scatter' => 'Raspršeni grafikon',
            'effectScatter' => 'Raspršeni grafikon s efektom valova',
            'radar' => 'Radarski grafikon',
            'tree' => 'Stablo',
            'treemap' => 'Stablasta karta',
            'boxplot' => 'Kutijasti dijagram',
            'candlestick' => 'Grafikon svijeća',
            'k' => 'K-linijski grafikon',
            'heatmap' => 'Toplinska karta',
            'map' => 'Karta',
            'parallel' => 'Grafikon paralelnih koordinata',
            'lines' => 'Linijski graf',
            'graph' => 'Graf odnosa',
            'sankey' => 'Sankeyjev dijagram',
            'funnel' => 'Lijevkasti grafikon',
            'gauge' => 'Mjerač',
            'pictorialBar' => 'Slikovni stupčasti grafikon',
            'themeRiver' => 'Grafikon tematske rijeke',
            'sunburst' => 'Grafikon sunčevih zraka',
            'custom' => 'Prilagođeni grafikon',
            'chart' => 'Grafikon',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ovo je grafikon pod nazivom "{title}"',
            'withoutTitle' => 'Ovo je grafikon',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' vrste {seriesType} s nazivom {seriesName}.',
                'withoutName' => ' vrste {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Broj serija: {seriesCount}.',
                'withName' => ' Serija {seriesId} vrste je {seriesType} i prikazuje {seriesName}.',
                'withoutName' => ' Serija {seriesId} vrste je {seriesType}.',
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
