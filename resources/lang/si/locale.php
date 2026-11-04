<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'ජනවාරි',
            'පෙබරවාරි',
            'මාර්තු',
            'අප්‍රේල්',
            'මැයි',
            'ජූනි',
            'ජූලි',
            'අගෝස්තු',
            'සැප්තැම්බර්',
            'ඔක්තෝබර්',
            'නොවැම්බර්',
            'දෙසැම්බර්',
        ],
        'monthAbbr' => [
            'ජන',
            'පෙබ',
            'මාර්තු',
            'අප්‍රේල්',
            'මැයි',
            'ජූනි',
            'ජූලි',
            'අගෝ',
            'සැප්',
            'ඔක්',
            'නොවැ',
            'දෙසැ',
        ],
        'dayOfWeek' => [
            'ඉරිදා',
            'සඳුදා',
            'අඟහරුවාදා',
            'බදාදා',
            'බ්‍රහස්පතින්දා',
            'සිකුරාදා',
            'සෙනසුරාදා',
        ],
        'dayOfWeekAbbr' => [
            'ඉරිදා',
            'සඳුදා',
            'අඟහ',
            'බදාදා',
            'බ්‍රහස්',
            'සිකු',
            'සෙන',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'සියල්ල',
            'inverse' => 'ප්‍රතිලෝම',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'කොටුවෙන් තෝරන්න',
                'polygon' => 'ලැසෝ තේරීම',
                'lineX' => 'තිරස් තේරීම',
                'lineY' => 'සිරස් තේරීම',
                'keep' => 'තේරීම් තබා ගන්න',
                'clear' => 'තේරීම් ඉවත් කරන්න',
            ],
        ],
        'dataView' => [
            'title' => 'දත්ත දසුන',
            'lang' => [
                'දත්ත දසුන',
                'වසන්න',
                'යාවත්කාලීන කරන්න',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'විශාලනය',
                'back' => 'විශාලනය යළි සකසන්න',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'රේඛා ප්‍රස්තාරයට මාරු වන්න',
                'bar' => 'තීරු ප්‍රස්තාරයට මාරු වන්න',
                'stack' => 'ගොඩ ගසන්න',
                'tiled' => 'එක ළඟ තබන්න',
            ],
        ],
        'restore' => [
            'title' => 'ප්‍රතිසාධනය',
        ],
        'saveAsImage' => [
            'title' => 'රූපයක් ලෙස සුරකින්න',
            'lang' => [
                'රූපය සුරැකීමට දකුණු-ක්ලික් කරන්න',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'වට ප්‍රස්තාරය',
            'bar' => 'තීරු ප්‍රස්තාරය',
            'line' => 'රේඛා ප්‍රස්තාරය',
            'scatter' => 'විසිරුම් ප්‍රස්තාරය',
            'effectScatter' => 'තරංග විසිරුම් ප්‍රස්තාරය',
            'radar' => 'රේඩාර් ප්‍රස්තාරය',
            'tree' => 'ගස',
            'treemap' => 'ගස් සිතියම',
            'boxplot' => 'කොටු ප්‍රස්තාරය',
            'candlestick' => 'ඉටිපන්දම් ප්‍රස්තාරය',
            'k' => 'K-line ප්‍රස්තාරය',
            'heatmap' => 'තාප සිතියම',
            'map' => 'සිතියම',
            'parallel' => 'සමාන්තර ඛණ්ඩාංක ප්‍රස්තාරය',
            'lines' => 'රේඛා ග්‍රාෆය',
            'graph' => 'සම්බන්ධතා ග්‍රාෆය',
            'sankey' => 'Sankey ප්‍රස්තාරය',
            'funnel' => 'පුනීල ප්‍රස්තාරය',
            'gauge' => 'මාපක ප්‍රස්තාරය',
            'pictorialBar' => 'රූපමය තීරු ප්‍රස්තාරය',
            'themeRiver' => 'තේමා ගංගා ප්‍රස්තාරය',
            'sunburst' => 'Sunburst ප්‍රස්තාරය',
            'custom' => 'අභිරුචි ප්‍රස්තාරය',
            'chart' => 'ප්‍රස්තාරය',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'මෙය “{title}” පිළිබඳ ප්‍රස්තාරයකි',
            'withoutTitle' => 'මෙය ප්‍රස්තාරයකි',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' {seriesName} නම් වූ {seriesType} වර්ගයේ.',
                'withoutName' => ' {seriesType} වර්ගයේ.',
            ],
            'multiple' => [
                'prefix' => '. එහි ශ්‍රේණි {seriesCount}ක් ඇත.',
                'withName' => ' {seriesId} වන ශ්‍රේණිය {seriesName} නියෝජනය කරන {seriesType} වේ.',
                'withoutName' => ' {seriesId} වන ශ්‍රේණිය {seriesType} වේ.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'දත්ත පහත පරිදි වේ: ',
            'partialData' => 'පළමු අයිතම {displayCnt} මෙසේය: ',
            'withName' => '{name} සඳහා දත්ත {value} වේ',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
