<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Reviews\StoreReviewRequest;
use App\Http\Requests\Client\Reviews\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        $data['user_id'] = $user->id();

        Review::create($data);

        return redirect()->back();
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $data = $request->validated();

        $review->update($data);

        return redirect()->back();
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back();
    }
}
