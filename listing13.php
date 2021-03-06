<?php

class IdeaController extends Zend_Controller_Action
{
    public function rateAction()
    {
        $ideaId = $this->request->getParam('id');
        $rating = $this->request->getParam('rating');

        $useCase = new RateIdeaUseCase(
            new RedisIdeaRepository(
                new \Predis\Client()
            )
        );

        $response = $useCase->execute(
            new RateIdeaRequest($ideaId, $rating)
        );

        $this->redirect('/idea/'.$response->idea->getId());
    }
}

class RedisIdeaRepository implements IdeaRepository
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    // ...
    public function find($id)
    {
        $idea = $this->client->get($this->getKey($id));
        if (!$idea) {
            return null;
        }

        return $idea;
    }
}
