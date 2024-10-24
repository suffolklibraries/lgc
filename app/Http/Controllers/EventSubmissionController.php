<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Http\RedirectResponse;
use App\Jobs\NotifyEventApproversOfNewEvent;
use App\Http\Requests\StoreEventSubmissionRequest;

class EventSubmissionController extends Controller
{
    public function store(StoreEventSubmissionRequest $request): RedirectResponse
    {

        $start = Carbon::parse($request->start);

        $entry = Entry::make()
            ->collection('events')
            ->published(false)
            ->slug(Str::slug(__(':name :start', [
                'name' => $request->title,
                'start' => $start->format('d-m-Y')
            ])))
            ->data([
                'title' => $request->title,
                'content' => $request->description,
                'start_date' => $start,
                'end_date' => Carbon::parse($request->end),
                'free' => $request->free === 'on' ? true : false,
                'virtual' => $request->virtual === 'on' ? true : false,
                'attendance_information' => $request->attendance_information,
                'accessibility_information' => $request->accessibility_information,
                'additional_access_information' => $request->additional_access_information,
                'latitude' => $request->lat,
                'longitude' => $request->lng,
                'building_name' => $request->building_name ?? null,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'town' => $request->town,
                'postcode' => $request->postcode,
                'directions' => Purifier::clean($request->directions),
                'content_area' => Purifier::clean($request->content),
                'booking_link' => $request->booking_link,
                'cta' => $request->cta,
                'cost_details' => $request->cost_details,
                'submitter_name' => $request->name,
                'submitter_email_address' => $request->email
            ]);

        if($request->file('image')) {
            $assetPath = $this->saveImage($request->file('image'), $request->alternative_text);
            $entry->featured_image = $assetPath;
        }

        if($request->categories) {
            $entry->event_categories = $request->categories;
        }

        if($request->organisers) {
            $entry->organisers = $request->organisers;
        }

        $entry->save();

        NotifyEventApproversOfNewEvent::dispatch($entry);

        return redirect()
            ->back()
            ->with('success', "Thank you for your submission.");
    }

    protected function saveImage($file, $altText = null): string
    {
        $asset = Asset::make()->container('assets')->path(__('user-events/:name', [
            'name' => $file->getClientOriginalName()
        ]));

        $asset->data([
            'alt' => $altText ?? $file->getClientOriginalName()
        ]);

        $asset->upload($file);

        $asset->save();

        return $asset->path;
    }
}
