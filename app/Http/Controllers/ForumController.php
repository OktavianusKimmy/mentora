<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Services\StudyStreakService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ForumController extends Controller
{
    public function index()
    {
        $threads = ForumThread::with(['user', 'images'])
            ->withCount(['replies', 'likes'])
            ->latest()
            ->get();

        return view('forum.index', compact('threads'));
    }

    public function store(Request $request)
    {
        $allowedCategories = Auth::user()->isAdmin()
            ? array_keys(ForumThread::CATEGORIES)
            : array_keys(ForumThread::CREATABLE_CATEGORIES);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in($allowedCategories)],
            'body' => ['required', 'string', 'max:5000'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $thread = ForumThread::create([
            'user_id' => Auth::id(),
            'category' => $validated['category'],
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $thread->images()->create([
                    'path' => $imageFile->store('forum', 'public'),
                ]);
            }
        }
        StudyStreakService::record(auth()->id());

        return redirect()->route('forum')->with('success', __('Diskusi kamu berhasil diposting!'));
    }

    public function show($id)
    {
        $thread = ForumThread::with(['user', 'likes', 'images', 'replies.user', 'replies.likes'])
            ->findOrFail($id);

        return view('forum.show', compact('thread'));
    }

    public function storeReply(Request $request, $id)
    {
        $thread = ForumThread::findOrFail($id);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        ForumReply::create([
            'forum_thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        StudyStreakService::record(auth()->id());

        return redirect()->route('forum.show', $thread->id)->with('success', __('Balasan kamu berhasil dikirim!'));
    }

    /**
     * Toggle like/unlike buat thread ATAU balasan (satu endpoint, dibedain lewat 'type').
     * Dipanggil via AJAX dari forum/show.blade.php.
     */
    public function toggleLike(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['thread', 'reply'])],
            'id' => ['required', 'integer'],
        ]);

        $model = $validated['type'] === 'thread'
            ? ForumThread::findOrFail($validated['id'])
            : ForumReply::findOrFail($validated['id']);

        $userId = Auth::id();

        $existingLike = $model->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $model->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $model->likes()->count(),
        ]);
    }

    /**
     * Hapus thread. Boleh sama pemilik thread ATAU admin.
     */
    public function destroyThread($id)
    {
        $thread = ForumThread::findOrFail($id);

        abort_unless(
            Auth::id() === $thread->user_id || Auth::user()->isAdmin(),
            403,
            'Kamu tidak punya izin untuk menghapus diskusi ini.'
        );

        $thread->delete();

        return redirect()->route('forum')->with('success', __('Diskusi berhasil dihapus.'));
    }

    /**
     * Hapus reply. Boleh sama pemilik reply ATAU admin.
     */
    public function destroyReply($id)
    {
        $reply = ForumReply::findOrFail($id);

        abort_unless(
            Auth::id() === $reply->user_id || Auth::user()->isAdmin(),
            403,
            'Kamu tidak punya izin untuk menghapus balasan ini.'
        );

        $threadId = $reply->forum_thread_id;

        $reply->delete();

        return redirect()->route('forum.show', $threadId)->with('success', __('Balasan berhasil dihapus.'));
    }

    /**
     * Report thread ATAU reply (satu endpoint, dibedain lewat 'type', sama pola kayak toggleLike).
     */
    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['thread', 'reply'])],
            'id' => ['required', 'integer'],
            'reason' => ['required', Rule::in(array_keys(ForumReport::REASONS))],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $model = $validated['type'] === 'thread'
            ? ForumThread::findOrFail($validated['id'])
            : ForumReply::findOrFail($validated['id']);

        // Gak boleh report konten sendiri
        abort_if($model->user_id === Auth::id(), 422, 'Kamu tidak bisa melaporkan konten milikmu sendiri.');

        if ($model->isReportedBy(Auth::id())) {
            return back()->with('error', __('Kamu sudah pernah melaporkan konten ini sebelumnya.'));
        }

        $model->reports()->create([
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', __('Laporan kamu sudah dikirim, terima kasih sudah bantu jaga komunitas Mentora. 🙏'));
    }
}
