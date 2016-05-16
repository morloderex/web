<?php return [
    'mapEagerly'    =>  true,
    
    'emulator'  =>  [
        'core'  =>  [
            'supported' =>  [
                'TrinityCore'   =>  [
                    'model'     =>  \App\Models\Emulators\TrinityCore\Account::class
                ]
            ]
        ],
        'account'   =>  [
            'attributes'    =>  [
                'expansion' =>  3 // WotLK
            ],
        ]
    ],
    
    'relations'     =>  [
        'method'    =>  'accounts'
    ]
];