<?php

return [
    // Optional
    'controllerNamespace' => 'App\Http\Controllers\Admin', // App\Http\Controllers\Admin
    'controllerPath' => 'Http/Controllers/Admin', // Http/Controllers/Admin

    // Required
    'modelName' => 'Product', // Product,
    'modelInstance' => 'product', // product
    'modelInstancePlural' => 'products', // products
    'resourceName' => 'Products', // Products
    'resourceNameSingular' => 'Product', // Product
    'viewPath' => 'admin/products', // admin/products
    'route' => 'admin.products', // admin.products

    // Data to return from JSON
    'data' => [
        [
            'JSONName' => 'name',
            'databaseColumn' => 'name',
            'tableName' => 'Product name',
        ],
        [
            'JSONName' => 'price',
            'databaseColumn' => 'price',
            'tableName' => 'Product price',
        ],
    ],

    // Fields
    'fields' => [
        [
            'name' => 'name', // Field name property. Ex: name
            'label' => 'Product name', // Field label. Ex: Product Name
            'type' => 'text', // Field type. Ex: text, password, radio, select, textarea
            'create_validation' => ['required', 'string'],
            'edit_validation' => ['required', 'string'],
        ],
        [
            'name' => 'password',
            'label' => 'Password',
            'type' => 'password',
            'create_validation' => ['required', 'string'],
            'edit_validation' => ['required', 'string'],
        ],
        [
            'name' => 'for_sale',
            'label' => 'For sale',
            'type' => 'radio',
            'value' => 'Y',
            'create_validation' => ['required', 'string'],
            'edit_validation' => ['required', 'string'],
        ],
        [
            'name' => 'for_sale',
            'label' => 'Not for sale',
            'type' => 'radio',
            'value' => 'N',
            'create_validation' => ['required', 'string'],
            'edit_validation' => ['required', 'string'],
        ],
        [
            'name' => 'category',
            'label' => 'Select category',
            'type' => 'select',
            'create_validation' => ['required', 'string'],
            'edit_validation' => ['required', 'string'],
        ],
        [
            'name' => 'description',
            'label' => 'Product description',
            'type' => 'textarea',
            'create_validation' => ['required', 'string'],
            'edit_validation' => ['required', 'string'],
        ],
    ],
];