<?php
// permissions.php

// Define user roles and their permissions
$permissions = [
    'admin' => [
        'can_create' => true,
        'can_edit' => true,
        'can_delete' => true,
        'can_view' => true,
    ],
    'editor' => [
        'can_create' => true,
        'can_edit' => true,
        'can_delete' => false,
        'can_view' => true,
    ],
    'viewer' => [
        'can_create' => false,
        'can_edit' => false,
        'can_delete' => false,
        'can_view' => true,
    ],
];

// Function to check if a user has a specific permission
function hasPermission($role, $permission) {
    global $permissions;
    return isset($permissions[$role]) && $permissions[$role][$permission] === true;
}

// Example usage
// if (hasPermission('admin', 'can_create')) {
//     // Allow creating a resource
// }
?>