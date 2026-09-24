<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'जनवरी',
            'फेब्रुअरी',
            'मार्च',
            'अप्रिल',
            'मे',
            'जुन',
            'जुलाई',
            'अगस्ट',
            'सेप्टेम्बर',
            'अक्टोबर',
            'नोभेम्बर',
            'डिसेम्बर',
        ],
        'monthAbbr' => [
            'जनवरी',
            'फेब्रुअरी',
            'मार्च',
            'अप्रिल',
            'मे',
            'जुन',
            'जुलाई',
            'अगस्ट',
            'सेप्टेम्बर',
            'अक्टोबर',
            'नोभेम्बर',
            'डिसेम्बर',
        ],
        'dayOfWeek' => [
            'आइतबार',
            'सोमबार',
            'मङ्गलबार',
            'बुधबार',
            'बिहिबार',
            'शुक्रबार',
            'शनिबार',
        ],
        'dayOfWeekAbbr' => [
            'आइत',
            'सोम',
            'मङ्गल',
            'बुध',
            'बिहि',
            'शुक्र',
            'शनि',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'सबै',
            'inverse' => 'उल्टाउनुहोस्',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'आयताकार चयन',
                'polygon' => 'लासो चयन',
                'lineX' => 'तेर्सो चयन',
                'lineY' => 'ठाडो चयन',
                'keep' => 'चयनहरू राख्नुहोस्',
                'clear' => 'चयनहरू हटाउनुहोस्',
            ],
        ],
        'dataView' => [
            'title' => 'डेटा दृश्य',
            'lang' => [
                'डेटा दृश्य',
                'बन्द गर्नुहोस्',
                'ताजा गर्नुहोस्',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'जुम',
                'back' => 'जुम रिसेट गर्नुहोस्',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'रेखा चार्टमा बदल्नुहोस्',
                'bar' => 'बार चार्टमा बदल्नुहोस्',
                'stack' => 'स्ट्याक गर्नुहोस्',
                'tiled' => 'टाइल गर्नुहोस्',
            ],
        ],
        'restore' => [
            'title' => 'पुनर्स्थापना गर्नुहोस्',
        ],
        'saveAsImage' => [
            'title' => 'छविको रूपमा सुरक्षित गर्नुहोस्',
            'lang' => [
                'छवि सुरक्षित गर्न दायाँ क्लिक गर्नुहोस्',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'पाई चार्ट',
            'bar' => 'बार चार्ट',
            'line' => 'रेखा चार्ट',
            'scatter' => 'स्क्याटर प्लट',
            'effectScatter' => 'रिपल स्क्याटर प्लट',
            'radar' => 'रडार चार्ट',
            'tree' => 'ट्री',
            'treemap' => 'ट्रीम्याप',
            'boxplot' => 'बक्स प्लट',
            'candlestick' => 'क्यान्डलस्टिक चार्ट',
            'k' => 'K लाइन चार्ट',
            'heatmap' => 'हिट म्याप',
            'map' => 'नक्सा',
            'parallel' => 'समानान्तर निर्देशांक चार्ट',
            'lines' => 'रेखा ग्राफ',
            'graph' => 'सम्बन्ध ग्राफ',
            'sankey' => 'स्यान्की (Sankey) डायग्राम',
            'funnel' => 'फनेल चार्ट',
            'gauge' => 'गेज',
            'pictorialBar' => 'चित्रात्मक बार',
            'themeRiver' => 'थिम रिभर चार्ट',
            'sunburst' => 'सनबर्स्ट चार्ट',
            'custom' => 'अनुकूलित चार्ट',
            'chart' => 'चार्ट',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'यो "{title}" सम्बन्धी चार्ट हो',
            'withoutTitle' => 'यो एउटा चार्ट हो',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ', जसको प्रकार {seriesType} र नाम {seriesName} हो।',
                'withoutName' => ', जसको प्रकार {seriesType} हो।',
            ],
            'multiple' => [
                'prefix' => '। यसमा {seriesCount} वटा शृङ्खला छन्।',
                'withName' => ' शृङ्खला {seriesId} {seriesName} को प्रतिनिधित्व गर्ने {seriesType} हो।',
                'withoutName' => ' शृङ्खला {seriesId} {seriesType} हो।',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'डेटा यस प्रकार छ: ',
            'partialData' => 'पहिलो {displayCnt} वटा वस्तुहरू: ',
            'withName' => '{name} को डेटा {value} हो',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '। ',
            ],
        ],
    ],
];
