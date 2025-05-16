<?php

namespace Tests\Feature\Admin;

use App\Models\NewAppointment;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_user_can_update_appointment_status_to_pending()
    {
        $admin = User::factory()->admin()->create();
        $appointment = NewAppointment::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.appointments.update-status', $appointment), [
                'status' => 'pending'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('pending', $appointment->fresh()->status);
    }

    /** @test */
    public function admin_user_can_update_appointment_status_to_active()
    {
        $admin = User::factory()->admin()->create();
        $appointment = NewAppointment::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.appointments.update-status', $appointment), [
                'status' => 'active'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('active', $appointment->fresh()->status);
    }

    /** @test */
    public function admin_user_can_update_appointment_status_to_completed()
    {
        $admin = User::factory()->admin()->create();
        $appointment = NewAppointment::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.appointments.update-status', $appointment), [
                'status' => 'completed'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('completed', $appointment->fresh()->status);
    }

    /** @test */
    public function admin_user_can_update_appointment_status_to_cancelled()
    {
        $admin = User::factory()->admin()->create();
        $appointment = NewAppointment::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.appointments.update-status', $appointment), [
                'status' => 'cancelled'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('cancelled', $appointment->fresh()->status);
    }

    /** @test */
    public function non_admin_user_cannot_update_appointment_status()
    {
        $user = User::factory()->create(['role' => 'user']);
        $staff = Staff::factory()->admin()->create(); // проверяем также staff
        $appointment = NewAppointment::factory()->create();

        $response = $this->actingAs($user)
            ->patch(route('admin.appointments.update-status', $appointment), [
                'status' => 'completed'
            ]);

        $response->assertForbidden();

        $response = $this->actingAs($staff)
            ->patch(route('admin.appointments.update-status', $appointment), [
                'status' => 'completed'
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function invalid_status_is_rejected()
    {
        $admin = User::factory()->admin()->create();
        $appointment = NewAppointment::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('admin.appointments.update-status', $appointment), [
                'status' => 'invalid-status'
            ]);

        $response->assertSessionHasErrors('status');
        $this->assertNotEquals('invalid-status', $appointment->fresh()->status);
    }
}