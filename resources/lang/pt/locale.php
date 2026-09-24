<?php

// Month and day names from CLDR; the other strings are translated by hand.

return [
    'time' => [
        'month' => [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro',
        ],
        'monthAbbr' => [
            'Jan.',
            'Fev.',
            'Mar.',
            'Abr.',
            'Mai.',
            'Jun.',
            'Jul.',
            'Ago.',
            'Set.',
            'Out.',
            'Nov.',
            'Dez.',
        ],
        'dayOfWeek' => [
            'Domingo',
            'Segunda-feira',
            'Terça-feira',
            'Quarta-feira',
            'Quinta-feira',
            'Sexta-feira',
            'Sábado',
        ],
        'dayOfWeekAbbr' => [
            'Domingo',
            'Segunda',
            'Terça',
            'Quarta',
            'Quinta',
            'Sexta',
            'Sábado',
        ],
    ],
    'legend' => [
        'selector' => [
            'all' => 'Todos',
            'inverse' => 'Inverter',
        ],
    ],
    'toolbox' => [
        'brush' => [
            'title' => [
                'rect' => 'Seleção retangular',
                'polygon' => 'Seleção em laço',
                'lineX' => 'Seleção horizontal',
                'lineY' => 'Seleção vertical',
                'keep' => 'Manter seleções',
                'clear' => 'Limpar seleções',
            ],
        ],
        'dataView' => [
            'title' => 'Vista de dados',
            'lang' => [
                'Vista de dados',
                'Fechar',
                'Atualizar',
            ],
        ],
        'dataZoom' => [
            'title' => [
                'zoom' => 'Zoom',
                'back' => 'Repor zoom',
            ],
        ],
        'magicType' => [
            'title' => [
                'line' => 'Mudar para gráfico de linhas',
                'bar' => 'Mudar para gráfico de barras',
                'stack' => 'Empilhar',
                'tiled' => 'Lado a lado',
            ],
        ],
        'restore' => [
            'title' => 'Restaurar',
        ],
        'saveAsImage' => [
            'title' => 'Guardar como imagem',
            'lang' => [
                'Clique com o botão direito do rato para guardar a imagem',
            ],
        ],
    ],
    'series' => [
        'typeNames' => [
            'pie' => 'Gráfico circular',
            'bar' => 'Gráfico de barras',
            'line' => 'Gráfico de linhas',
            'scatter' => 'Gráfico de dispersão',
            'effectScatter' => 'Gráfico de dispersão com ondulação',
            'radar' => 'Gráfico de radar',
            'tree' => 'Árvore',
            'treemap' => 'Mapa em árvore',
            'boxplot' => 'Diagrama de caixa',
            'candlestick' => 'Gráfico de velas',
            'k' => 'Gráfico de linhas K',
            'heatmap' => 'Mapa de calor',
            'map' => 'Mapa',
            'parallel' => 'Gráfico de coordenadas paralelas',
            'lines' => 'Diagrama de linhas',
            'graph' => 'Grafo de relações',
            'sankey' => 'Diagrama de Sankey',
            'funnel' => 'Gráfico de funil',
            'gauge' => 'Medidor',
            'pictorialBar' => 'Barras pictóricas',
            'themeRiver' => 'Gráfico de rio temático',
            'sunburst' => 'Gráfico de explosão solar',
            'custom' => 'Gráfico personalizado',
            'chart' => 'Gráfico',
        ],
    ],
    'aria' => [
        'general' => [
            'withTitle' => 'Este é um gráfico sobre "{title}"',
            'withoutTitle' => 'Este é um gráfico',
        ],
        'series' => [
            'single' => [
                'prefix' => '',
                'withName' => ' do tipo {seriesType} com o nome {seriesName}.',
                'withoutName' => ' do tipo {seriesType}.',
            ],
            'multiple' => [
                'prefix' => '. É composto por {seriesCount} séries.',
                'withName' => ' A série {seriesId} é do tipo {seriesType} e representa {seriesName}.',
                'withoutName' => ' A série {seriesId} é do tipo {seriesType}.',
                'separator' => [
                    'middle' => '',
                    'end' => '',
                ],
            ],
        ],
        'data' => [
            'allData' => 'Os dados são os seguintes: ',
            'partialData' => 'Os primeiros {displayCnt} itens são: ',
            'withName' => 'o valor de {name} é {value}',
            'withoutName' => '{value}',
            'separator' => [
                'middle' => ', ',
                'end' => '. ',
            ],
        ],
    ],
];
