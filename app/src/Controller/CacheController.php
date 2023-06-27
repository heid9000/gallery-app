<?php

namespace App\Controller;

use App\Request\CacheRequest;
use App\Service\CacheService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CacheController extends AbstractController
{

    /**
     * @param CacheService $cacheService
     */
    public function __construct(
        private CacheService $cacheService
    )
    {
    }

    /**
     * @TODO move to param-converter
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return CacheRequest|null
     */
    private function createCacheRequest(Request $request, ValidatorInterface $validator): ?CacheRequest
    {
        $cacheRequest = new CacheRequest();
        $cacheRequest->setSize($request->get('size'));
        $cacheRequest->setName($request->get('name'));
        $errors = $validator->validate($cacheRequest);
        if ($errors->count() > 0) {
            return null;
        }
        return $cacheRequest;
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/cache', methods: ['GET'])]
    public function getImage(Request $request, ValidatorInterface $validator): Response
    {
        if (($cacheRequest = $this->createCacheRequest($request, $validator)) === null) {
            return new Response('invalid params', 500);
        }
        $cache = $this->cacheService->findCache($cacheRequest);
        if ($cache === null) {
            return new Response('cache not found', 404);
        }
        return new BinaryFileResponse($cache->getSrc());
    }
}