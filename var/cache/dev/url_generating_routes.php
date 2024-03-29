<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    '_wdt' => [['token'], ['_controller' => 'web_profiler.controller.profiler::toolbarAction'], [], [['variable', '/', '[^/]++', 'token', true], ['text', '/_wdt']], [], [], []],
    '_profiler_home' => [[], ['_controller' => 'web_profiler.controller.profiler::homeAction'], [], [['text', '/_profiler/']], [], [], []],
    '_profiler_search' => [[], ['_controller' => 'web_profiler.controller.profiler::searchAction'], [], [['text', '/_profiler/search']], [], [], []],
    '_profiler_search_bar' => [[], ['_controller' => 'web_profiler.controller.profiler::searchBarAction'], [], [['text', '/_profiler/search_bar']], [], [], []],
    '_profiler_phpinfo' => [[], ['_controller' => 'web_profiler.controller.profiler::phpinfoAction'], [], [['text', '/_profiler/phpinfo']], [], [], []],
    '_profiler_xdebug' => [[], ['_controller' => 'web_profiler.controller.profiler::xdebugAction'], [], [['text', '/_profiler/xdebug']], [], [], []],
    '_profiler_search_results' => [['token'], ['_controller' => 'web_profiler.controller.profiler::searchResultsAction'], [], [['text', '/search/results'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_open_file' => [[], ['_controller' => 'web_profiler.controller.profiler::openAction'], [], [['text', '/_profiler/open']], [], [], []],
    '_profiler' => [['token'], ['_controller' => 'web_profiler.controller.profiler::panelAction'], [], [['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_router' => [['token'], ['_controller' => 'web_profiler.controller.router::panelAction'], [], [['text', '/router'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_exception' => [['token'], ['_controller' => 'web_profiler.controller.exception_panel::body'], [], [['text', '/exception'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    '_profiler_exception_css' => [['token'], ['_controller' => 'web_profiler.controller.exception_panel::stylesheet'], [], [['text', '/exception.css'], ['variable', '/', '[^/]++', 'token', true], ['text', '/_profiler']], [], [], []],
    'app_course_index' => [[], ['_controller' => 'App\\Controller\\CourseController::index'], [], [['text', '/courses/']], [], [], []],
    'app_course_new' => [[], ['_controller' => 'App\\Controller\\CourseController::new'], [], [['text', '/courses/new']], [], [], []],
    'app_course_show' => [['id'], ['_controller' => 'App\\Controller\\CourseController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/courses']], [], [], []],
    'app_course_edit' => [['id'], ['_controller' => 'App\\Controller\\CourseController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/courses']], [], [], []],
    'app_course_delete' => [['id'], ['_controller' => 'App\\Controller\\CourseController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/courses']], [], [], []],
    'app_lesson_new' => [['id'], ['_controller' => 'App\\Controller\\CourseController::newLesson'], [], [['text', '/new/lesson'], ['variable', '', '[^/]++', 'id', true], ['text', '/courses']], [], [], []],
    'app_lesson_show' => [['id'], ['_controller' => 'App\\Controller\\LessonController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/lessons']], [], [], []],
    'app_lesson_edit' => [['id'], ['_controller' => 'App\\Controller\\LessonController::edit'], [], [['text', '/edit'], ['variable', '/', '[^/]++', 'id', true], ['text', '/lessons']], [], [], []],
    'app_lesson_delete' => [['id'], ['_controller' => 'App\\Controller\\LessonController::delete'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/lessons']], [], [], []],
    'index' => [[], ['_controller' => 'App\\Controller\\CourseController::index'], [], [['text', '/']], [], [], []],
];
