<?php

namespace Pozitim\CI\Database;

use Pozitim\CI\Database\Entity\JobEntity;

interface JobEntitySaver
{
    /**
     * @param JobEntity $entity
     */
    public function insert(JobEntity $entity);

    /**
     * @param JobEntity $entity
     * @param $appendText
     */
    public function appendOutput(JobEntity $entity, $appendText);

    /**
     * @param JobEntity $entity
     * @param string $errorMessage
     */
    public function updateExitCode(JobEntity $entity, $errorMessage = '');
}
