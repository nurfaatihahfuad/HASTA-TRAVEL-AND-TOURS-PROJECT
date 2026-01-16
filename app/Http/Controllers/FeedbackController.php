<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'bookingID' => 'required|exists:booking,bookingID|unique:feedback,bookingID',
            'rating'    => 'required|integer|min:1|max:5',
            'review'    => 'nullable|string'
        ]);

        Feedback::create([
            'bookingID' => $request->bookingID,
            'rate'    => $request->rating,
            'reviewSentences'    => $request->review
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }
}
