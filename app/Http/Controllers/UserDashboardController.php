<?php

namespace App\Http\Controllers;

use Statamic\View\View;
use Statamic\Facades\Term;
use Statamic\Facades\User;
use Illuminate\Support\Str;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Statamic\Facades\Collection;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Statamic\Modifiers\CoreModifiers;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\DeleteEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Jobs\NotifyEventApproversOfNewEvent;
use App\Http\Requests\SaveEventAsDraftRequest;
use App\Http\Requests\UpdateUserDetailsRequest;
use App\Http\Requests\UpdateUserOrganisationRequest;

class UserDashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect(route('user.dashboard.my-details.index'));
    }

    public function details(): View
    {
        return (new View)
            ->layout('layout')
            ->template('users.dashboard.my-details')
            ->with([
                'title' => "Dashboard",
                'user' => Auth::user()
            ]);
    }

    public function updateDetails(UpdateUserDetailsRequest $request): RedirectResponse
    {

        $user = User::current();

        $user->data([
            'name' => $request->name,
        ]);

        $user->email($request->email);

        if($request->password && $request->new_password) {

            if(Hash::check($request->password, $user->password())) {
                $user->password(Hash::make($request->new_password));
            } else {
                return redirect()->back()->with([
                    'password_incorrect' => "Password Incorrect"
                ]);
            }
        }

        $user->save();

        return redirect()->back()->with([
            'success' => 'Details updated successfully'
        ]);
    }

    public function organisation(): View
    {
        return (new View)
            ->layout('layout')
            ->template('users.dashboard.my-organisation')
            ->with([
                'title' => "Dashboard",
                'user' => Auth::user(),
                'organisation' => Auth::user()->linked_organisations
            ]);
    }

    public function updateOrganisationDetails(UpdateUserOrganisationRequest $request): RedirectResponse
    {
        $org = Term::find($request->org);

        $org->data([
            'title' => $request->organisation_name,
            'email_address' =>  $request->organisation_email,
            'telephone_number' =>  $request->organisation_tel,
            'website' => $request->organisation_website,
        ]);

        $org->save();

        return redirect()->back()->with([
            'success' => 'Organisation details updated successfully'
        ]);
    }

    public function events(): View
    {
        $user = User::current();

        $page = request()->input('page', 1);

        $events = Entry::query()
            ->where('collection', 'events')
            ->where('created_by', $user->id)
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(10, 'page', $page);


        return (new View)
            ->layout('layout')
            ->template('users.dashboard.my-events')
            ->with([
                'title' => "Dashboard",
                'user' => $user,
                'events' => $events
            ]);
    }

    public function editEvent(string $entryId): View
    {
        $entry = $this->getEntry($entryId);

        // Is the user allowed to edit this event?
        if(!$entry->created_by || $entry->created_by?->id !== User::current()?->id) {
            return (new View)
                ->layout('layout')
                ->template('errors.404');
        }

        $orgAddresses = Auth::user()->linked_organisations?->get('addresses');

        $categories = $entry->get('event_categories');
        $eventOrganisers = $entry->get('organisers');

        if(is_array($entry->content_area)) {
            $coreModifiers = new CoreModifiers();
            $eventContent = $coreModifiers->bardHtml($entry->content_area);

        } else {
            $eventContent = $entry->content_area;
        }

        if(is_array($entry->directions)) {
            $coreModifiers = new CoreModifiers();
            $directionsContent = $coreModifiers->bardHtml($entry->directions);
        } else {
            $directionsContent = $entry->directions;
        }

        $accessibilityInfoOptions = Collection::find('events')
            ->entryBlueprint()
            ->field('accessibility_information')
            ->get('options');


        return (new View)
            ->layout('layout')
            ->template('users.dashboard.my-events-edit')
            ->with([
                'title' => "Dashboard",
                'entry' => $entry,
                'categories' => $categories,
                'event_organisers' => $eventOrganisers,
                'event_content' => $eventContent,
                'org_addresses' => $orgAddresses,
                'directions_content' => $directionsContent,
                'accessibility_info_options' => $accessibilityInfoOptions
            ]);
    }

    public function updateEvent(string $entryId, UpdateEventRequest $request): RedirectResponse
    {
        $entry = $this->getEntry($entryId);
        $user = User::current();

        $start = Carbon::parse($request->start);

        $entry->slug(Str::slug(__(':name :start', [
            'name' => $request->title,
            'start' => $start->format('d-m-Y')
        ])));

        $data = $this->mapEventData($request, $user);


        if($request->file('image')) {
            $assetPath = $this->saveImage($request->file('image'), $request->alternative_text);
            $data['featured_image'] = $assetPath;
        } else {
            $data['featured_image'] = $entry->featured_image?->path;

            if($request->alternative_text) {
                $asset = $entry->featured_image;

                if($asset) {
                    $asset->set('alt', $request->alternative_text);
                    $asset->save();
                }
            }
        }


        $entry->data($data);

        if($request->categories) {
            $entry->event_categories = $request->categories;
        }

        if($request->organisers) {
            $entry->organisers = $request->organisers;
        }

        if($request->publish) {
            $entry->published(true);
        }

        $entry->save();

        return redirect()->back()->with('success', 'Event details updated successfully');
    }

    public function deleteEvent(string $entryId, DeleteEventRequest $request): RedirectResponse
    {

        $entry = $this->getEntry($entryId);

        if(!$entry) {
            abort(404);
        }

        if($entry->featured_image) {
            Asset::delete($entry->featured_image);
        }

        Entry::delete($entry);

        return redirect(route('user.dashboard.my-events.index'))->with(
            'success',
            __("Event ':event' deleted successfully.", [
                'event' => $entry->title
            ])
        );
    }

    public function createEvent(): View
    {

        if(Auth::user()->linked_organisations) {
            $defaultOrg = [Auth::user()->linked_organisations?->slug];
        } else {
            $defaultOrg = null;
        }

        $orgAddresses = Auth::user()->linked_organisations?->get('addresses');

        $accessibilityInfoOptions = Collection::find('events')
            ->entryBlueprint()
            ->field('accessibility_information')
            ->get('options');

        return (new View)
            ->layout('layout')
            ->template('users.dashboard.my-events-create')
            ->with([
                'title' => "Dashboard",
                'default_org' => $defaultOrg,
                'org_addresses' => $orgAddresses,
                'accessibility_info_options' => $accessibilityInfoOptions,
            ]);
    }

    public function storeEvent(StoreEventRequest $request): RedirectResponse
    {
        $user = User::current();

        $start = Carbon::parse($request->start);

        $entry = Entry::make()
            ->collection('events')
            ->published(false)
            ->slug(Str::slug(__(':name :start', [
                'name' => $request->title,
                'start' => $start->format('d-m-Y')
            ])))
            ->data(
                $this->mapEventData($request, $user)
            );

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

        return redirect(route('user.dashboard.my-events.index'))->with(
            'success',
            __("Event ':event' created successfully.", [
                'event' => $entry->title
            ])
        );
    }

    private function getEntry(string $id)
    {
        $entry = Entry::find($id);

        if(!$entry) {
            abort(404);
        }

        return $entry;
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

    protected function mapEventData($request, $user) {
        return [
            'title' => $request->title,
            'content' => $request->description,
            'start_date' => Carbon::parse($request->start),
            'end_date' => Carbon::parse($request->end),
            'free' => $request->free === 'on' ? true : false,
            'virtual' => $request->virtual === 'on' ? true : false,
            'attendance_information' => $request->attendance_information,
            'accessibility_information' => $request->accessibility_information,
            'additional_access_information' => $request->additional_access_information,
            'latitude' => $request->lat,
            'longitude' => $request->lng,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'building_name' => $request->building_name ?? null,
            'town' => $request->town,
            'postcode' => $request->postcode,
            'directions' => Purifier::clean($request->directions),
            'content_area' => Purifier::clean($request->content),
            'booking_link' => $request->booking_link,
            'cta' => $request->cta,
            'cost_details' => $request->cost_details,
            'created_by' => $user ? $user->id : null
        ];
    }
}
