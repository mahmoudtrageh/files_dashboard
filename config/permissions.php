<?php

return [
    'models_path' => 'Models',
    
    'excluded_models' => [
        'Permission', 'Role', 'BaseModel', 'Pivot', 'Migration', 'Password'
    ],
    
    'standard_actions' => [
        'view', 'create', 'edit', 'delete'
    ],
    
    'roles' => [
        'super-admin' => ['all'],
        'admin' => ['view', 'create', 'edit', 'delete'],
        'manager' => ['view', 'create', 'edit'],
        'editor' => ['view', 'create', 'edit'],
        'viewer' => ['view']
    ],
    
    'custom_model_actions' => [
        'Post' => ['publish', 'feature', 'archive'],
        'User' => ['impersonate', 'block'],
        'Order' => ['refund', 'ship', 'cancel'],
        'Product' => ['feature', 'discount']
    ],
    
    'custom_permissions' => [
        'dashboard.view',
    ],
    
    'admin_email' => 'admin@example.com',

    'guard_name' => 'admin', 
];