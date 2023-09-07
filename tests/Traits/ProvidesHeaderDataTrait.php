<?php

namespace DannyXCII\HttpComponentTests\Traits;

trait ProvidesHeaderDataTrait
{
    /**
     * @return array
     */
    public static function methodProvider(): array
    {
        return [
            'POST' => [
                'POST',
            ],
            'PUT' => [
                'PUT',
            ],
            'DELETE' => [
                'DELETE',
            ],
            'GET' => [
                'GET',
            ],
            'PATCH' => [
                'PATCH',
            ]
        ];
    }

    /**
     * @return array[]
     */
    public static function headerKeyProvider(): array
    {
        return [
            'Content-Type' => [
                'Content-Type',
            ],
            'content-type' => [
                'content-type',
            ],
            'CONTENT-TYPE' => [
                'CONTENT-TYPE',
            ],
        ];
    }
}