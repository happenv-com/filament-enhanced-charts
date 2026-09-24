<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ],
        'monthAbbr' => [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des',
        ],
        'dayOfWeek' => [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
        ],
        'dayOfWeekAbbr' => [
            'Min',
            'Sen',
            'Sel',
            'Rab',
            'Kam',
            'Jum',
            'Sab',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Semua',
            'inverse' => 'Balik',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Pilih Kotak',
                'polygon' => 'Pilih Laso',
                'lineX' => 'Pilih Horizontal',
                'lineY' => 'Pilih Vertikal',
                'keep' => 'Pertahankan Pilihan',
                'clear' => 'Hapus Pilihan',
            ],
        ],
        'dataView' => [
            'title' => 'Tampilan Data',
            'lang' => [
                'Tampilan Data',
                'Tutup',
                'Segarkan',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zoom',
                'back' => 'Atur Ulang Zoom',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Beralih ke Diagram Garis',
                'bar' => 'Beralih ke Diagram Batang',
                'stack' => 'Tumpuk',
                'tiled' => 'Berdampingan',
            ],
        ],
        'restore' => [
            'title' => 'Pulihkan',
        ],
        'saveAsImage' => [
            'title' => 'Simpan sebagai Gambar',
            'lang' => [
                'Klik Kanan untuk Menyimpan Gambar',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Diagram lingkaran',
            'bar' => 'Diagram batang',
            'line' => 'Diagram garis',
            'scatter' => 'Diagram pencar',
            'effectScatter' => 'Diagram pencar beriak',
            'radar' => 'Diagram radar',
            'tree' => 'Pohon',
            'treemap' => 'Peta pohon',
            'boxplot' => 'Diagram kotak garis',
            'candlestick' => 'Diagram candlestick',
            'k' => 'Diagram K-line',
            'heatmap' => 'Peta panas',
            'map' => 'Peta',
            'parallel' => 'Diagram koordinat paralel',
            'lines' => 'Diagram lintasan garis',
            'graph' => 'Graf relasi',
            'sankey' => 'Diagram Sankey',
            'funnel' => 'Diagram corong',
            'gauge' => 'Diagram pengukur',
            'pictorialBar' => 'Diagram batang bergambar',
            'themeRiver' => 'Diagram sungai tema',
            'sunburst' => 'Diagram sunburst',
            'custom' => 'Diagram kustom',
            'chart' => 'Diagram',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Ini adalah diagram tentang "{title}"',
            'withoutTitle' => 'Ini adalah diagram',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' dengan tipe {seriesType} bernama {seriesName}.',
                'withoutName' => ' dengan tipe {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. Diagram ini terdiri dari {seriesCount} seri.',
                'withName' => ' Seri {seriesId} adalah {seriesType} yang menggambarkan {seriesName}.',
                'withoutName' => ' Seri {seriesId} adalah {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Datanya adalah sebagai berikut: ',
            'partialData' => '{displayCnt} item pertama adalah: ',
            'withName' => 'data untuk {name} adalah {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
