<?php

return [
    'exports' => [
        'chunk_size' => 1000,
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => "\n",
            'use_bom' => false,
            'include_separator_line' => false,
            'excel_compatibility' => false,
        ],
    ],
    'imports' => [
        'read_only' => true,
        'heading_row' => [
            'formatter' => 'slug',
        ],
    ],
];