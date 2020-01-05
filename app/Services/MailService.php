<?php
namespace App\Services;

use App\Services\Interfaces\MailServiceInterface;
use Illuminate\Support\Facades\Mail;

class MailService implements MailServiceInterface
{
    public function sendToAccounts($accounts = null, $mailForm = null, $data = [], $attach = [])
    {
        if ($accounts && $mailForm) {
            Mail::to($accounts)->send(new $mailForm($accounts, $data, $attach));
        }
    }
}