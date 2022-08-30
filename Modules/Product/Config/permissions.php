<?php

return [
    'admin.products' => [
        'index' => 'product::permissions.index',
        'create' => 'product::permissions.create',
        'edit' => 'product::permissions.edit',
        'destroy' => 'product::permissions.destroy',
    ],
    'admin.suppliers' => [
        'index' => 'supplier::permissions.index',
        'create' => 'supplier::permissions.create',
        'edit' => 'supplier::permissions.edit',
        'destroy' => 'supplier::permissions.destroy',
    ],
];
