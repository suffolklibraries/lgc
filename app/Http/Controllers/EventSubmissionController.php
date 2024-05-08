<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
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
                'latitude' => $request->lat,
                'longitude' => $request->lng,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'town' => $request->town,
                'postcode' => $request->postcode,
                'content_area' => $request->content,
                'booking_link' => $request->booking_link,
                'cta' => $request->cta,
            ]);

        if($request->file('image')) {

            $file = $request->file('image');

            $asset = Asset::make()->container('assets')->path(__('user-events/:name', [
                'name' => $file->getClientOriginalName()
            ]));

            $asset->data([
                'alt' => $request->title
            ]);

            $asset->upload($file);

            $asset->save();

            $entry->featured_image = $asset->path;
        }

        if($request->categories) {
            $entry->event_categories = $request->categories;
        }

        if($request->organisers) {
            $entry->organisers = $request->organisers;
        }

        $entry->save();

        return redirect()
            ->back()
            ->with('success', "Thank you for your submission.");
    }
}
