<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Display user's feedback list with create form.
     */
    public function index(): View
    {
        $feedbacks = Feedback::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('user.feedbacks.index', compact('feedbacks'));
    }
    
    /**
     * Store a new feedback.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|min:10|max:1000',
        ]);
        
        Feedback::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);
        
        return redirect()->route('user.feedbacks.index')
            ->with('success', 'Thank you for your feedback!');
    }
    
    /**
     * Delete feedback.
     */
    public function destroy(int $id): RedirectResponse
    {
        $feedback = Feedback::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $feedback->delete();
        
        return redirect()->route('user.feedbacks.index')
            ->with('success', 'Feedback deleted successfully!');
    }
}
