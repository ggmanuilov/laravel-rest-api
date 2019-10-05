<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShortUrlRequest;
use App\Services\ShortUrlService;
use App\ShortUrls;
use Illuminate\Http\Request;

/**
 * @group ShortLinks management
 *
 * APIs for managing shortLinks
 */
class ShortUrlsController extends Controller
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
            return response()->json([], 404);
        }
        $this->shortUrlService->addHit($shortUrlModel);

        return redirect($shortUrlModel->long_url);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(ShortUrls::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ShortUrlRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShortUrlRequest $request)
    {
        $longUrl = $request->get('long_url');

        $shortUrlModel = $this->shortUrlService->getByLongUrl($longUrl);
        if (!$shortUrlModel) {
            $shortUrlModel = $this->shortUrlService->make($longUrl);
        }

        return response()->json($shortUrlModel, 201);
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

        return response()->json($shortUrlModel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ShortUrlRequest $request
     * @param \App\ShortUrls $ShortUrls
     * @return \Illuminate\Http\Response
     */
    public function update(ShortUrlRequest $request)
    {
        $shortUrlModel = $this->shortUrlService->getById($request->get('id'));
        $shortUrlModel->fill($request->except(['id']))
            ->save();

        return response()->json($shortUrlModel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ShortUrlRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @response 204 {}
     * @response 404 {
     *  "message": "No query results for model [\App\ShortUrls]"
     *  }
     */
    public function destroy($id)
    {
        $this->shortUrlService->validate(ShortUrlService::METHOD_DELETE, ['id' => $id]);

        $shortUrlModel = $this->findModel($id);
        if ($this->shortUrlService->delete($shortUrlModel)) {
            return response()->json($shortUrlModel, 204);
        }
    }

    private function findModel($id)
    {
        if (!$shortUrlModel = $this->shortUrlService->getById($id)) {
            return response()->json('', 404);
        }

        return $shortUrlModel;
    }
}
