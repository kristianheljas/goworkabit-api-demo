<?php

namespace App\JsonApi\V1\WorkBits;

use App\Models\WorkBit;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'work-bits';

    /**
     * @param \App\Models\WorkBit $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\WorkBit $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'title' => $resource->title,
            'description' => $resource->description,
            'date' => $resource->date,
            'createdAt' => $resource->created_at,
            'updatedAt' => $resource->updated_at,
        ];
    }

    /**
     * List relationship resources
     *
     * @param WorkBit $post
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array|\bool[][]
     */
    public function getRelationships($workBit, $isPrimary, array $includeRelationships)
    {
        return [
            'author' => [
                self::SHOW_RELATED => true,
                self::SHOW_DATA => true,
                self::DATA => function () use ($workBit) {
                    return $workBit->author;
                },
            ],
        ];
    }
}
