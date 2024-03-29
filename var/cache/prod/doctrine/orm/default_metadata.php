<?php

// This file has been auto-generated by the Symfony Cache Component.

return [[

'App__Entity__Course__CLASSMETADATA__' => 0,
'App__Entity__Lesson__CLASSMETADATA__' => 1,

], [

0 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            clone (($p = &\Symfony\Component\VarExporter\Internal\Registry::$prototypes)['Doctrine\\ORM\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Doctrine\\ORM\\Mapping\\ClassMetadata')),
            clone ($p['Doctrine\\ORM\\Id\\SequenceGenerator'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Doctrine\\ORM\\Id\\SequenceGenerator')),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'App\\Entity\\Course',
                ],
                'namespace' => [
                    'App\\Entity',
                ],
                'rootEntityName' => [
                    'App\\Entity\\Course',
                ],
                'customRepositoryClassName' => [
                    'App\\Repository\\CourseRepository',
                ],
                'identifier' => [
                    [
                        'id',
                    ],
                ],
                'generatorType' => [
                    2,
                ],
                'fieldMappings' => [
                    [
                        'id' => [
                            'fieldName' => 'id',
                            'type' => 'integer',
                            'scale' => null,
                            'length' => null,
                            'unique' => false,
                            'nullable' => false,
                            'precision' => null,
                            'id' => true,
                            'columnName' => 'id',
                        ],
                        'code' => [
                            'fieldName' => 'code',
                            'type' => 'string',
                            'scale' => null,
                            'length' => 255,
                            'unique' => true,
                            'nullable' => false,
                            'precision' => null,
                            'columnName' => 'code',
                        ],
                        'name' => [
                            'fieldName' => 'name',
                            'type' => 'string',
                            'scale' => null,
                            'length' => 255,
                            'unique' => false,
                            'nullable' => false,
                            'precision' => null,
                            'columnName' => 'name',
                        ],
                        'description' => [
                            'fieldName' => 'description',
                            'type' => 'string',
                            'scale' => null,
                            'length' => 1000,
                            'unique' => false,
                            'nullable' => true,
                            'precision' => null,
                            'columnName' => 'description',
                        ],
                    ],
                ],
                'fieldNames' => [
                    [
                        'id' => 'id',
                        'code' => 'code',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                ],
                'columnNames' => [
                    [
                        'id' => 'id',
                        'code' => 'code',
                        'name' => 'name',
                        'description' => 'description',
                    ],
                ],
                'table' => [
                    [
                        'name' => 'course',
                    ],
                ],
                'associationMappings' => [
                    [
                        'lessons' => [
                            'fieldName' => 'lessons',
                            'mappedBy' => 'course',
                            'targetEntity' => 'App\\Entity\\Lesson',
                            'cascade' => [],
                            'orphanRemoval' => true,
                            'fetch' => 2,
                            'type' => 4,
                            'inversedBy' => null,
                            'isOwningSide' => false,
                            'sourceEntity' => 'App\\Entity\\Course',
                            'isCascadeRemove' => true,
                            'isCascadePersist' => false,
                            'isCascadeRefresh' => false,
                            'isCascadeMerge' => false,
                            'isCascadeDetach' => false,
                        ],
                    ],
                ],
                'idGenerator' => [
                    $o[1],
                ],
                'sequenceGeneratorDefinition' => [
                    [
                        'sequenceName' => 'course_id_seq',
                        'allocationSize' => '1',
                        'initialValue' => '1',
                    ],
                ],
            ],
        ],
        $o[0],
        [
            -1 => [
                'allocationSize' => 1,
                'sequenceName' => 'course_id_seq',
            ],
        ]
    );
},
1 => static function () {
    return \Symfony\Component\VarExporter\Internal\Hydrator::hydrate(
        $o = [
            clone (($p = &\Symfony\Component\VarExporter\Internal\Registry::$prototypes)['Doctrine\\ORM\\Mapping\\ClassMetadata'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Doctrine\\ORM\\Mapping\\ClassMetadata')),
            clone ($p['Doctrine\\ORM\\Id\\SequenceGenerator'] ?? \Symfony\Component\VarExporter\Internal\Registry::p('Doctrine\\ORM\\Id\\SequenceGenerator')),
        ],
        null,
        [
            'stdClass' => [
                'name' => [
                    'App\\Entity\\Lesson',
                ],
                'namespace' => [
                    'App\\Entity',
                ],
                'rootEntityName' => [
                    'App\\Entity\\Lesson',
                ],
                'customRepositoryClassName' => [
                    'App\\Repository\\LessonRepository',
                ],
                'identifier' => [
                    [
                        'id',
                    ],
                ],
                'generatorType' => [
                    2,
                ],
                'fieldMappings' => [
                    [
                        'id' => [
                            'fieldName' => 'id',
                            'type' => 'integer',
                            'scale' => null,
                            'length' => null,
                            'unique' => false,
                            'nullable' => false,
                            'precision' => null,
                            'id' => true,
                            'columnName' => 'id',
                        ],
                        'name' => [
                            'fieldName' => 'name',
                            'type' => 'string',
                            'scale' => null,
                            'length' => 255,
                            'unique' => false,
                            'nullable' => false,
                            'precision' => null,
                            'columnName' => 'name',
                        ],
                        'content' => [
                            'fieldName' => 'content',
                            'type' => 'text',
                            'scale' => null,
                            'length' => null,
                            'unique' => false,
                            'nullable' => false,
                            'precision' => null,
                            'columnName' => 'content',
                        ],
                        'number' => [
                            'fieldName' => 'number',
                            'type' => 'integer',
                            'scale' => null,
                            'length' => null,
                            'unique' => false,
                            'nullable' => false,
                            'precision' => null,
                            'columnName' => 'number',
                        ],
                    ],
                ],
                'fieldNames' => [
                    [
                        'id' => 'id',
                        'name' => 'name',
                        'content' => 'content',
                        'number' => 'number',
                    ],
                ],
                'columnNames' => [
                    [
                        'id' => 'id',
                        'name' => 'name',
                        'content' => 'content',
                        'number' => 'number',
                    ],
                ],
                'table' => [
                    [
                        'name' => 'lesson',
                    ],
                ],
                'associationMappings' => [
                    [
                        'course' => [
                            'fieldName' => 'course',
                            'joinColumns' => [
                                [
                                    'name' => 'course_id',
                                    'unique' => false,
                                    'nullable' => false,
                                    'onDelete' => null,
                                    'columnDefinition' => null,
                                    'referencedColumnName' => 'id',
                                ],
                            ],
                            'cascade' => [],
                            'inversedBy' => 'lessons',
                            'targetEntity' => 'App\\Entity\\Course',
                            'fetch' => 2,
                            'type' => 2,
                            'mappedBy' => null,
                            'isOwningSide' => true,
                            'sourceEntity' => 'App\\Entity\\Lesson',
                            'isCascadeRemove' => false,
                            'isCascadePersist' => false,
                            'isCascadeRefresh' => false,
                            'isCascadeMerge' => false,
                            'isCascadeDetach' => false,
                            'sourceToTargetKeyColumns' => [
                                'course_id' => 'id',
                            ],
                            'joinColumnFieldNames' => [
                                'course_id' => 'course_id',
                            ],
                            'targetToSourceKeyColumns' => [
                                'id' => 'course_id',
                            ],
                            'orphanRemoval' => false,
                        ],
                    ],
                ],
                'idGenerator' => [
                    $o[1],
                ],
                'sequenceGeneratorDefinition' => [
                    [
                        'sequenceName' => 'lesson_id_seq',
                        'allocationSize' => '1',
                        'initialValue' => '1',
                    ],
                ],
            ],
        ],
        $o[0],
        [
            -1 => [
                'allocationSize' => 1,
                'sequenceName' => 'lesson_id_seq',
            ],
        ]
    );
},

]];
