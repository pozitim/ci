<?php

namespace Pozitim\CI\Database\Entity;

use Pozitim\CI\Database\BuildEntityFetcher;

class JobEntity extends EntityAbstract
{
    public $id;
    public $build_id;
    public $name;
    public $output = '';
    public $exit_code;
    public $started_date;
    public $completed_date;

    /**
     * @var BuildEntity
     */
    protected $buildEntity;

    /**
     * @return BuildEntity
     * @throws \Exception
     */
    public function getBuildEntity()
    {
        if ($this->buildEntity == null) {
            /**
             * @var BuildEntityFetcher $buildEntityFetcher
             */
            $buildEntityFetcher = $this->getDi()->get('build_entity_fetcher');
            $this->setBuildEntity($buildEntityFetcher->fetchOneObjectById($this->build_id));
        }
        return $this->buildEntity;
    }

    /**
     * @param BuildEntity $buildEntity
     */
    public function setBuildEntity($buildEntity)
    {
        $this->buildEntity = $buildEntity;
        $this->build_id = $buildEntity->id;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        $date1 = new \DateTime($this->started_date);
        $date2 = new \DateTime($this->completed_date);
        return $date2->getTimestamp() - $date1->getTimestamp();
    }
}
