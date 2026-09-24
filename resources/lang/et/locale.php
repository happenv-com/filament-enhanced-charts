<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Jaanuar',
            'Veebruar',
            'Märts',
            'Aprill',
            'Mai',
            'Juuni',
            'Juuli',
            'August',
            'September',
            'Oktoober',
            'November',
            'Detsember',
        ],
        'monthAbbr' => [
            'Jaan',
            'Veebr',
            'Märts',
            'Apr',
            'Mai',
            'Juuni',
            'Juuli',
            'Aug',
            'Sept',
            'Okt',
            'Nov',
            'Dets',
        ],
        'dayOfWeek' => [
            'Pühapäev',
            'Esmaspäev',
            'Teisipäev',
            'Kolmapäev',
            'Neljapäev',
            'Reede',
            'Laupäev',
        ],
        'dayOfWeekAbbr' => [
            'P',
            'E',
            'T',
            'K',
            'N',
            'R',
            'L',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Kõik',
            'inverse' => 'Pööra ümber',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Ristkülikvalik',
                'polygon' => 'Lassovalik',
                'lineX' => 'Horisontaalne valik',
                'lineY' => 'Vertikaalne valik',
                'keep' => 'Säilita valikud',
                'clear' => 'Tühjenda valikud',
            ],
        ],
        'dataView' => [
            'title' => 'Andmevaade',
            'lang' => [
                'Andmevaade',
                'Sulge',
                'Värskenda',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Suumi',
                'back' => 'Lähtesta suum',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Lülita joondiagrammile',
                'bar' => 'Lülita tulpdiagrammile',
                'stack' => 'Virnasta',
                'tiled' => 'Kõrvuti',
            ],
        ],
        'restore' => [
            'title' => 'Taasta',
        ],
        'saveAsImage' => [
            'title' => 'Salvesta pildina',
            'lang' => [
                'Pildi salvestamiseks tee paremklõps',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Sektordiagramm',
            'bar' => 'Tulpdiagramm',
            'line' => 'Joondiagramm',
            'scatter' => 'Hajuvusdiagramm',
            'effectScatter' => 'Laineefektiga hajuvusdiagramm',
            'radar' => 'Radardiagramm',
            'tree' => 'Puu',
            'treemap' => 'Puukaart',
            'boxplot' => 'Karpdiagramm',
            'candlestick' => 'Küünaldiagramm',
            'k' => 'K-joone diagramm',
            'heatmap' => 'Soojuskaart',
            'map' => 'Kaart',
            'parallel' => 'Paralleelkoordinaatide diagramm',
            'lines' => 'Joonte graafik',
            'graph' => 'Seosegraaf',
            'sankey' => 'Sankey diagramm',
            'funnel' => 'Lehtridiagramm',
            'gauge' => 'Näidik',
            'pictorialBar' => 'Piltidega tulpdiagramm',
            'themeRiver' => 'Teemajõe diagramm',
            'sunburst' => 'Päikesekiirdiagramm',
            'custom' => 'Kohandatud diagramm',
            'chart' => 'Diagramm',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'See on diagramm teemal "{title}"',
            'withoutTitle' => 'See on diagramm',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ', mille tüüp on {seriesType} ja nimi {seriesName}.',
                'withoutName' => ', mille tüüp on {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. See koosneb {seriesCount} seeriast.',
                'withName' => ' Seeria {seriesId} tüüp on {seriesType} ja nimi {seriesName}.',
                'withoutName' => ' Seeria {seriesId} tüüp on {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Andmed on järgmised: ',
            'partialData' => 'Esimesed {displayCnt} elementi on: ',
            'withName' => '{name} väärtus on {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
