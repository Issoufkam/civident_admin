<?php

return [
    'admin' => [
        [
            'title' => 'Tableau de Bord',
            'icon' => 'speedometer2',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard'
        ],
        [
            'title' => 'Gestion des Agents',
            'icon' => 'people',
            'route' => 'admin.agents.index',
            'active' => 'admin.agents.*',
            'badge' => 'new' // Optionnel
        ],
        [
            'title' => 'Gestion des Régions',
            'icon' => 'geo-alt',
            'route' => 'admin.regions.index',
            'active' => 'admin.regions.*'
        ]
    ],
    'agent' => [
        // Menu spécifique aux agents
    ]
];
