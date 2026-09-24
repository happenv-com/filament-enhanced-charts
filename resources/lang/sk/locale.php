<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Január',
            'Február',
            'Marec',
            'Apríl',
            'Máj',
            'Jún',
            'Júl',
            'August',
            'September',
            'Október',
            'November',
            'December',
        ],
        'monthAbbr' => [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Máj',
            'Jún',
            'Júl',
            'Aug',
            'Sep',
            'Okt',
            'Nov',
            'Dec',
        ],
        'dayOfWeek' => [
            'Nedeľa',
            'Pondelok',
            'Utorok',
            'Streda',
            'Štvrtok',
            'Piatok',
            'Sobota',
        ],
        'dayOfWeekAbbr' => [
            'Ne',
            'Po',
            'Ut',
            'St',
            'Št',
            'Pi',
            'So',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Všetko',
            'inverse' => 'Obrátiť',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Obdĺžnikový výber',
                'polygon' => 'Výber lasom',
                'lineX' => 'Vodorovný výber',
                'lineY' => 'Zvislý výber',
                'keep' => 'Ponechať výber',
                'clear' => 'Zrušiť výber',
            ],
        ],
        'dataView' => [
            'title' => 'Zobrazenie údajov',
            'lang' => [
                'Zobrazenie údajov',
                'Zavrieť',
                'Aktualizovať',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Priblížiť',
                'back' => 'Zrušiť priblíženie',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Prepnúť na čiarový graf',
                'bar' => 'Prepnúť na stĺpcový graf',
                'stack' => 'Naskladať',
                'tiled' => 'Rozložiť vedľa seba',
            ],
        ],
        'restore' => [
            'title' => 'Obnoviť',
        ],
        'saveAsImage' => [
            'title' => 'Uložiť ako obrázok',
            'lang' => [
                'Obrázok uložíte kliknutím pravým tlačidlom myši',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Koláčový graf',
            'bar' => 'Stĺpcový graf',
            'line' => 'Čiarový graf',
            'scatter' => 'Bodový graf',
            'effectScatter' => 'Bodový graf s efektom vlnenia',
            'radar' => 'Radarový graf',
            'tree' => 'Strom',
            'treemap' => 'Stromová mapa',
            'boxplot' => 'Krabicový graf',
            'candlestick' => 'Sviečkový graf',
            'k' => 'K-čiarový graf',
            'heatmap' => 'Teplotná mapa',
            'map' => 'Mapa',
            'parallel' => 'Graf paralelných súradníc',
            'lines' => 'Čiarový diagram',
            'graph' => 'Graf vzťahov',
            'sankey' => 'Sankeyho diagram',
            'funnel' => 'Lievikový graf',
            'gauge' => 'Ukazovateľ',
            'pictorialBar' => 'Obrázkový stĺpcový graf',
            'themeRiver' => 'Tematický riečny graf',
            'sunburst' => 'Viacúrovňový prstencový graf',
            'custom' => 'Vlastný graf',
            'chart' => 'Graf',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Toto je graf o „{title}“',
            'withoutTitle' => 'Toto je graf',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' typu {seriesType} s názvom {seriesName}.',
                'withoutName' => ' typu {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Počet sérií: {seriesCount}.',
                'withName' => ' Séria {seriesId} je typu {seriesType} a predstavuje {seriesName}.',
                'withoutName' => ' Séria {seriesId} je typu {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Údaje sú nasledovné: ',
            'partialData' => 'Prvých {displayCnt} položiek: ',
            'withName' => 'hodnota pre {name} je {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
