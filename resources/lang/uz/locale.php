<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Yanvar',
            'Fevral',
            'Mart',
            'Aprel',
            'May',
            'Iyun',
            'Iyul',
            'Avgust',
            'Sentabr',
            'Oktabr',
            'Noyabr',
            'Dekabr',
        ],
        'monthAbbr' => [
            'Yan',
            'Fev',
            'Mar',
            'Apr',
            'May',
            'Iyn',
            'Iyl',
            'Avg',
            'Sen',
            'Okt',
            'Noy',
            'Dek',
        ],
        'dayOfWeek' => [
            'Yakshanba',
            'Dushanba',
            'Seshanba',
            'Chorshanba',
            'Payshanba',
            'Juma',
            'Shanba',
        ],
        'dayOfWeekAbbr' => [
            'Yak',
            'Dush',
            'Sesh',
            'Chor',
            'Pay',
            'Jum',
            'Shan',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Barchasi',
            'inverse' => 'Teskari',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Toʻrtburchak bilan tanlash',
                'polygon' => 'Lasso bilan tanlash',
                'lineX' => 'Gorizontal tanlash',
                'lineY' => 'Vertikal tanlash',
                'keep' => 'Tanlovni saqlash',
                'clear' => 'Tanlovni tozalash',
            ],
        ],
        'dataView' => [
            'title' => 'Maʼlumotlar koʻrinishi',
            'lang' => [
                'Maʼlumotlar koʻrinishi',
                'Yopish',
                'Yangilash',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Masshtablash',
                'back' => 'Masshtabni tiklash',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Chiziqli diagrammaga oʻtish',
                'bar' => 'Ustunli diagrammaga oʻtish',
                'stack' => 'Ustma-ust joylash',
                'tiled' => 'Yonma-yon joylash',
            ],
        ],
        'restore' => [
            'title' => 'Tiklash',
        ],
        'saveAsImage' => [
            'title' => 'Rasm sifatida saqlash',
            'lang' => [
                'Rasmni saqlash uchun sichqonchaning oʻng tugmasini bosing',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Doiraviy diagramma',
            'bar' => 'Ustunli diagramma',
            'line' => 'Chiziqli diagramma',
            'scatter' => 'Nuqtali diagramma',
            'effectScatter' => 'Toʻlqin effektli nuqtali diagramma',
            'radar' => 'Radar diagrammasi',
            'tree' => 'Daraxt',
            'treemap' => 'Daraxtsimon xarita',
            'boxplot' => 'Quti diagrammasi',
            'candlestick' => 'Shamli diagramma',
            'k' => 'K-chiziqli diagramma',
            'heatmap' => 'Issiqlik xaritasi',
            'map' => 'Xarita',
            'parallel' => 'Parallel koordinatalar diagrammasi',
            'lines' => 'Chiziqlar grafigi',
            'graph' => 'Aloqalar grafi',
            'sankey' => 'Sankey diagrammasi',
            'funnel' => 'Voronka diagrammasi',
            'gauge' => 'Oʻlchagich',
            'pictorialBar' => 'Tasviriy ustunli diagramma',
            'themeRiver' => 'Mavzu daryosi diagrammasi',
            'sunburst' => 'Quyosh nurlari diagrammasi',
            'custom' => 'Maxsus diagramma',
            'chart' => 'Diagramma',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Bu «{title}» haqidagi diagramma',
            'withoutTitle' => 'Bu diagramma',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' boʻlib, turi {seriesType}, nomi {seriesName}.',
                'withoutName' => ' boʻlib, turi {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. U {seriesCount} ta seriyadan iborat.',
                'withName' => ' {seriesId}-seriya {seriesType} turida boʻlib, {seriesName} maʼlumotlarini aks ettiradi.',
                'withoutName' => ' {seriesId}-seriya {seriesType} turida.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Maʼlumotlar quyidagicha: ',
            'partialData' => 'Dastlabki {displayCnt} ta element: ',
            'withName' => '{name} uchun qiymat {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
