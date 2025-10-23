<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\Contact\ContactConfirmationMail;
use App\Mail\Contact\ContactMessageMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $contactAddress = config('services.mail.contact');
        $supportAddress = config('services.mail.support');

        if ($contactAddress) {
            rescue(function () use ($data, $contactAddress, $supportAddress) {
                $mailer = Mail::to($contactAddress);

                if ($supportAddress && $supportAddress !== $contactAddress) {
                    $mailer->cc($supportAddress);
                }

                $mailer->send(new ContactMessageMail($data));
            }, report: false);
        }

        rescue(function () use ($data) {
            Mail::to($data['email'])->send(new ContactConfirmationMail($data));
        }, report: false);

        return back()
            ->withInput([])
            ->with('contact.status', 'Gracias por escribirnos. Te responderemos muy pronto.');
    }
}
