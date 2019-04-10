<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiBaseController;
use App\Services\ShortUrlService;
use App\ShortUrls;
use Illuminate\Http\Request;

class ShortUrlsController extends ApiBaseController
{
    /**
     * @var ShortUrlService
     */
    private $shortUrlService;

    public function __construct(ShortUrlService $shortUrlService)
    {
        $this->shortUrlService = $shortUrlService;
    }

    /**
     * Redirect from short to full url
     * @param $shortUrl
     * @return \Illuminate\Http\JsonResponse
     */
    public function resolve($shortUrl)
    {
        if (!$shortUrlModel = $this->shortUrlService->getByShortUrl($shortUrl)) {
            return $this->sendResponse('', 404, 'Record not found');
        }
        return redirect($shortUrlModel->long_url);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(ShortUrls::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $longUrl = $request->get('long_url');

        $this->shortUrlService->validate(ShortUrlService::METHOD_POST, $request->all());

        $shortUrlModel = $this->shortUrlService->getByLongUrl($longUrl);
        if (!$shortUrlModel) {
            $shortUrlModel = $this->shortUrlService->make($longUrl);
        }

        return $this->sendResponse($shortUrlModel, 201);
    }

    /**
     * Display the short url details
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->shortUrlService->validate(ShortUrlService::METHOD_GET, ['id' => $id]);
        $shortUrlModel = $this->findModel($id);
        return $this->sendResponse($shortUrlModel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ShortUrls $ShortUrls
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['id'] = $id;

        $this->shortUrlService->validate(ShortUrlService::METHOD_PUT, $data);
        $shortUrlModel = $this->shortUrlService->getById($id);
        $shortUrlModel->fill($request->except(['id']))
            ->save();
        return $this->sendResponse($shortUrlModel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $this->shortUrlService->validate(ShortUrlService::METHOD_DELETE, ['id' => $id]);

        $urlModel = $this->findModel($id);
        if ($this->shortUrlService->delete($urlModel))
            return $this->sendResponse($urlModel, 204);
    }

    private function findModel($id)
    {
        if (!$shortUrlModel = $this->shortUrlService->getById($id)) {
            return $this->sendResponse('', 404, 'Record not found');
        }
        return $shortUrlModel;
    }
}
