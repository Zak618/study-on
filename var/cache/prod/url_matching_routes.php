<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/courses' => [[['_route' => 'app_course_index', '_controller' => 'App\\Controller\\CourseController::index'], null, ['GET' => 0], null, true, false, null]],
        '/courses/new' => [[['_route' => 'app_course_new', '_controller' => 'App\\Controller\\CourseController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/' => [[['_route' => 'index', '_controller' => 'App\\Controller\\CourseController::index'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/courses(?'
                    .'|/([^/]++)(?'
                        .'|(*:30)'
                        .'|/edit(*:42)'
                        .'|(*:49)'
                    .')'
                    .'|([^/]++)/new/lesson(*:76)'
                .')'
                .'|/lessons/([^/]++)(?'
                    .'|(*:104)'
                    .'|/edit(*:117)'
                    .'|(*:125)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        30 => [[['_route' => 'app_course_show', '_controller' => 'App\\Controller\\CourseController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        42 => [[['_route' => 'app_course_edit', '_controller' => 'App\\Controller\\CourseController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        49 => [[['_route' => 'app_course_delete', '_controller' => 'App\\Controller\\CourseController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        76 => [[['_route' => 'app_lesson_new', '_controller' => 'App\\Controller\\CourseController::newLesson'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        104 => [[['_route' => 'app_lesson_show', '_controller' => 'App\\Controller\\LessonController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        117 => [[['_route' => 'app_lesson_edit', '_controller' => 'App\\Controller\\LessonController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        125 => [
            [['_route' => 'app_lesson_delete', '_controller' => 'App\\Controller\\LessonController::delete'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
