<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'មករា',
            'កុម្ភៈ',
            'មីនា',
            'មេសា',
            'ឧសភា',
            'មិថុនា',
            'កក្កដា',
            'សីហា',
            'កញ្ញា',
            'តុលា',
            'វិច្ឆិកា',
            'ធ្នូ',
        ],
        'monthAbbr' => [
            'មករា',
            'កុម្ភៈ',
            'មីនា',
            'មេសា',
            'ឧសភា',
            'មិថុនា',
            'កក្កដា',
            'សីហា',
            'កញ្ញា',
            'តុលា',
            'វិច្ឆិកា',
            'ធ្នូ',
        ],
        'dayOfWeek' => [
            'អាទិត្យ',
            'ចន្ទ',
            'អង្គារ',
            'ពុធ',
            'ព្រហស្បតិ៍',
            'សុក្រ',
            'សៅរ៍',
        ],
        'dayOfWeekAbbr' => [
            'អាទិត្យ',
            'ចន្ទ',
            'អង្គារ',
            'ពុធ',
            'ព្រហ',
            'សុក្រ',
            'សៅរ៍',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'ទាំងអស់',
            'inverse' => 'បញ្ច្រាស',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'ជ្រើសរើសជាប្រអប់',
                'polygon' => 'ជ្រើសរើសដោយឡាសូ',
                'lineX' => 'ជ្រើសរើសតាមទិសផ្ដេក',
                'lineY' => 'ជ្រើសរើសតាមទិសបញ្ឈរ',
                'keep' => 'រក្សាការជ្រើសរើស',
                'clear' => 'សម្អាតការជ្រើសរើស',
            ],
        ],
        'dataView' => [
            'title' => 'ទិដ្ឋភាពទិន្នន័យ',
            'lang' => [
                'ទិដ្ឋភាពទិន្នន័យ',
                'បិទ',
                'ធ្វើបច្ចុប្បន្នភាព',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'ពង្រីក',
                'back' => 'កំណត់ការពង្រីកឡើងវិញ',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'ប្ដូរទៅគំនូសតាងបន្ទាត់',
                'bar' => 'ប្ដូរទៅគំនូសតាងរបារ',
                'stack' => 'ដាក់ជាជង់',
                'tiled' => 'ដាក់ទន្ទឹមគ្នា',
            ],
        ],
        'restore' => [
            'title' => 'ស្ដារឡើងវិញ',
        ],
        'saveAsImage' => [
            'title' => 'រក្សាទុកជារូបភាព',
            'lang' => [
                'ចុចកណ្ដុរស្ដាំដើម្បីរក្សាទុករូបភាព',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'គំនូសតាងរង្វង់',
            'bar' => 'គំនូសតាងរបារ',
            'line' => 'គំនូសតាងបន្ទាត់',
            'scatter' => 'គំនូសតាងចំណុចរាយប៉ាយ',
            'effectScatter' => 'គំនូសតាងចំណុចរាយប៉ាយមានរលក',
            'radar' => 'គំនូសតាងរ៉ាដា',
            'tree' => 'មែកធាង',
            'treemap' => 'ផែនទីមែកធាង',
            'boxplot' => 'គំនូសតាងប្រអប់',
            'candlestick' => 'គំនូសតាងទៀន',
            'k' => 'គំនូសតាង K-line',
            'heatmap' => 'ផែនទីកម្ដៅ',
            'map' => 'ផែនទី',
            'parallel' => 'គំនូសតាងកូអរដោនេស្របគ្នា',
            'lines' => 'គំនូសតាងខ្សែ',
            'graph' => 'ក្រាហ្វទំនាក់ទំនង',
            'sankey' => 'គំនូសតាង Sankey',
            'funnel' => 'គំនូសតាងរាងចីវ៉ា',
            'gauge' => 'គំនូសតាងឧបករណ៍វាស់',
            'pictorialBar' => 'គំនូសតាងរបាររូបភាព',
            'themeRiver' => 'គំនូសតាងទន្លេប្រធានបទ',
            'sunburst' => 'គំនូសតាង Sunburst',
            'custom' => 'គំនូសតាងផ្ទាល់ខ្លួន',
            'chart' => 'គំនូសតាង',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'នេះជាគំនូសតាងអំពី “{title}”',
            'withoutTitle' => 'នេះជាគំនូសតាង',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' ប្រភេទ {seriesType} ដែលមានឈ្មោះ {seriesName}។',
                'withoutName' => ' ប្រភេទ {seriesType}។',
            ],
            'multiple' => [
                'prefix' => '។ វាមាន {seriesCount} ស៊េរី។',
                'withName' => ' ស៊េរីទី {seriesId} ជា {seriesType} ដែលតំណាងឱ្យ {seriesName}។',
                'withoutName' => ' ស៊េរីទី {seriesId} ជា {seriesType}។',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'ទិន្នន័យមានដូចខាងក្រោម៖ ',
            'partialData' => 'ធាតុ {displayCnt} ដំបូងគឺ៖ ',
            'withName' => 'ទិន្នន័យសម្រាប់ {name} គឺ {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '។ ',
            ],
        ],
    ],
];
