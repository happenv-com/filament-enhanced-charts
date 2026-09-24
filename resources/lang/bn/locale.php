<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'জানুয়ারি',
            'ফেব্রুয়ারি',
            'মার্চ',
            'এপ্রিল',
            'মে',
            'জুন',
            'জুলাই',
            'আগস্ট',
            'সেপ্টেম্বর',
            'অক্টোবর',
            'নভেম্বর',
            'ডিসেম্বর',
        ],
        'monthAbbr' => [
            'জানু',
            'ফেব',
            'মার্চ',
            'এপ্রিল',
            'মে',
            'জুন',
            'জুলাই',
            'আগস্ট',
            'সেপ্ট',
            'অক্টো',
            'নভেম্বর',
            'ডিসে',
        ],
        'dayOfWeek' => [
            'রবিবার',
            'সোমবার',
            'মঙ্গলবার',
            'বুধবার',
            'বৃহস্পতিবার',
            'শুক্রবার',
            'শনিবার',
        ],
        'dayOfWeekAbbr' => [
            'রবি',
            'সোম',
            'মঙ্গল',
            'বুধ',
            'বৃহস্পতি',
            'শুক্র',
            'শনি',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'সব',
            'inverse' => 'উল্টান',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'আয়তক্ষেত্র নির্বাচন',
                'polygon' => 'ল্যাসো নির্বাচন',
                'lineX' => 'অনুভূমিক নির্বাচন',
                'lineY' => 'উল্লম্ব নির্বাচন',
                'keep' => 'নির্বাচন রাখুন',
                'clear' => 'নির্বাচন মুছুন',
            ],
        ],
        'dataView' => [
            'title' => 'ডেটা ভিউ',
            'lang' => [
                'ডেটা ভিউ',
                'বন্ধ করুন',
                'রিফ্রেশ করুন',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'জুম',
                'back' => 'জুম রিসেট করুন',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'লাইন চার্টে পরিবর্তন করুন',
                'bar' => 'বার চার্টে পরিবর্তন করুন',
                'stack' => 'স্তূপীকৃত',
                'tiled' => 'পাশাপাশি',
            ],
        ],
        'restore' => [
            'title' => 'পুনরুদ্ধার করুন',
        ],
        'saveAsImage' => [
            'title' => 'ছবি হিসেবে সংরক্ষণ করুন',
            'lang' => [
                'ছবি সংরক্ষণ করতে রাইট-ক্লিক করুন',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'পাই চার্ট',
            'bar' => 'বার চার্ট',
            'line' => 'লাইন চার্ট',
            'scatter' => 'স্ক্যাটার প্লট',
            'effectScatter' => 'রিপল স্ক্যাটার প্লট',
            'radar' => 'রাডার চার্ট',
            'tree' => 'ট্রি',
            'treemap' => 'ট্রিম্যাপ',
            'boxplot' => 'বক্স প্লট',
            'candlestick' => 'ক্যান্ডেলস্টিক চার্ট',
            'k' => 'K-লাইন চার্ট',
            'heatmap' => 'হিটম্যাপ',
            'map' => 'মানচিত্র',
            'parallel' => 'সমান্তরাল স্থানাঙ্ক চার্ট',
            'lines' => 'লাইন গ্রাফ',
            'graph' => 'সম্পর্ক গ্রাফ',
            'sankey' => 'স্যাঙ্কি ডায়াগ্রাম',
            'funnel' => 'ফানেল চার্ট',
            'gauge' => 'গেজ',
            'pictorialBar' => 'চিত্রভিত্তিক বার চার্ট',
            'themeRiver' => 'থিম রিভার চার্ট',
            'sunburst' => 'সানবার্স্ট চার্ট',
            'custom' => 'কাস্টম চার্ট',
            'chart' => 'চার্ট',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'এটি "{title}" বিষয়ক একটি চার্ট',
            'withoutTitle' => 'এটি একটি চার্ট',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => '। এর ধরন {seriesType} এবং নাম {seriesName}।',
                'withoutName' => '। এর ধরন {seriesType}।',
            ],
            'multiple' => [
                'prefix' => '। এতে {seriesCount}টি সিরিজ রয়েছে।',
                'withName' => ' সিরিজ {seriesId} একটি {seriesType}, যা {seriesName} উপস্থাপন করে।',
                'withoutName' => ' সিরিজ {seriesId} একটি {seriesType}।',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'ডেটা নিম্নরূপ: ',
            'partialData' => 'প্রথম {displayCnt}টি আইটেম: ',
            'withName' => '{name}-এর মান {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '। ',
            ],
        ],
    ],
];
