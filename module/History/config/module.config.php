<?php

namespace History;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use History\Controller\Factory\HistoryControllerFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\HistoryController::class => HistoryControllerFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'history' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/history[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\HistoryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'history' => __DIR__ . '/../view',
        ],
    ],
];