<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Januari',
            'Februari',
            'Machi',
            'Aprili',
            'Mei',
            'Juni',
            'Julai',
            'Agosti',
            'Septemba',
            'Oktoba',
            'Novemba',
            'Desemba',
        ],
        'monthAbbr' => [
            'Jan',
            'Feb',
            'Mac',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ],
        'dayOfWeek' => [
            'Jumapili',
            'Jumatatu',
            'Jumanne',
            'Jumatano',
            'Alhamisi',
            'Ijumaa',
            'Jumamosi',
        ],
        'dayOfWeekAbbr' => [
            'Jumapili',
            'Jumatatu',
            'Jumanne',
            'Jumatano',
            'Alhamisi',
            'Ijumaa',
            'Jumamosi',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Zote',
            'inverse' => 'Geuza',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Chagua kwa kisanduku',
                'polygon' => 'Chagua kwa lasso',
                'lineX' => 'Chagua kwa mlalo',
                'lineY' => 'Chagua kwa wima',
                'keep' => 'Hifadhi uteuzi',
                'clear' => 'Futa uteuzi',
            ],
        ],
        'dataView' => [
            'title' => 'Mwonekano wa data',
            'lang' => [
                'Mwonekano wa data',
                'Funga',
                'Onyesha upya',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Kuza',
                'back' => 'Weka upya ukuzaji',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Badili kuwa chati ya mstari',
                'bar' => 'Badili kuwa chati ya pau',
                'stack' => 'Panga kwa mrundikano',
                'tiled' => 'Panga kando kwa kando',
            ],
        ],
        'restore' => [
            'title' => 'Rejesha',
        ],
        'saveAsImage' => [
            'title' => 'Hifadhi kama picha',
            'lang' => [
                'Bofya kulia ili kuhifadhi picha',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Chati duara',
            'bar' => 'Chati ya pau',
            'line' => 'Chati ya mstari',
            'scatter' => 'Chati ya mtawanyiko',
            'effectScatter' => 'Chati ya mtawanyiko yenye mawimbi',
            'radar' => 'Chati ya rada',
            'tree' => 'Mti',
            'treemap' => 'Ramani ya mti',
            'boxplot' => 'Chati ya kisanduku',
            'candlestick' => 'Chati ya mishumaa',
            'k' => 'Chati ya mstari wa K',
            'heatmap' => 'Ramani ya joto',
            'map' => 'Ramani',
            'parallel' => 'Chati ya viwianishi sambamba',
            'lines' => 'Grafu ya mistari',
            'graph' => 'Grafu ya mahusiano',
            'sankey' => 'Mchoro wa Sankey',
            'funnel' => 'Chati ya faneli',
            'gauge' => 'Kipimo',
            'pictorialBar' => 'Chati ya pau za picha',
            'themeRiver' => 'Chati ya mto wa mada',
            'sunburst' => 'Chati ya mionzi ya jua',
            'custom' => 'Chati maalum',
            'chart' => 'Chati',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Hii ni chati kuhusu "{title}"',
            'withoutTitle' => 'Hii ni chati',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' ya aina {seriesType} yenye jina {seriesName}.',
                'withoutName' => ' ya aina {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Inajumuisha mifululizo {seriesCount} ya data.',
                'withName' => ' Mfululizo wa {seriesId} ni {seriesType} unaowakilisha {seriesName}.',
                'withoutName' => ' Mfululizo wa {seriesId} ni {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Data ni kama ifuatavyo: ',
            'partialData' => 'Vipengele {displayCnt} vya kwanza ni: ',
            'withName' => 'data ya {name} ni {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
