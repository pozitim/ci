<?php

namespace Pozitim\CI\Web\V1;

use Pozitim\CI\Database\Entity\BuildEntity;
use Pozitim\CI\Database\Entity\JobEntity;
use Pozitim\CI\Database\JobEntityFetcher;
use Pozitim\MySQL\Exception\RecordNotFoundException;

class RawJobViewerController extends BaseController
{
    /**
     * @var BuildEntity
     */
    protected $buildEntity;

    /**
     * @var JobEntity
     */
    protected $jobEntity;

    public function view()
    {
        try {
            $this->tryView();
        } catch (RecordNotFoundException $exception) {
            $this->sendPlainText('Not Found!', 404);
        }
    }

    protected function tryView()
    {
        /**
         * @var JobEntityFetcher $jobEntityFetcher
         */
        $jobEntityFetcher = $this->getDi()->get('job_entity_fetcher');
        $jobEntity = $jobEntityFetcher->fetchOneObjectById($this->getHttpRequest()->get('job_id'));
        $this->sendPlainText($jobEntity->output);
    }
}
