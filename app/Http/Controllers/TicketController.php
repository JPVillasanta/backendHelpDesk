<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $tickets = Ticket::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($filter) use ($search) {
                    $filter->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('requester_name', 'like', "%{$search}%")
                        ->orWhere('requester_email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return response()->json(['tickets' => $tickets]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'requester_name' => ['required', 'string', 'max:120'],
            'requester_email' => ['required', 'email', 'max:180'],
            'category' => ['required', 'in:Hardware,Software,Network,Access,Other'],
            'priority' => ['required', 'in:Low,Medium,High,Critical'],
            'subject' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:5000'],
        ]);

        $ticket = Ticket::create($data + ['ticket_number' => 'TEMP-' . uniqid()]);
        $ticket->update(['ticket_number' => 'TKT-' . str_pad((string) $ticket->id, 6, '0', STR_PAD_LEFT)]);

        return response()->json(['ticket' => $ticket->fresh()], 201);
    }

    public function show(Ticket $ticket)
    {
        return response()->json(['ticket' => $ticket]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'requester_name' => ['sometimes', 'required', 'string', 'max:120'],
            'requester_email' => ['sometimes', 'required', 'email', 'max:180'],
            'category' => ['sometimes', 'required', 'in:Hardware,Software,Network,Access,Other'],
            'priority' => ['sometimes', 'required', 'in:Low,Medium,High,Critical'],
            'subject' => ['sometimes', 'required', 'string', 'max:180'],
            'description' => ['sometimes', 'required', 'string', 'max:5000'],
            'status' => ['sometimes', 'required', 'in:Open,In Progress,Resolved,Closed'],
        ]);

        $ticket->update($data);

        return response()->json(['ticket' => $ticket->fresh()]);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted.']);
    }
}
