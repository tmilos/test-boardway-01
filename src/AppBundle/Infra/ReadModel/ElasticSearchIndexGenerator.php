<?php

namespace AppBundle\Infra\ReadModel;

use Broadway\ReadModel\ElasticSearch\ElasticSearchRepository;

class ElasticSearchIndexGenerator
{
    /** @var ElasticSearchRepository[] */
    private $repositories;

    /**
     * @param array $repositories
     */
    public function __construct(array $repositories)
    {
        $this->repositories = $repositories;
    }

    public function generateElasticSearchIndices()
    {
        foreach ($this->repositories as $repository) {
            $repository->createIndex();
        }
    }
}
