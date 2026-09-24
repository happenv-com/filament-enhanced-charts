<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'जनवरी',
            'फ़रवरी',
            'मार्च',
            'अप्रैल',
            'मई',
            'जून',
            'जुलाई',
            'अगस्त',
            'सितंबर',
            'अक्टूबर',
            'नवंबर',
            'दिसंबर',
        ],
        'monthAbbr' => [
            'जन॰',
            'फ़र॰',
            'मार्च',
            'अप्रैल',
            'मई',
            'जून',
            'जुल॰',
            'अग॰',
            'सित॰',
            'अक्टू॰',
            'नव॰',
            'दिस॰',
        ],
        'dayOfWeek' => [
            'रविवार',
            'सोमवार',
            'मंगलवार',
            'बुधवार',
            'गुरुवार',
            'शुक्रवार',
            'शनिवार',
        ],
        'dayOfWeekAbbr' => [
            'रवि',
            'सोम',
            'मंगल',
            'बुध',
            'गुरु',
            'शुक्र',
            'शनि',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'सभी',
            'inverse' => 'उलटें',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'आयताकार चयन',
                'polygon' => 'लैसो चयन',
                'lineX' => 'क्षैतिज चयन',
                'lineY' => 'ऊर्ध्वाधर चयन',
                'keep' => 'चयन बनाए रखें',
                'clear' => 'चयन साफ़ करें',
            ],
        ],
        'dataView' => [
            'title' => 'डेटा दृश्य',
            'lang' => [
                'डेटा दृश्य',
                'बंद करें',
                'रीफ़्रेश करें',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'ज़ूम करें',
                'back' => 'ज़ूम रीसेट करें',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'लाइन चार्ट पर स्विच करें',
                'bar' => 'बार चार्ट पर स्विच करें',
                'stack' => 'स्टैक करें',
                'tiled' => 'साथ-साथ दिखाएँ',
            ],
        ],
        'restore' => [
            'title' => 'पुनर्स्थापित करें',
        ],
        'saveAsImage' => [
            'title' => 'छवि के रूप में सहेजें',
            'lang' => [
                'छवि सहेजने के लिए राइट-क्लिक करें',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'पाई चार्ट',
            'bar' => 'बार चार्ट',
            'line' => 'लाइन चार्ट',
            'scatter' => 'स्कैटर प्लॉट',
            'effectScatter' => 'रिपल स्कैटर प्लॉट',
            'radar' => 'रडार चार्ट',
            'tree' => 'ट्री चार्ट',
            'treemap' => 'ट्रीमैप',
            'boxplot' => 'बॉक्स प्लॉट',
            'candlestick' => 'कैंडलस्टिक चार्ट',
            'k' => 'के-लाइन चार्ट',
            'heatmap' => 'हीट मैप',
            'map' => 'मानचित्र',
            'parallel' => 'समानांतर निर्देशांक चार्ट',
            'lines' => 'रेखा ग्राफ़',
            'graph' => 'संबंध ग्राफ़',
            'sankey' => 'सैंकी आरेख',
            'funnel' => 'फ़नल चार्ट',
            'gauge' => 'गेज',
            'pictorialBar' => 'चित्रात्मक बार चार्ट',
            'themeRiver' => 'थीम रिवर चार्ट',
            'sunburst' => 'सनबर्स्ट चार्ट',
            'custom' => 'कस्टम चार्ट',
            'chart' => 'चार्ट',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'यह "{title}" के बारे में एक चार्ट है',
            'withoutTitle' => 'यह एक चार्ट है',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => '। इसका प्रकार {seriesType} है और नाम {seriesName} है।',
                'withoutName' => '। इसका प्रकार {seriesType} है।',
            ],
            'multiple' => [
                'prefix' => '। इसमें {seriesCount} सीरीज़ हैं।',
                'withName' => ' सीरीज़ {seriesId} का प्रकार {seriesType} है और यह {seriesName} को दर्शाती है।',
                'withoutName' => ' सीरीज़ {seriesId} का प्रकार {seriesType} है।',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'डेटा इस प्रकार है: ',
            'partialData' => 'पहले {displayCnt} आइटम इस प्रकार हैं: ',
            'withName' => '{name} का मान {value} है',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '। ',
            ],
        ],
    ],
];
