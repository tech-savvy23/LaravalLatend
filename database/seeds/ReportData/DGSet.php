<?php

return [
    'title' => 'DG Set/Inverter/UPS/Solar Panels/Change Over Switch',
    'types' => [
        [
            'title'   => 'DG Set',
            'reports' => [
                [
                    'title'      => 'Available',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                    ],
                ],
                [
                    'title'      => 'fencing was available',
                    'options'    => [
                        ['title'=>'Yes'],
                        [
                            'title'    => 'No',
                            'messages' => ['provide the barricading of the DG area.'],
                        ],
                    ],
                ],
                [
                    'title'      => 'gate was locked',
                    'options'    => [
                        ['title'=>'Yes'],
                        [
                            'title'    => 'No',
                            'messages' => ['DG gate have to be closed & locked all the time & only authorized person should be allowed to enter the DG premises. '],
                        ],
                    ],
                ],
                [
                    'title'      => 'fencing & gate earthing available for static charge',
                    'options'    => [
                        ['title'=>'Yes'],
                        [
                            'title'    => 'No',
                            'messages' => ['DG gate needed to be earthed from two separate earthpits or earthing grids, so that any static charge can be discharged'],
                        ],
                    ],
                ],
                [
                    'title'      => 'danger sign with voltage level available',
                    'options'    => [
                        ['title'=>'Yes'],
                        [
                            'title'    => 'No',
                            'messages' => ['Danger sign along with voltage level needed to be displayed in HINDI, ENGLISH & LOCAL LANGUAGE'],
                        ],
                    ],
                ],
                [
                    'title'      => 'authorization list displayed',
                    'options'    => [
                        ['title'=>'Yes'],
                        [
                            'title'    => 'No',
                            'messages' => ['Authorization list to be displayed along with their Supervisory licence & wireman licence number alng with date of validity.'],
                        ],
                    ],
                ],
                [
                    'title'      => 'DG Chimney height was appropite',
                    'options'    => [
                        ['title'=>'Yes'],
                        [
                            'title'    => 'No',
                            'messages' => ['During the start of DG black smoke comes, which means CO gas generation takes place which is harmful for human beings. Carbon mono-oxide is a poisnous gas.'],
                        ],
                    ],
                ],
                [
                    'title'      => 'Insulaton was available on DG Chimney',
                    'options'    => [
                        ['title'=>'Yes'],
                        [
                            'title'    => 'No',
                            'messages' => ['Asbestor rope or hot insulation needed to be done on the DG stack chimney, as the DG exhaust temperature is around 500 deg C'],
                        ],
                    ],
                ],
                [
                    'title'      => 'dg cables termianted at dg point',
                    'options'    => [
                        ['title'=>'MCB'],
                        ['title'    => 'MCCB'],
                        ['title'    => 'KitKat Fuse'],
                        ['title'    => 'terminal blocks'],
                        ['title'    => 'Joints'],
                        ['title'    => 'Directly termianted to change over switch'],
                    ],
                ],
                [
                    'title'      => 'DG TYPE',
                    'options'    => [
                        ['title'=>'Single phase'],
                        ['title'    => '3 Phase'],
                    ],
                ],
                ['title'      => 'Rated KVA', ],
                ['title'      => 'RATED VOLTAGE (VOLTS)', ],
                ['title'      => 'CURRENT RATING OF DG ALTERNATOR (AMPS)', ],
                ['title'      => 'CURRENT RATING OF THE PROTECTION DEVICE (AMPS)', ],
                [
                    'title'      => 'Rating Of Protection device w.r.t the Alternator rating',
                    'options'    => [
                        [
                            'title'    => 'Higher',
                            'messages' => ['Over load protection has to be equal to less than the alternator current rating, so that the alternator winding should not burnt or over heated'],
                        ],
                        ['title'    => 'Lesser'],
                        ['title'    => 'Equals', ],
                    ],
                ],
                [
                    'title'      => 'heating observed in the protection device',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> ['If heating observed in the protection device than current rating of the protection device is derated. & protection device  needed to be replaced.'],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Heating Observed at the termination point (Incomer)- Isolation Device',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Current rating is not sufficient of Isolation Device.',
                                'Current rating of cable is not sufficient. ',
                                'Termianation were loose.',
                                'Others kindly mention',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Heating Observed at the termination point (Outgoing)- Isolation Device',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Current rating is not sufficient of Isolation Device.',
                                'Current rating of cable is not sufficient. ',
                                'Termianation were loose.',
                                'Others kindly mention',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Heating Observed at the termination point (Outgoing)- Isolation Device',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Current rating is not sufficient of Isolation Device.',
                                'Current rating of cable is not sufficient. ',
                                'Termianation were loose.',
                                'Others kindly mention',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Lugs available at termination (Incomer)-- Isolation Device',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Lugs help in correct termination & also avoids heating at termiantion points.',
                                'Lugs also avoids loose connections.',
                                'Connections needed to  be tighten on forthnightly basis to avoid loose connecton.',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Lugs available at termination (Outgoing)- Isolation Device',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Lugs help in correct termination & also avoids heating at termiantion points.',
                                'Lugs also avoids loose connections.',
                                'Connections needed to  be tighten on forthnightly basis to avoid loose connecton.',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Termiantion done with the cable glands (Incomer)',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Double compressor glands should be used for termiantion. Double compressor glands helps in providing strength to the cable & provides earthing to the cable.',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Termiantion done with the cable glands (Outgoing)',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Double compressor glands should be used for termiantion. Double compressor glands helps in providing strength to the cable & provides earthing to the cable.',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Opening observed in the gland plate',
                    'options'    => [
                        [
                            'title'   => 'Yes',
                            'messages'=> [
                                'Opening needed to be plugged with M-seal or Silicon. From openings insects like lizards can enter the live termiantion point.',
                            ],
                        ],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'DG NEUTRAL WAS EARTHED',
                    'options'    => [
                        ['title'   => 'Yes', ],
                        [
                            'title'   => 'No',
                            'messages'=> [
                                'DG neutral needed to b earthed from 2 separate dedicated earth pits .  ',
                                'Neutral earthpits should not be used for any other earthing.',
                                'Earth pits resistance values needed to be displayed on earthpits.',
                                'Earthpit resistance values to be needed to be inspected on quarterly basis as with the change in weather the soil resistivity changes & with change in soil resistivity the earthpit resistance values also changes.',
                            ],
                        ],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'DG BODY WAS EARTHING',
                    'options'    => [
                        ['title'   => 'Yes', ],
                        [
                            'title'   => 'No',
                            'messages'=> [
                                'DG neutral needed to b earthed from 2 separate dedicated earth pits .  ',
                                'Neutral earthpits should not be used for any other earthing.',
                                'Earth pits resistance values needed to be displayed on earthpits.',
                                'Earthpit resistance values to be needed to be inspected on quarterly basis as with the change in weather the soil resistivity changes & with change in soil resistivity the earthpit resistance values also changes.',
                            ],
                        ],
                        ['title'=> 'N.A.'],
                    ],
                ],
                ['title'      => 'DG NEUTRAL EARTHPIT VALUES (Ω)', ], // review
                ['title'      => 'DG BODY EARTHPIT VALUES (Ω)', ], // review
                [
                    'title'      => 'opening observed in the dg control panel',
                    'options'    => [
                        ['title'   => 'Yes', ],
                        [
                            'title'   => 'No',
                            'messages'=> [
                                'All the opening needed to be plugged with M-seal or Silicon',
                            ],
                        ],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'lub oil level',
                    'options'    => [
                        ['title'   => 'OK', ],
                        [
                            'title'   => 'Not OK',
                            'messages'=> [
                                'Lub Oil level needed to be moniotred on daily bais & it\'s level has to be OK all the time.',
                            ],
                        ],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'foam type or abc type fire extinguisher available',
                    'options'    => [
                        ['title'   => 'Available', ],
                        [
                            'title'   => 'Not Available',
                            'messages'=> [
                                'One FOAM or ABC type fire extinguisher to be kept near the DG along with the sand buckets. For Homes sand buckets are not applicable',
                            ],
                        ],
                    ],
                ],
                [
                    'title'      => 'Spillage of diesel oil or mobile oil observed',
                    'options'    => [
                        ['title'   => 'Yes', ],
                        [
                            'title'   => 'No',
                            'messages'=> [
                                'No fuel or lub oil spillage should be their in the DG area, as the fuels (diesel, kerosene, petrol etc) is highly flammable.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'      => 'dg diesel day tank was earthed (double earthing)',
                    'options'    => [
                        ['title'   => 'Yes', ],
                        [
                            'title'   => 'No',
                            'messages'=> [
                                'DG day needed to be earthed from 2 separate earth pits or earthing grids. ',
                            ],
                        ],
                    ],
                ],
                [
                    'title'      => 'DG pipe line flanges are earthed for static charge ',
                    'options'    => [
                        ['title'   => 'Yes', ],
                        [
                            'title'   => 'No',
                            'messages'=> [
                                'DG pipeline flanges needed to be earthed from two separate earthpits or earthing grids, so that any static charge can be discharged',
                            ],
                        ],
                    ],
                ],
                [
                    'title'      => 'Check the Diesel pumps & tanks are installed as per the approved drawing',
                    'options'    => [
                        ['title'   => 'Yes', ],
                        [
                            'title'   => 'No',
                            'messages'=> [
                                'All the pumps & fuel storage tanks should be installed as per the approved drawing from Petroleum & Explosive department. 2) Date of certificate needed to be cross check for validity.',
                            ],
                        ],
                    ],
                ],
                [
                    'title'      => 'DG last maintence record',
                    'options'    => [
                        ['title'   => 'Available', ],
                        [
                            'title'   => 'Not Available',
                            'messages'=> [
                                'Check the last maintenace record & the last B check done date & when next check is due.',
                            ],
                        ],
                        ['title'   => 'Not Required', ],
                    ],
                ],
                [
                    'title'      => 'DG Duty Record',
                    'options'    => [
                        ['title'   => 'Paid', ],
                        [
                            'title'   => 'Not Paid',
                            'messages'=> [
                                'DG duty needed to be submitted on quarterly basis & it\'s records needed to be maintained.',
                            ],
                        ],
                        ['title'   => 'Not Required', ],
                        ['title'   => 'last paid record updated upto', ],
                    ],
                ],
                [
                    'title'      => 'DG Stack Monitoring Record',
                    'options'    => [
                        ['title'   => 'Available', ],
                        [
                            'title'   => 'Not Available',
                            'messages'=> [
                                'DG SOX, NOX values to be recorded on halfyearly basis from a government approved laboratory/Vendor & it\'s record needed to be maintained.',
                            ],
                        ],
                        ['title'   => 'Not Required', ],
                        ['title'   => 'last paid record updated upto', ],
                    ],
                ],
            ],
        ],
        [
            'title'   => 'ups/invertor',
            'reports' => [
                [
                    'title'      => 'Battery room/UPS ROOM was locked',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Danger sing with voltage level was available',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Authorization list was displayed',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Condition of Cable Termination  in the Invertor',
                    'options'    => [
                        ['title'=>'OK'],
                        ['title'=> 'Not OK'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'All fans of UPS are working',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Any alarm on UPS',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Earthing of UPS',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Rubber Mat Available',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Emergency Light Available ',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Emergency light is marked',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Battery stand is earthed',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Battery terminals conditions',
                    'options'    => [
                        ['title'=>'Ok'],
                        ['title'=> 'Not OK'],
                    ],
                ],
                [
                    'title'      => 'Battery stand is covered with acrylic sheet ',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                    ],
                ],
                [
                    'title'      => 'Electrolylite level in Batteries.',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Specific gravity of battery electrolyte.',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'When was batteries were last discharged.',
                    'options'    => [
                        ['title'=>'Checked'],
                        ['title'=> 'No Record Found'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Battery charging voltage at boost charge (Volts)',
                ],
                [
                    'title'      => 'Battery charging voltage at float charge',
                ],
                [
                    'title'      => 'Any bulging was observed in the batteries.',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Hydrogen level indicator & sensors were present in the battery room.',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Exhaust fan is available in the battery room',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Air-conditioner was available in the battery room',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Is the false ceiling is fire retardant',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'Battery MCCB rating (Amps)',
                ],
                [
                    'title'      => 'Opening observed in the battery terminal MCCB',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
                [
                    'title'      => 'FM-200',
                ],
                [
                    'title'      => 'Fire Extinguisher available in the battery room.',
                    'options'    => [
                        ['title'=>'Yes'],
                        ['title'=> 'No'],
                        ['title'=> 'N.A.'],
                    ],
                ],
            ],
        ],
    ],
];
