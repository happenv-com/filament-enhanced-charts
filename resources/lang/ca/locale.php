<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Gener',
            'Febrer',
            'Març',
            'Abril',
            'Maig',
            'Juny',
            'Juliol',
            'Agost',
            'Setembre',
            'Octubre',
            'Novembre',
            'Desembre',
        ],
        'monthAbbr' => [
            'Gen.',
            'Febr.',
            'Març',
            'Abr.',
            'Maig',
            'Juny',
            'Jul.',
            'Ag.',
            'Set.',
            'Oct.',
            'Nov.',
            'Des.',
        ],
        'dayOfWeek' => [
            'Diumenge',
            'Dilluns',
            'Dimarts',
            'Dimecres',
            'Dijous',
            'Divendres',
            'Dissabte',
        ],
        'dayOfWeekAbbr' => [
            'Dg.',
            'Dl.',
            'Dt.',
            'Dc.',
            'Dj.',
            'Dv.',
            'Ds.',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Tots',
            'inverse' => 'Inverteix',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Selecció rectangular',
                'polygon' => 'Selecció amb llaç',
                'lineX' => 'Selecció horitzontal',
                'lineY' => 'Selecció vertical',
                'keep' => 'Mantén la selecció',
                'clear' => 'Esborra la selecció',
            ],
        ],
        'dataView' => [
            'title' => 'Vista de dades',
            'lang' => [
                'Vista de dades',
                'Tanca',
                'Actualitza',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zoom',
                'back' => 'Restableix el zoom',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Canvia a gràfic de línies',
                'bar' => 'Canvia a gràfic de barres',
                'stack' => 'Apila',
                'tiled' => 'Costat a costat',
            ],
        ],
        'restore' => [
            'title' => 'Restaura',
        ],
        'saveAsImage' => [
            'title' => 'Desa com a imatge',
            'lang' => [
                'Feu clic amb el botó dret per desar la imatge',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Gràfic circular',
            'bar' => 'Gràfic de barres',
            'line' => 'Gràfic de línies',
            'scatter' => 'Diagrama de dispersió',
            'effectScatter' => 'Diagrama de dispersió amb ones',
            'radar' => 'Gràfic de radar',
            'tree' => 'Arbre',
            'treemap' => 'Mapa d\'arbre',
            'boxplot' => 'Diagrama de caixa',
            'candlestick' => 'Gràfic d\'espelmes',
            'k' => 'Gràfic de línies K',
            'heatmap' => 'Mapa de calor',
            'map' => 'Mapa',
            'parallel' => 'Gràfic de coordenades paral·leles',
            'lines' => 'Diagrama de línies',
            'graph' => 'Graf de relacions',
            'sankey' => 'Diagrama de Sankey',
            'funnel' => 'Gràfic d\'embut',
            'gauge' => 'Indicador',
            'pictorialBar' => 'Gràfic de barres pictòric',
            'themeRiver' => 'Gràfic de riu temàtic',
            'sunburst' => 'Gràfic de projecció solar',
            'custom' => 'Gràfic personalitzat',
            'chart' => 'Gràfic',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Aquest és un gràfic sobre «{title}»',
            'withoutTitle' => 'Aquest és un gràfic',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' de tipus «{seriesType}» anomenat {seriesName}.',
                'withoutName' => ' de tipus «{seriesType}».',
            ],
            'multiple' => [
                'prefix' => '. Consta de {seriesCount} sèries.',
                'withName' => ' La sèrie {seriesId} és de tipus «{seriesType}» i representa {seriesName}.',
                'withoutName' => ' La sèrie {seriesId} és de tipus «{seriesType}».',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Les dades són les següents: ',
            'partialData' => 'Els primers {displayCnt} elements són: ',
            'withName' => 'el valor de {name} és {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
