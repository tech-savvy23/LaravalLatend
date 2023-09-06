<?php

return [
    'title'   => 'Electrical Connection',
    'reports' => [
        [
            'title'   => 'Phase Type',
            'options' => [
                ['title'=> 'single', ],
                ['title'=> 'three', ],
            ],
        ],
        ['title' => 'sanctioned load (kw)'],
        ['title' => 'connected load (kw)'],
        ['title' => 'running load (kw)'],
        ['title' => 'sanctioned load (kva)'],
        ['title' => 'connected load (kva)'],
        ['title' => 'running load (kva)'],
        ['title' => 'phase current (amps)- r/y/b '],
        ['title' => 'neutral current (amps)'],
        ['title' => 'running power factor'],
    ],
];
