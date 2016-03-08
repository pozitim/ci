<?php

namespace Pozitim\CI\Database;

use Pozitim\CI\Database\Entity\JobEntity;

interface JobEntityFetcher
{
    /**
     * @param $id
     * @return JobEntity
     */
    public function fetchOneObjectById($id);

    /**
     * @param $buildId
     * @return array
     */
    public function fetchAllObjectsByBuild($buildId);
}
