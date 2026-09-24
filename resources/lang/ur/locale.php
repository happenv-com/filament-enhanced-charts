<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'جنوری',
            'فروری',
            'مارچ',
            'اپریل',
            'مئی',
            'جون',
            'جولائی',
            'اگست',
            'ستمبر',
            'اکتوبر',
            'نومبر',
            'دسمبر',
        ],
        'monthAbbr' => [
            'جنوری',
            'فروری',
            'مارچ',
            'اپریل',
            'مئی',
            'جون',
            'جولائی',
            'اگست',
            'ستمبر',
            'اکتوبر',
            'نومبر',
            'دسمبر',
        ],
        'dayOfWeek' => [
            'اتوار',
            'پیر',
            'منگل',
            'بدھ',
            'جمعرات',
            'جمعہ',
            'ہفتہ',
        ],
        'dayOfWeekAbbr' => [
            'اتوار',
            'پیر',
            'منگل',
            'بدھ',
            'جمعرات',
            'جمعہ',
            'ہفتہ',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'سب',
            'inverse' => 'برعکس',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'مستطیل انتخاب',
                'polygon' => 'لاسو انتخاب',
                'lineX' => 'افقی انتخاب',
                'lineY' => 'عمودی انتخاب',
                'keep' => 'انتخاب برقرار رکھیں',
                'clear' => 'انتخاب صاف کریں',
            ],
        ],
        'dataView' => [
            'title' => 'ڈیٹا منظر',
            'lang' => [
                'ڈیٹا منظر',
                'بند کریں',
                'تازہ کریں',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'زوم',
                'back' => 'زوم ری سیٹ کریں',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'لائن چارٹ پر سوئچ کریں',
                'bar' => 'بار چارٹ پر سوئچ کریں',
                'stack' => 'اسٹیک کریں',
                'tiled' => 'ساتھ ساتھ دکھائیں',
            ],
        ],
        'restore' => [
            'title' => 'بحال کریں',
        ],
        'saveAsImage' => [
            'title' => 'تصویر کے طور پر محفوظ کریں',
            'lang' => [
                'تصویر محفوظ کرنے کے لیے دایاں کلک کریں',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'پائی چارٹ',
            'bar' => 'بار چارٹ',
            'line' => 'لائن چارٹ',
            'scatter' => 'اسکیٹر پلاٹ',
            'effectScatter' => 'لہر دار اسکیٹر پلاٹ',
            'radar' => 'ریڈار چارٹ',
            'tree' => 'ٹری',
            'treemap' => 'ٹری میپ',
            'boxplot' => 'باکس پلاٹ',
            'candlestick' => 'کینڈل اسٹک چارٹ',
            'k' => 'K لائن چارٹ',
            'heatmap' => 'ہیٹ میپ',
            'map' => 'نقشہ',
            'parallel' => 'متوازی محددات چارٹ',
            'lines' => 'لائن گراف',
            'graph' => 'تعلقات کا گراف',
            'sankey' => 'سینکی ڈایاگرام',
            'funnel' => 'فنل چارٹ',
            'gauge' => 'گیج',
            'pictorialBar' => 'تصویری بار چارٹ',
            'themeRiver' => 'تھیم ریور چارٹ',
            'sunburst' => 'سن برسٹ چارٹ',
            'custom' => 'حسب ضرورت چارٹ',
            'chart' => 'چارٹ',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'یہ "{title}" کے بارے میں ایک چارٹ ہے',
            'withoutTitle' => 'یہ ایک چارٹ ہے',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' جس کی قسم {seriesType} ہے اور نام {seriesName} ہے۔',
                'withoutName' => ' جس کی قسم {seriesType} ہے۔',
            ],
            'multiple' => [
                'prefix' => '۔ یہ {seriesCount} سیریز پر مشتمل ہے۔',
                'withName' => ' سیریز {seriesId} ایک {seriesType} ہے جو {seriesName} کی نمائندگی کرتی ہے۔',
                'withoutName' => ' سیریز {seriesId} ایک {seriesType} ہے۔',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'ڈیٹا درج ذیل ہے: ',
            'partialData' => 'پہلے {displayCnt} آئٹمز یہ ہیں: ',
            'withName' => '{name} کا ڈیٹا {value} ہے',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => '، ',
                'end' => '۔ ',
            ],
        ],
    ],
];
