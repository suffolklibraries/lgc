<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Events\IndexRequest;
use Illuminate\Http\Request;
use Statamic\Facades\Entry;

class EventController extends Controller
{
    public function index(IndexRequest $request)
    {
        $data = $request->validated();

        $events = Entry::query()
            ->where('collection', 'events')
            ->when($data['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', 'published');
            })
            ->when($data['search'] ?? null, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            })
            ->when($data['category'] ?? null, function ($query, $category) {
                $query->whereTaxonomyIn(["event_categories::{$category}"]);
            })
            ->when($data['type'] ?? null, function ($query, $type) {
                if ($type === 'virtual') {
                    $query->where('virtual', true);
                } elseif ($type === 'free') {
                    $query->where('free', true);
                }
            })
            ->when($data['start_date'] ?? null, function ($query, $startDate) {
                $query->whereDate('start_date', $startDate);
            })
            ->orderBy('start_date', 'desc');

        return $events->paginate(10);
    }
}
