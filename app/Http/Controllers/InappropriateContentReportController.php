<?php

namespace App\Http\Controllers;

use App\Models\ContentReport;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreContentReportRequest;

class InappropriateContentReportController extends Controller
{
    public function store(string $entryId, StoreContentReportRequest $request): RedirectResponse
    {
        ContentReport::create([
            'entry_id' => $entryId,
            'reason' => $request->reason,
            'comments' => $request->comments,
            'email' => $request->email
        ]);

        return redirect()->back()->with([
            'openModal' => true,
            'message' => 'Thank you for alerting us to this inappropriate content. Your help is crucial in upholding our community standards.'
        ]);
    }
}
