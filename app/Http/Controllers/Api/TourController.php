<?php
namespace App\Http\Controllers\Api;

use App\Mail\BookedTourInforMail;
use App\Repositories\Interfaces\TourRepositoryInterface;
use App\Services\Interfaces\MailServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TourCollection;

class TourController extends ApiController
{
    public function __construct(MailServiceInterface $mailService, TourRepositoryInterface $tourRepository)
    {
        $this->mailService = $mailService;
        $this->tourRepository = $tourRepository;
    }

    public function booking(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_tour' => 'required',
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            $errors = '';
            foreach ( $validator->messages()->toArray() as $error) {
                $errors .= implode(',', $error) . ' ';
            }
            return (new TourCollection(collect([])))->setMessage($errors)->setStatus(422);
        }
        $tour = $this->tourRepository->findByAttrFirst('id', $request->id_tour);
        if ($tour) {
            $this->mailService->sendToAccounts($request->email, BookedTourInforMail::class, $tour);
            return (new TourCollection(collect([])))->setMessage('Booking done!')->setStatus(200);
        }
        return (new TourCollection(collect([])))->setMessage('Tour not found')->setStatus(404);
    }
}