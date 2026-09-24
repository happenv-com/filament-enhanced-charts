<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Januari',
            'Februari',
            'Mac',
            'April',
            'Mei',
            'Jun',
            'Julai',
            'Ogos',
            'September',
            'Oktober',
            'November',
            'Disember',
        ],
        'monthAbbr' => [
            'Jan',
            'Feb',
            'Mac',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Ogo',
            'Sep',
            'Okt',
            'Nov',
            'Dis',
        ],
        'dayOfWeek' => [
            'Ahad',
            'Isnin',
            'Selasa',
            'Rabu',
            'Khamis',
            'Jumaat',
            'Sabtu',
        ],
        'dayOfWeekAbbr' => [
            'Ahd',
            'Isn',
            'Sel',
            'Rab',
            'Kha',
            'Jum',
            'Sab',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Semua',
            'inverse' => 'Songsang',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Pilih Kotak',
                'polygon' => 'Pilih Lasso',
                'lineX' => 'Pilih Mendatar',
                'lineY' => 'Pilih Menegak',
                'keep' => 'Kekalkan Pilihan',
                'clear' => 'Kosongkan Pilihan',
            ],
        ],
        'dataView' => [
            'title' => 'Paparan Data',
            'lang' => [
                'Paparan Data',
                'Tutup',
                'Muat Semula',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zum',
                'back' => 'Set Semula Zum',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Tukar kepada Carta Garis',
                'bar' => 'Tukar kepada Carta Bar',
                'stack' => 'Tindan',
                'tiled' => 'Jubin',
            ],
        ],
        'restore' => [
            'title' => 'Pulihkan',
        ],
        'saveAsImage' => [
            'title' => 'Simpan sebagai Imej',
            'lang' => [
                'Klik kanan untuk menyimpan imej',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Carta pai',
            'bar' => 'Carta bar',
            'line' => 'Carta garis',
            'scatter' => 'Plot serakan',
            'effectScatter' => 'Plot serakan riak',
            'radar' => 'Carta radar',
            'tree' => 'Pokok',
            'treemap' => 'Peta pokok',
            'boxplot' => 'Plot kotak',
            'candlestick' => 'Carta lilin',
            'k' => 'Carta garis K',
            'heatmap' => 'Peta haba',
            'map' => 'Peta',
            'parallel' => 'Carta koordinat selari',
            'lines' => 'Graf garis',
            'graph' => 'Graf hubungan',
            'sankey' => 'Rajah Sankey',
            'funnel' => 'Carta corong',
            'gauge' => 'Tolok',
            'pictorialBar' => 'Bar bergambar',
            'themeRiver' => 'Carta sungai tema',
            'sunburst' => 'Carta sunburst',
            'custom' => 'Carta tersuai',
            'chart' => 'Carta',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ini ialah carta tentang "{title}"',
            'withoutTitle' => 'Ini ialah carta',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' dengan jenis {seriesType} bernama {seriesName}.',
                'withoutName' => ' dengan jenis {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Ia terdiri daripada {seriesCount} siri.',
                'withName' => ' Siri {seriesId} ialah {seriesType} yang mewakili {seriesName}.',
                'withoutName' => ' Siri {seriesId} ialah {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Data adalah seperti berikut: ',
            'partialData' => '{displayCnt} item pertama ialah: ',
            'withName' => 'data untuk {name} ialah {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
