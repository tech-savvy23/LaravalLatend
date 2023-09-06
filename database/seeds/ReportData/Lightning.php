<?php

return [
    'title'   => 'Lightning Distribution Board',
    'reports' => [
        [
            'title'   => 'LDB Available',
            'options' => [
                ['title' => 'Yes'],
                [
                    'title'    => 'No',
                    'messages' => [
                        'Use LDB for power or lightening distribution, so that the  circuits can be separated & protection provided to individual circuits & major or cosstly electrical appliances',
                    ],
                ],
                ['title' => 'Not Requied'],
            ],
        ],
        [
            'title'   => 'No of Outgoing',
            'options' => [
                ['title' => 1],
                ['title' => 2],
                ['title' => 3],
                ['title' => 4],
                ['title' => 5],
                ['title' => 6],
                ['title' => 7],
                ['title' => 8],
                ['title' => 9],
                ['title' => 10],
            ],
        ],
        ['title'  => 'RATING OF OUTGOING MCB (AMPS)'], // Review Later
        [
            'title'   => 'ELCB/RCCB/RCBO AVAILABLE', // Review Later
            'options' => [
                [
                    'title'    => 'Yes',
                    'messages' => [
                        'ELCB Installed?',
                        'RCCB Installed?',
                        'RCBO Installed?',
                        'Rating of ELCB  1) 30 mA      2) 100 mA     3) 300 mA',
                    ],
                ],
                [
                    'title'    => 'No',
                    'messages' => [
                        'It is recommended to install 30 mA rated RCCB & if MCB is also not installed, install 30 mA rated RCBO of appropiate rating (as per the connected load)',
                    ],
                ],
            ],
        ],
        [
            'title'   => 'RATING OF OUTGOING CABLE (SQMM)', // Review Later
            'options' => [
                ['title'    => 'Yes', ],
                ['title'    => 'No', ],
            ],
        ],
        [
            'title'   => 'CABLE CONDUCTOR',
            'options' => [
                ['title'    => 'Copper', ],
                [
                    'title'    => 'Alluminium',
                    'messages' => [
                        'Needed to be replaced with Copper cable as current taking capacity is lower & it\'s get heated at low currents also.',
                    ],
                ],
            ],
        ],
        [
            'title'   => 'CABLE TYPE',
            'options' => [
                [
                    'title'    => 'FLEXIBLE',
                    'message'  => [
                        'If possible, replace the flexible cable with armoured cable, so that earthing can be provided if the cable insulation get damaged. A current of more than 30 mA is harmful for humans.',
                    ],
                ],
                ['title'    => 'ARMOURED'],
            ],
        ],
        [
            'title'   => 'CABLE',
            'options' => [
                [
                    'title'    => 'Single',
                    'message'  => [
                        'It is needed to be replaced, with the multi strand cable, as the single strand conducter can be broken if sharp bend is provided in the cable & if internal cracks are formed then the current carrying capacity will be reduced & the cable will start heating & if the insulation is heated then electrcial short circuit may happen & electrcial fire may occur.',
                    ],
                ],
                ['title'    => 'MULTI STRAND'],
            ],
        ],
        [
            'title'   => 'Opening in the LDB where  MCB are placed',
            'options' => [
                ['title'    => 'Yes', ],
                [
                    'title'    => 'No',
                    'message'  => ['Neeeded to be plugged with the help of blank plates', ],
                ],
            ],
        ],
        [
            'title'   => 'Opening from the Gland side in the LDB',
            'options' => [
                [
                    'title'    => 'Yes',
                    'message'  => [
                        'Opening from the gland plates needed to be plugged with the help of Silicon or M-seal. From the opening lizards & other insects may enter the LDB & the electrical short circuit may happen. ',
                    ],
                ],
                ['title'    => 'No', ],
            ],
        ],
        [
            'title'   => 'Ratings of the MCB &  outgoing wire are matching.',
            'options' => [
                ['title'    => 'Yes', ],
                [
                    'title'    => 'No',
                    'message'  => [
                        'The rating of MCB has to be in according to the outgoing cable, so tha the overloading & heating of cables can be avoided.',
                    ],
                ],
            ],
        ],
        [
            'title'   => 'Is earthing connected  to the door for the discharge of any static charge.',
            'options' => [
                ['title'    => 'Yes', ],
                [
                    'title'    => 'No',
                    'message'  => [
                        'Static charge can build up on the surface of an object, due to the imbalace between the negative and positive charges in an object. One way to discharge them through an earthing circit.',
                    ],
                ],
            ],
        ],
        [
            'title'   => 'any joint in the cables are observed',
            'options' => [
                [
                    'title'    => 'Yes',
                    'message'  => [
                        'Joints in the cables should be avoided as joints in cables causes the heating of the cables or spark in the cables, which can lead to an electrcial fire. If joints are to be provided in the wires or cables, they needed to be inspected on weekly basis for any heating. Heating can be observed by seeing the color of the insulation of the  wires.',
                    ],
                ],
                ['title'    => 'No', ],
            ],
        ],
        [
            'title'   => 'Visible earthing available',
            'options' => [
                ['title'    => 'Yes', ],
                [
                    'title'    => 'No',
                    'message'  => [
                        'Atleast single visible earthing should be available in the DB for home & shops. For warehouses & other large facilities, double visible earthing should be available.',
                    ],
                ],
            ],
        ],
        [
            'title'   => 'Any insects, like lizards etc., were witnessed ',
            'options' => [
                [
                    'title'    => 'Yes',
                    'message'  => [
                        'Lizards or any other insects can cause the electrcial short circuit in the LDB. Needed to  check the opening in the LDB from where the insects are coming &  it\'s needed to be plugged.',
                    ],
                ],
                ['title'    => 'No', ],
            ],
        ],
        [
            'title'   => 'Any Heating Observed inside the LDB',
            'options' => [
                [
                    'title'    => 'Yes',
                    'message'  => [
                        'Heating inside the LDB needed to be looked on weekly basis & all the terminations needed to  be tighten on monthly basis. Mormally heating starts with loose terminations only.',
                    ],
                ],
                ['title'    => 'No', ],
            ],
        ],
        [
            'title'   => 'Multiple Cables termianted in the one MCB',
            'options' => [
                [
                    'title'    => 'Yes',
                    'message'  => [
                        'Avoid termianting multiple wires or cables in one MCB, as termianting multiple cable in one MCB lead to loose termination, which will result in heating of wires. Such conditions can be avoided by the use of lugs. ',
                    ],
                ],
                ['title'    => 'No', ],
            ],
        ],
        [
            'title'   => 'Any other Observation',
            'options' => [
                [
                    'title'    => 'Yes',
                    'message'  => ['Write down your observations'],
                ],
                ['title'    => 'No', ],
            ],
        ],
    ],
];
