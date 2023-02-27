<?php

return [
    /**
     * Props to pass to the vue-swatches component.
     *
     * See https://saintplay.github.io/vue-swatches/#sub-props
     */
    'props' => [
        // 'colors' => 'basic', // Preset
        // 'colors' => 'material-basic', // Preset
        'colors' => ['#D5DCDD', '#002D31'], // Array

        'show-fallback' => true,
        'fallback-type' => 'input', // Or "color"
    ]
];
