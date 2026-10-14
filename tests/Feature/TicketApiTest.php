<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_can_be_created_listed_viewed_updated_and_deleted(): void
    {
        $payload = [
            'requester_name' => 'Sam Rivera',
            'requester_email' => 'sam@example.com',
            'category' => 'Network',
            'priority' => 'High',
            'subject' => 'Wi-Fi is unavailable',
            'description' => 'The office network is not reachable.',
        ];

        $created = $this->postJson('/api/v1/tickets', $payload)
            ->assertCreated()
            ->assertJsonPath('ticket.requester_name', 'Sam Rivera');
        $ticketId = $created->json('ticket.id');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticketId,
            'ticket_number' => 'TKT-' . str_pad((string) $ticketId, 6, '0', STR_PAD_LEFT),
        ]);
        $this->getJson('/api/v1/tickets')->assertOk()->assertJsonPath('tickets.0.id', $ticketId);
        $this->getJson("/api/v1/tickets/{$ticketId}")->assertOk()->assertJsonPath('ticket.subject', $payload['subject']);
        $this->putJson("/api/v1/tickets/{$ticketId}", ['status' => 'Resolved'])
            ->assertOk()->assertJsonPath('ticket.status', 'Resolved');
        $this->deleteJson("/api/v1/tickets/{$ticketId}")->assertOk();
        $this->assertDatabaseMissing('tickets', ['id' => $ticketId]);
    }

    public function test_ticket_creation_validates_required_fields(): void
    {
        $this->postJson('/api/v1/tickets', [])->assertUnprocessable()->assertJsonValidationErrors([
            'requester_name', 'requester_email', 'category', 'priority', 'subject', 'description',
        ]);
    }
}
