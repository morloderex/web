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
            'hashing'       =>  [
                'default'   =>  \App\Libraries\Hashing\TrinityCoreSha1Hasher::class
            ]
        ]
    ],
    
    'relations'     =>  [
        'method'    =>  'accounts'
    ]
];