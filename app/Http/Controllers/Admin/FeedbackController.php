<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    /**
     * Display a listing of feedback.
     */
    public function index(Request $request)
    {
        $query = Feedback::with('user');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('feedback', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
        }
        
        // Filter by rating
        if ($request->has('rating') && !empty($request->rating)) {
            $query->where('rating', $request->rating);
        }
        
        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $feedbacks = $query->paginate(15)->withQueryString();
        
        // Get statistics
        $totalFeedbacks = Feedback::count();
        $averageRating = Feedback::avg('rating') ?? 0;
        $ratingDistribution = [
            5 => Feedback::where('rating', 5)->count(),
            4 => Feedback::where('rating', 4)->count(),
            3 => Feedback::where('rating', 3)->count(),
            2 => Feedback::where('rating', 2)->count(),
            1 => Feedback::where('rating', 1)->count(),
        ];
        
        $recentFeedbacks = Feedback::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('admin.feedback.index', compact('feedbacks', 'totalFeedbacks', 'averageRating', 'ratingDistribution', 'recentFeedbacks'));
    }

    /**
     * Display the specified feedback.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('user');
        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Remove the specified feedback from storage.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback deleted successfully!');
    }

    /**
     * Bulk delete feedback.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No feedback items were selected.');
        }
        
        // Delete records using the singular route name to match your routes file configuration
        Feedback::whereIn('id', $ids)->delete();
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Selected feedback items deleted successfully!');
    }
}