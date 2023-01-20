<?php

namespace Jayrods\AluraMvc\Controller;

use Nyholm\Psr7\Response;
use Jayrods\AluraMvc\Controller\RequestHandlerInterface;
use Jayrods\AluraMvc\Controller\Traits\FlashMessage;
use Jayrods\AluraMvc\Repository\VideoRepository;
use Jayrods\AluraMvc\Repository\RepositoryFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeleteImageController implements RequestHandlerInterface
{
    use FlashMessage;

    /**
     * 
     */
    private VideoRepository $videoRepository;

    /**
     * 
     */
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->videoRepository = $repositoryFactory->create('Video');
    }

    /**
     * 
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);

        if ($id === false or $id === null) {
            $this->addErrorMessage('Error: Could not delete image. Image not found');
            return new Response(302, [
                'Location' => '/'
            ]);
        }

        $result = $this->videoRepository
            ->removeImage($id);

        if ($result) {
            $this->addSuccessMessage('Image deleted');
            return new Response(200, [
                'Location' => '/'
            ]);
        } else {
            $this->addErrorMessage('Error: Could not delete image');
            return new Response(302, [
                'Location' => '/'
            ]);
        }
    }
}
