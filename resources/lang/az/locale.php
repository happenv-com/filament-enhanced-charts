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
            'Avqust',
            'Sentyabr',
            'Oktyabr',
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
            'Avq',
            'Sen',
            'Okt',
            'Noy',
            'Dek',
        ],
        'dayOfWeek' => [
            'Bazar',
            'Bazar ertəsi',
            'Çərşənbə axşamı',
            'Çərşənbə',
            'Cümə axşamı',
            'Cümə',
            'Şənbə',
        ],
        'dayOfWeekAbbr' => [
            'B.',
            'B.E.',
            'Ç.A.',
            'Ç.',
            'C.A.',
            'C.',
            'Ş.',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Hamısı',
            'inverse' => 'Tərsinə çevir',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Düzbucaqlı seçim',
                'polygon' => 'Lasso ilə seçim',
                'lineX' => 'Üfüqi seçim',
                'lineY' => 'Şaquli seçim',
                'keep' => 'Seçimi saxla',
                'clear' => 'Seçimi təmizlə',
            ],
        ],
        'dataView' => [
            'title' => 'Məlumat görünüşü',
            'lang' => [
                'Məlumat görünüşü',
                'Bağla',
                'Yenilə',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Yaxınlaşdır',
                'back' => 'Miqyası sıfırla',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Xətti diaqrama keç',
                'bar' => 'Sütunlu diaqrama keç',
                'stack' => 'Yığılmış görünüş',
                'tiled' => 'Yanaşı görünüş',
            ],
        ],
        'restore' => [
            'title' => 'Bərpa et',
        ],
        'saveAsImage' => [
            'title' => 'Şəkil kimi yadda saxla',
            'lang' => [
                'Şəkli yadda saxlamaq üçün sağ klikləyin',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Dairəvi diaqram',
            'bar' => 'Sütunlu diaqram',
            'line' => 'Xətti diaqram',
            'scatter' => 'Səpələnmə diaqramı',
            'effectScatter' => 'Dalğa effektli səpələnmə diaqramı',
            'radar' => 'Radar diaqramı',
            'tree' => 'Ağac',
            'treemap' => 'Ağac xəritəsi',
            'boxplot' => 'Qutu diaqramı',
            'candlestick' => 'Şam diaqramı',
            'k' => 'K-xətt diaqramı',
            'heatmap' => 'İstilik xəritəsi',
            'map' => 'Xəritə',
            'parallel' => 'Paralel koordinatlar diaqramı',
            'lines' => 'Xətlər qrafiki',
            'graph' => 'Əlaqə qrafı',
            'sankey' => 'Sankey diaqramı',
            'funnel' => 'Huni diaqramı',
            'gauge' => 'Göstərici',
            'pictorialBar' => 'Şəkilli sütun diaqramı',
            'themeRiver' => 'Tematik çay diaqramı',
            'sunburst' => 'Günəş şüaları diaqramı',
            'custom' => 'Fərdi diaqram',
            'chart' => 'Diaqram',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Bu, "{title}" mövzusunda diaqramdır',
            'withoutTitle' => 'Bu, diaqramdır',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => '. Növü: {seriesType}, adı: {seriesName}.',
                'withoutName' => '. Növü: {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. {seriesCount} seriyadan ibarətdir.',
                'withName' => ' {seriesId} nömrəli seriya: növü {seriesType}, təmsil etdiyi {seriesName}.',
                'withoutName' => ' {seriesId} nömrəli seriya: növü {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Məlumatlar aşağıdakılardır: ',
            'partialData' => 'İlk {displayCnt} element: ',
            'withName' => '{name}: {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
