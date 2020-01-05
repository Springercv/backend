<?php
namespace App\Http\Controllers\Api;

use App\Http\Resources\TourCollection;
use App\Http\Resources\SearchCollection;
use App\Models\MasterSearchAttribute;
use App\Models\Tour;
use App\Services\Interfaces\SearchServiceInterface;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    public function __construct(SearchServiceInterface $searchService)
    {
        $this->searchService = $searchService;
    }

    public function listKeys()
    {
        return (new SearchCollection(MasterSearchAttribute::pluck('key')))->setMessage('Get keys success!')->setStatus(200);
    }

    public function getSearchToursResult(Request $request)
    {
        if ($request->has('keys')) {
            return (new TourCollection($this->searchService->search($request->keys, Tour::class)))->setMessage('Get tours success')->setStatus(200);
        }
        return (new TourCollection(collect([])))->setMessage('Get tours success!')->setStatus(200);
    }
}