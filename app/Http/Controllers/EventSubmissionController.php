<?php

namespace App\Http\Controllers;

use Statamic\Facades\Entry;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreEventSubmissionRequest;

class EventSubmissionController extends Controller
{
    public function store(StoreEventSubmissionRequest $request): RedirectResponse
    {

        ray($request);

        $entry = Entry::make()
            ->collection('events')
            ->published(false)
            ->data([
                'title' => $request->title,
                'content_area' => $request->content,
            ]);

        $entry->save();

        return redirect()
            ->back()
            ->with('success', "Thank you for your submission.");
    }
}
