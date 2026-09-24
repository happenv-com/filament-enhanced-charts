<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            '1月',
            '2月',
            '3月',
            '4月',
            '5月',
            '6月',
            '7月',
            '8月',
            '9月',
            '10月',
            '11月',
            '12月',
        ],
        'monthAbbr' => [
            '1月',
            '2月',
            '3月',
            '4月',
            '5月',
            '6月',
            '7月',
            '8月',
            '9月',
            '10月',
            '11月',
            '12月',
        ],
        'dayOfWeek' => [
            '星期日',
            '星期一',
            '星期二',
            '星期三',
            '星期四',
            '星期五',
            '星期六',
        ],
        'dayOfWeekAbbr' => [
            '週日',
            '週一',
            '週二',
            '週三',
            '週四',
            '週五',
            '週六',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => '全選',
            'inverse' => '反選',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => '矩形選擇',
                'polygon' => '套索選擇',
                'lineX' => '橫向選擇',
                'lineY' => '縱向選擇',
                'keep' => '保留選擇',
                'clear' => '清除選擇',
            ],
        ],
        'dataView' => [
            'title' => '數據視圖',
            'lang' => [
                '數據視圖',
                '關閉',
                '重新整理',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => '區域縮放',
                'back' => '重設縮放',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => '切換為折線圖',
                'bar' => '切換為棒形圖',
                'stack' => '切換為堆疊',
                'tiled' => '切換為平鋪',
            ],
        ],
        'restore' => [
            'title' => '還原',
        ],
        'saveAsImage' => [
            'title' => '儲存為圖片',
            'lang' => [
                '按右鍵儲存圖片',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => '圓形圖',
            'bar' => '棒形圖',
            'line' => '折線圖',
            'scatter' => '散點圖',
            'effectScatter' => '漣漪散點圖',
            'radar' => '雷達圖',
            'tree' => '樹狀圖',
            'treemap' => '矩形樹狀圖',
            'boxplot' => '框線圖',
            'candlestick' => '蠟燭圖',
            'k' => 'K線圖',
            'heatmap' => '熱圖',
            'map' => '地圖',
            'parallel' => '平行坐標圖',
            'lines' => '線圖',
            'graph' => '關係圖',
            'sankey' => '桑基圖',
            'funnel' => '漏斗圖',
            'gauge' => '儀表圖',
            'pictorialBar' => '象形棒形圖',
            'themeRiver' => '主題河流圖',
            'sunburst' => '旭日圖',
            'custom' => '自訂圖表',
            'chart' => '圖表',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => '這是一個關於「{title}」的圖表',
            'withoutTitle' => '這是一個圖表',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => '，類型為{seriesType}，名稱為{seriesName}。',
                'withoutName' => '，類型為{seriesType}。',
            ],
            'multiple' => [
                'prefix' => '。此圖表由{seriesCount}個系列組成。',
                'withName' => '第{seriesId}個系列是表示{seriesName}的{seriesType}。',
                'withoutName' => '第{seriesId}個系列是{seriesType}。',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => '數據如下：',
            'partialData' => '前{displayCnt}項數據為：',
            'withName' => '{name}的數據為{value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => '，',
                'end' => '。',
            ],
        ],
    ],
];
