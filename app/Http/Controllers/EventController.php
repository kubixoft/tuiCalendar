<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return view('events.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Event::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $event = Event::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403, 'Bu etkinliği güncelleme yetkiniz yok.');
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json(['status' => 'updated']);
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403, 'Bu etkinliği silme yetkiniz yok.');
        }

        $event->delete();

        return response()->json(['status' => 'deleted']);
    }

    public function list()
    {
        $events = Event::where('user_id', Auth::id())->get();

        // TUI Calendar formatında dön
        $mapped = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'calendarId' => '1',
                'title' => $event->title,
                'category' => 'time',
                'start' => $event->start,
                'end' => $event->end,
                'raw' => [ // ✅ TUI'nin anlayacağı format bu
                    'description' => $event->description
                ],
            ];
        });

        return response()->json($mapped);
    }

    public function adminCalendar(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.user-calendar', [
            'user' => $user
        ]);
    }

    public function listForUser($userId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $events = Event::where('user_id', $userId)->get();

        return response()->json($events->map(function ($event) {
            return [
                'id' => $event->id,
                'calendarId' => '1',
                'title' => $event->title,
                'category' => 'time',
                'start' => $event->start,
                'end' => $event->end,
                'raw' => [
                    'description' => $event->description
                ],
            ];
        }));
    }

    public function exportPdf(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $events = Event::where('user_id', $user->id)
            ->whereBetween('start', [$startOfMonth, $endOfMonth])
            ->orderBy('start')
            ->get();

        $pdf = Pdf::loadView('admin.calendar-pdf', [
            'user' => $user,
            'events' => $events,
            'month' => $now->format('F Y')
        ]);

        return $pdf->download("takvim_{$user->name}_{$now->format('Y_m')}.pdf");
    }
}
