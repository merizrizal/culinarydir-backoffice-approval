<?php
return [
    'params' => [
        'navigation' => [
            'mainMenu'=> [
                'label' => 'Main Menu',
                'iconClass' => 'fa fa-home',
                'url' => [''],
                'isDirect' => true,
            ],
            'newApplication' => [
                'label' => 'New Application',
                'iconClass' => 'fa fa-check',
                'navigation' => [
                    'pndgApplication' => [
                        'label' => 'Pending',
                        'url' => ['approval/status/pndg-application'],
                        'isDirect' => false,
                    ],
                    'icorctApplication' => [
                        'label' => 'Incorrect',
                        'url' => ['approval/status/icorct-application'],
                        'isDirect' => false,
                    ],
                ],
            ],
        ]
    ]
];