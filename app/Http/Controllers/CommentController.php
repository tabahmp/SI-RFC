<?php

namespace App\Http\Controllers;

use App\Models\PermohonanRfc;
use App\Models\RfcComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Get all comments for an RFC
     */
    public function index(Request $request, $rfcId)
    {
        $rfc = PermohonanRfc::findOrFail($rfcId);
        $user = $request->user();
        
        // Build query based on user role
        $query = RfcComment::where('rfc_id', $rfcId)
            ->rootComments()
            ->with(['user', 'replies.user']);
        
        // Only staff can see internal notes
        $isStaff = in_array($user->peran?->nama_peran ?? '', ['verifikator', 'pimpinan', 'teknisi', 'admin']);
        
        if (!$isStaff) {
            $query->public();
        }
        
        $comments = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($comments);
    }
    
    /**
     * Store a new comment
     */
    public function store(Request $request, $rfcId)
    {
        $rfc = PermohonanRfc::findOrFail($rfcId);
        
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'is_internal' => ['boolean'],
            'parent_id' => ['nullable', 'exists:rfc_comments,id'],
        ]);
        
        $comment = RfcComment::create([
            'rfc_id' => $rfcId,
            'user_id' => $request->user()->id,
            'comment' => $validated['comment'],
            'is_internal' => $validated['is_internal'] ?? false,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);
        
        $comment->load('user');
        
        return response()->json([
            'message' => 'Komentar berhasil ditambahkan',
            'comment' => $comment
        ], 201);
    }
    
    /**
     * Update a comment
     */
    public function update(Request $request, $rfcId, $commentId)
    {
        $comment = RfcComment::where('rfc_id', $rfcId)
            ->where('id', $commentId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);
        
        $comment->update($validated);
        
        return response()->json([
            'message' => 'Komentar berhasil diperbarui',
            'comment' => $comment
        ]);
    }
    
    /**
     * Delete a comment
     */
    public function destroy(Request $request, $rfcId, $commentId)
    {
        $comment = RfcComment::where('rfc_id', $rfcId)
            ->where('id', $commentId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
        
        $comment->delete();
        
        return response()->json(['message' => 'Komentar berhasil dihapus']);
    }
}
