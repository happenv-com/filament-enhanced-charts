<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'ጃንዋሪ',
            'ፌብሩዋሪ',
            'ማርች',
            'ኤፕሪል',
            'ሜይ',
            'ጁን',
            'ጁላይ',
            'ኦገስት',
            'ሴፕቴምበር',
            'ኦክቶበር',
            'ኖቬምበር',
            'ዲሴምበር',
        ],
        'monthAbbr' => [
            'ጃን',
            'ፌብ',
            'ማርች',
            'ኤፕሪ',
            'ሜይ',
            'ጁን',
            'ጁላይ',
            'ኦገስ',
            'ሴፕቴ',
            'ኦክቶ',
            'ኖቬም',
            'ዲሴም',
        ],
        'dayOfWeek' => [
            'እሑድ',
            'ሰኞ',
            'ማክሰኞ',
            'ረቡዕ',
            'ሐሙስ',
            'ዓርብ',
            'ቅዳሜ',
        ],
        'dayOfWeekAbbr' => [
            'እሑድ',
            'ሰኞ',
            'ማክሰ',
            'ረቡዕ',
            'ሐሙስ',
            'ዓርብ',
            'ቅዳሜ',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'ሁሉም',
            'inverse' => 'ገልብጥ',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'አራት ማዕዘን ምርጫ',
                'polygon' => 'የላሶ ምርጫ',
                'lineX' => 'አግድም ምርጫ',
                'lineY' => 'ቁመታዊ ምርጫ',
                'keep' => 'ምርጫዎችን አቆይ',
                'clear' => 'ምርጫዎችን አጽዳ',
            ],
        ],
        'dataView' => [
            'title' => 'የውሂብ እይታ',
            'lang' => [
                'የውሂብ እይታ',
                'ዝጋ',
                'አድስ',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'አጉላ',
                'back' => 'ማጉላትን ዳግም አስጀምር',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'ወደ መስመር ገበታ ቀይር',
                'bar' => 'ወደ አሞሌ ገበታ ቀይር',
                'stack' => 'የተደራረበ እይታ',
                'tiled' => 'ጎን ለጎን እይታ',
            ],
        ],
        'restore' => [
            'title' => 'ወደነበረበት መልስ',
        ],
        'saveAsImage' => [
            'title' => 'እንደ ምስል አስቀምጥ',
            'lang' => [
                'ምስሉን ለማስቀመጥ በቀኝ ጠቅ ያድርጉ',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'የፓይ ገበታ',
            'bar' => 'የአሞሌ ገበታ',
            'line' => 'የመስመር ገበታ',
            'scatter' => 'የነጥብ ብተና ገበታ',
            'effectScatter' => 'የሞገድ ውጤት ያለው የነጥብ ብተና ገበታ',
            'radar' => 'የራዳር ገበታ',
            'tree' => 'ዛፍ',
            'treemap' => 'የዛፍ ካርታ',
            'boxplot' => 'የሳጥን ገበታ',
            'candlestick' => 'የሻማ ገበታ',
            'k' => 'የK-መስመር ገበታ',
            'heatmap' => 'የሙቀት ካርታ',
            'map' => 'ካርታ',
            'parallel' => 'የትይዩ መጋጠሚያዎች ገበታ',
            'lines' => 'የመስመሮች ግራፍ',
            'graph' => 'የግንኙነት ግራፍ',
            'sankey' => 'የSankey ንድፍ',
            'funnel' => 'የፈነል ገበታ',
            'gauge' => 'መለኪያ',
            'pictorialBar' => 'ሥዕላዊ የአሞሌ ገበታ',
            'themeRiver' => 'የጭብጥ ወንዝ ገበታ',
            'sunburst' => 'የፀሐይ ጨረር ገበታ',
            'custom' => 'ብጁ ገበታ',
            'chart' => 'ገበታ',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'ይህ ስለ "{title}" የሆነ ገበታ ነው',
            'withoutTitle' => 'ይህ ገበታ ነው',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => '። ዓይነቱ {seriesType}፣ ስሙ {seriesName} ነው።',
                'withoutName' => '። ዓይነቱ {seriesType} ነው።',
            ],
            'multiple' => [
                'prefix' => '። {seriesCount} ተከታታዮችን ይዟል።',
                'withName' => ' ተከታታይ {seriesId}፦ ዓይነቱ {seriesType}፣ የሚወክለው {seriesName} ነው።',
                'withoutName' => ' ተከታታይ {seriesId}፦ ዓይነቱ {seriesType} ነው።',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'ውሂቡ የሚከተለው ነው፦ ',
            'partialData' => 'የመጀመሪያዎቹ {displayCnt} ንጥሎች፦ ',
            'withName' => '{name}፦ {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => '፣ ',
                'end' => '። ',
            ],
        ],
    ],
];
