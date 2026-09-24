<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'ינואר',
            'פברואר',
            'מרץ',
            'אפריל',
            'מאי',
            'יוני',
            'יולי',
            'אוגוסט',
            'ספטמבר',
            'אוקטובר',
            'נובמבר',
            'דצמבר',
        ],
        'monthAbbr' => [
            'ינו׳',
            'פבר׳',
            'מרץ',
            'אפר׳',
            'מאי',
            'יוני',
            'יולי',
            'אוג׳',
            'ספט׳',
            'אוק׳',
            'נוב׳',
            'דצמ׳',
        ],
        'dayOfWeek' => [
            'יום ראשון',
            'יום שני',
            'יום שלישי',
            'יום רביעי',
            'יום חמישי',
            'יום שישי',
            'יום שבת',
        ],
        'dayOfWeekAbbr' => [
            'יום א׳',
            'יום ב׳',
            'יום ג׳',
            'יום ד׳',
            'יום ה׳',
            'יום ו׳',
            'שבת',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'הכול',
            'inverse' => 'הפוך',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'בחירה מלבנית',
                'polygon' => 'בחירת לאסו',
                'lineX' => 'בחירה אופקית',
                'lineY' => 'בחירה אנכית',
                'keep' => 'שמור בחירות',
                'clear' => 'נקה בחירות',
            ],
        ],
        'dataView' => [
            'title' => 'תצוגת נתונים',
            'lang' => [
                'תצוגת נתונים',
                'סגור',
                'רענן',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'זום',
                'back' => 'איפוס זום',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'עבור לתרשים קו',
                'bar' => 'עבור לתרשים עמודות',
                'stack' => 'הצג כמוערם',
                'tiled' => 'הצג זה לצד זה',
            ],
        ],
        'restore' => [
            'title' => 'שחזר',
        ],
        'saveAsImage' => [
            'title' => 'שמור כתמונה',
            'lang' => [
                'לחץ לחיצה ימנית כדי לשמור את התמונה',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'תרשים עוגה',
            'bar' => 'תרשים עמודות',
            'line' => 'תרשים קו',
            'scatter' => 'תרשים פיזור',
            'effectScatter' => 'תרשים פיזור עם אפקט אדווה',
            'radar' => 'תרשים רדאר',
            'tree' => 'עץ',
            'treemap' => 'מפת עץ',
            'boxplot' => 'תרשים קופסה',
            'candlestick' => 'תרשים נרות',
            'k' => 'תרשים K-line',
            'heatmap' => 'מפת חום',
            'map' => 'מפה',
            'parallel' => 'תרשים קואורדינטות מקבילות',
            'lines' => 'גרף קווים',
            'graph' => 'גרף קשרים',
            'sankey' => 'תרשים Sankey',
            'funnel' => 'תרשים משפך',
            'gauge' => 'מד',
            'pictorialBar' => 'תרשים עמודות ציורי',
            'themeRiver' => 'תרשים נהר נושאים',
            'sunburst' => 'תרשים קרני שמש',
            'custom' => 'תרשים מותאם אישית',
            'chart' => 'תרשים',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'זהו תרשים בנושא "{title}"',
            'withoutTitle' => 'זהו תרשים',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' מסוג {seriesType} בשם {seriesName}.',
                'withoutName' => ' מסוג {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. הוא מורכב מ-{seriesCount} סדרות.',
                'withName' => ' סדרה {seriesId} היא מסוג {seriesType} ומייצגת את {seriesName}.',
                'withoutName' => ' סדרה {seriesId} היא מסוג {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'הנתונים הם: ',
            'partialData' => '{displayCnt} הפריטים הראשונים הם: ',
            'withName' => 'הערך של {name} הוא {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
