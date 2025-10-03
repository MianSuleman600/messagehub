<?php

namespace App\Domain\Contacts\Controllers;

use App\Domain\Contacts\Models\Contact;
use Illuminate\Http\Request;

class ContactController
{
    /**
     * Display a paginated list of contacts, with optional search
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $q = $request->input('q', '');

        $contacts = Contact::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('handle', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('contacts.index', compact('contacts', 'q'));
    }

    /**
     * Display a single contact with their conversations
     *
     * @param Contact $contact
     * @return \Illuminate\View\View
     */
    public function show(Contact $contact)
    {
        $contact->load([
            'conversations' => fn($query) => $query->with([
                'assignee:id,name',
                'messages' => fn($mq) => $mq->latestFirst()->limit(1),
            ]),
        ]);

        return view('contacts.show', compact('contact'));
    }
}
