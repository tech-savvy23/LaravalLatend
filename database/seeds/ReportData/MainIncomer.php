<?php

return [
    'title'   => 'Main Incomer',
    // 'reports' => [
    //     ['title' => ''],
    // ],
    'types' => [
        [
            'title'   => 'incomer cable',
            'reports' => [
                [
                    'title'   => 'rating (sqmm)',
                    'options' => [
                        ['title' => '0.5', ],
                        ['title' => '0.75', ],
                        ['title' => '1', ],
                        ['title' => '1.5', ],
                        ['title' => '2.5', ],
                        ['title' => '4', ],
                        ['title' => '6', ],
                        ['title' => '10', ],
                        ['title' => '16', ],
                        ['title' => '20', ],
                        ['title' => '25', ],
                        ['title' => '35', ],
                        ['title' => '50', ],
                    ],
                ],
                [
                    'title'   => 'cable moc',
                    'options' => [
                        [
                            'title' => 'Copper', ],
                        [
                            'title'   => 'Alluminium',
                            'messages'=> [
                                'If its Aluminium, needed to be replaced with Copper cable as current taking capacity is lower & it\'s get heated at low currents also.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'type of cable',
                    'options' => [
                        [
                            'title'    => 'Flexible',
                            'messages' => [
                                'If possible, replace the flexible cable with armoured cable, so that earthing can be provided if the cable insulation get damaged. A current of more than 30 mA is harmful for humans.',
                            ],
                        ],
                        [
                            'title'   => 'Armoured',
                        ],
                    ],
                ],
                [
                    'title'   => 'FRLS',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'It is recommended to use Fire retardent & low smoke cable.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'ARMORED',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'Armored cables have more strength than flexible wires.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Available',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'The termination has to be done with the use of lugs only, otherwise their could be problem of loose termination, which can result in heating at termination point. Air comes at termination point & it act as resistance, that\'s why',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Metal',
                    'options' => [
                        [
                            'title'    => 'Copper',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                        [
                            'title'    => 'Alluminum',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Metal',
                    'options' => [
                        [
                            'title'    => 'Copper',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                        [
                            'title'    => 'Alluminum',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Type',
                    'options' => [
                        [
                            'title'    => 'Ring',
                            'messages' => [
                                'Lugs help in proper termination of cables & also avoids heating of cables.',
                            ],
                        ],
                        [
                            'title'    => 'Pin',
                            'messages' => [
                                'Lugs help in proper termination of cables & also avoids heating of cables.',
                            ],
                        ],
                        [
                            'title'    => 'U-Type',
                            'messages' => [
                                'Lugs help in proper termination of cables & also avoids heating of cables.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Termination',
                    'options' => [
                        [
                            'title'    => 'Ok',
                        ],
                        [
                            'title'    => 'Not Ok',
                            'messages' => [
                                'Lug or Thimble Termiantion has to be done properly, loose termination will cause the heating of cables. ',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'heating observed',
                    'options' => [
                        [
                            'title'    => 'Yes',
                            'messages' => [
                                'cable to be replaced',
                                'lug to be replaced',
                                'connector to be replaced',
                                'kit kat fuse to  be replaced',
                                'mcb to be replaced',
                                'mccb to be replaced',
                                'elcb/rccb/rcbo to be replaced',
                            ],
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'Cable may be heated due to the loose termination ',
                                'May be due to dryness of lug, while re-lugging the lug-gel has to be applied inside the lugs just before the crimping of lugs. ',
                                'May be due bi-metallic contact (Copper -Aluminium Joint).',
                                'Cable size is under-rated as per the running load.',
                                'Quality of cable or MCB not ok',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'EARTHING AVAILABLE',
                    'options' => [
                        [
                            'title'    => 'Yes',
                            'messages' => [
                                'cable to be replaced',
                                'lug to be replaced',
                                'connector to be replaced',
                                'kit kat fuse to  be replaced',
                                'mcb to be replaced',
                                'mccb to be replaced',
                                'elcb/rccb/rcbo to be replaced',
                            ],
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'If NO, earthing is needed to be provided, so that in the event of any fault, the fault current should flow to ground & no person should get the electrcial shock & the electrical appliances should also be safe.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'EARTHING VALUE (Ω) -Measured Values',
                    'options' => [
                        [
                            'title'    => 'Yes',
                            'messages' => [
                                'cable to be replaced',
                                'lug to be replaced',
                                'connector to be replaced',
                                'kit kat fuse to  be replaced',
                                'mcb to be replaced',
                                'mccb to be replaced',
                                'elcb/rccb/rcbo to be replaced',
                            ],
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'If NO, earthing is needed to be provided, so that in the event of any fault, the fault current should flow to ground & no person should get the electrcial shock & the electrical appliances should also be safe.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Is the mcb/mccb/kitkat fuse rating suitable as per connected cable',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'Connect the MCB as per the connected wire current rating, so that the heating of cables should not be allowed & no invitation is given to electrcial fire.',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'title'   => 'Outgoing cable',
            'reports' => [
                [
                    'title'   => 'rating (sqmm)',
                    'options' => [
                        ['title' => '0.5', ],
                        ['title' => '0.75', ],
                        ['title' => '1', ],
                        ['title' => '1.5', ],
                        ['title' => '2.5', ],
                        ['title' => '4', ],
                        ['title' => '6', ],
                        ['title' => '10', ],
                        ['title' => '16', ],
                        ['title' => '20', ],
                        ['title' => '25', ],
                        ['title' => '35', ],
                        ['title' => '50', ],
                    ],
                ],
                [
                    'title'   => 'cable moc',
                    'options' => [
                        [
                            'title' => 'Copper', ],
                        [
                            'title'   => 'Alluminium',
                            'messages'=> [
                                'If its Aluminium, needed to be replaced with Copper cable as current taking capacity is lower & it\'s get heated at low currents also.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'type of cable',
                    'options' => [
                        [
                            'title'    => 'Flexible',
                            'messages' => [
                                'If possible, replace the flexible cable with armoured cable, so that earthing can be provided if the cable insulation get damaged. A current of more than 30 mA is harmful for humans.',
                            ],
                        ],
                        [
                            'title'   => 'Armoured',
                        ],
                    ],
                ],
                [
                    'title'   => 'FRLS',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'It is recommended to use Fire retardent & low smoke cable.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'ARMORED',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'Armored cables have more strength than flexible wires.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Available',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'The termination has to be done with the use of lugs only, otherwise their could be problem of loose termination, which can result in heating at termination point. Air comes at termination point & it act as resistance, that\'s why',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Metal',
                    'options' => [
                        [
                            'title'    => 'Copper',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                        [
                            'title'    => 'Alluminum',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Metal',
                    'options' => [
                        [
                            'title'    => 'Copper',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                        [
                            'title'    => 'Alluminum',
                            'messages' => [
                                'With Copper cables, copper lugs to be used & with Aluminum cables, aluminium lugs to be used,',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Type',
                    'options' => [
                        [
                            'title'    => 'Ring',
                            'messages' => [
                                'Lugs help in proper termination of cables & also avoids heating of cables.',
                            ],
                        ],
                        [
                            'title'    => 'Pin',
                            'messages' => [
                                'Lugs help in proper termination of cables & also avoids heating of cables.',
                            ],
                        ],
                        [
                            'title'    => 'U-Type',
                            'messages' => [
                                'Lugs help in proper termination of cables & also avoids heating of cables.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Lugs Termination',
                    'options' => [
                        [
                            'title'    => 'Ok',
                        ],
                        [
                            'title'    => 'Not Ok',
                            'messages' => [
                                'Lug or Thimble Termiantion has to be done properly, loose termination will cause the heating of cables. ',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'heating observed',
                    'options' => [
                        [
                            'title'    => 'Yes',
                            'messages' => [
                                'cable to be replaced',
                                'lug to be replaced',
                                'connector to be replaced',
                                'kit kat fuse to  be replaced',
                                'mcb to be replaced',
                                'mccb to be replaced',
                                'elcb/rccb/rcbo to be replaced',
                            ],
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'Cable may be heated due to the loose termination ',
                                'May be due to dryness of lug, while re-lugging the lug-gel has to be applied inside the lugs just before the crimping of lugs. ',
                                'May be due bi-metallic contact (Copper -Aluminium Joint).',
                                'Cable size is under-rated as per the running load.',
                                'Quality of cable or MCB not ok',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'EARTHING AVAILABLE',
                    'options' => [
                        [
                            'title'    => 'Yes',
                            'messages' => [
                                'cable to be replaced',
                                'lug to be replaced',
                                'connector to be replaced',
                                'kit kat fuse to  be replaced',
                                'mcb to be replaced',
                                'mccb to be replaced',
                                'elcb/rccb/rcbo to be replaced',
                            ],
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'If NO, earthing is needed to be provided, so that in the event of any fault, the fault current should flow to ground & no person should get the electrcial shock & the electrical appliances should also be safe.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'EARTHING VALUE (Ω) -Measured Values',
                    'options' => [
                        [
                            'title'    => 'Yes',
                            'messages' => [
                                'cable to be replaced',
                                'lug to be replaced',
                                'connector to be replaced',
                                'kit kat fuse to  be replaced',
                                'mcb to be replaced',
                                'mccb to be replaced',
                                'elcb/rccb/rcbo to be replaced',
                            ],
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'If NO, earthing is needed to be provided, so that in the event of any fault, the fault current should flow to ground & no person should get the electrcial shock & the electrical appliances should also be safe.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'   => 'Is the mcb/mccb/kitkat fuse rating suitable as per connected cable',
                    'options' => [
                        [
                            'title'    => 'Yes',
                        ],
                        [
                            'title'    => 'No',
                            'messages' => [
                                'Connect the MCB as per the connected wire current rating, so that the heating of cables should not be allowed & no invitation is given to electrcial fire.',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
