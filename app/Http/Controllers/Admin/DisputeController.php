<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisputeController extends Controller
{
    public function index(): View
    {
        $disputes = Dispute::with(['order', 'user'])->latest()->paginate(20);
        return view('pages.admin.disputes', compact('disputes'));
    }

    public function resolve(Dispute $dispute, Request $request)
    {
        $request->validate([
            'resolution' => 'required|string'
        ]);

        $dispute->update([
            'status' => 'resolved',
            'resolution' => $request->resolution
        ]);

        return back()->with('success', 'Dispute resolved.');
    }
}
