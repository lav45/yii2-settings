<?php

namespace lav45\settings\tests\helpers;

use lav45\settings\helpers\ArrayHelper;

class ArrayHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for [[testSetValue()]]
     * @return array test data
     */
    public function dataProviderExport()
    {
        return [
            [
                [
                    'key1' => 'val1',
                    'key2' => 'val2',
                ],
                'key', 'val',
                [
                    'key1' => 'val1',
                    'key2' => 'val2',
                    'key' => 'val',
                ],
            ],
            [
                [
                    'key1' => 'val1',
                    'key2' => 'val2',
                ],
                'key2', 'val',
                [
                    'key1' => 'val1',
                    'key2' => 'val',
                ],
            ],

            [
                [
                    'key1' => 'val1',
                ],
                'key.in', 'val',
                [
                    'key1' => 'val1',
                    'key' => ['in' => 'val'],
                ],
            ],
            [
                [
                    'key' => 'val1',
                ],
                'key.in', 'val',
                [
                    'key' => [
                        'val1',
                        'in' => 'val'
                    ],
                ],
            ],
            [
                [
                    'key' => 'val1',
                ],
                'key', ['in' => 'val'],
                [
                    'key' => ['in' => 'val'],
                ],
            ],

            [
                [
                    'key1' => 'val1',
                ],
                'key.in.0', 'val',
                [
                    'key1' => 'val1',
                    'key' => [
                        'in' => ['val']
                    ],
                ],
            ],

            [
                [
                    'key1' => 'val1',
                ],
                'key.in.arr', 'val',
                [
                    'key1' => 'val1',
                    'key' => [
                        'in' => [
                            'arr' => 'val'
                        ]
                    ],
                ],
            ],
            [
                [
                    'key1' => 'val1',
                ],
                'key.in.arr', ['val'],
                [
                    'key1' => 'val1',
                    'key' => [
                        'in' => [
                            'arr' => ['val']
                        ]
                    ],
                ],
            ],
            [
                [
                    'key' => [
                        'in' => ['val1']
                    ],
                ],
                'key.in.arr', 'val',
                [
                    'key' => [
                        'in' => [
                            'val1',
                            'arr' => 'val'
                        ]
                    ],
                ],
            ],

            [
                [
                    'key' => ['in' => 'val1'],
                ],
                'key.in.arr', ['val'],
                [
                    'key' => [
                        'in' => [
                            'val1',
                            'arr' => ['val']
                        ]
                    ],
                ],
            ],
            [
                [
                    'key' => [
                        'in' => [
                            'val1',
                            'key' => 'val'
                        ]
                    ],
                ],
                'key.in.0', ['arr' => 'val'],
                [
                    'key' => [
                        'in' => [
                            ['arr' => 'val'],
                            'key' => 'val'
                        ]
                    ],
                ],
            ],
            [
                [
                    'key' => [
                        'in' => [
                            'val1',
                            'key' => 'val'
                        ]
                    ],
                ],
                'key.in', ['arr' => 'val'],
                [
                    'key' => [
                        'in' => ['arr' => 'val']
                    ],
                ],
            ],
            [
                [
                    'key' => [
                        'in' => [
                            'key' => 'val',
                            'data' => [
                                'attr1',
                                'attr2',
                                'attr3',
                            ]
                        ]
                    ],
                ],
                'key.in.schema', 'array',
                [
                    'key' => [
                        'in' => [
                            'key' => 'val',
                            'schema' => 'array',
                            'data' => [
                                'attr1',
                                'attr2',
                                'attr3',
                            ]
                        ]
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderExport
     *
     * @param array $array_input
     * @param string $key
     * @param mixed $value
     * @param array $expected
     */
    public function testSetValue($array_input, $key, $value, $expected)
    {
        $result = ArrayHelper::setValue($array_input, $key, $value);
        static::assertEquals($expected, $result);
    }
}