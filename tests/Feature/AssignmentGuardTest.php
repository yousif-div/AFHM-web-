<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AssignmentGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_teacher_cannot_be_assigned_again_to_any_supervisor(): void
    {
        Notification::fake();
        $admin = User::factory()->role('admin')->create();
        $current = User::factory()->role('supervisor')->create();
        $other = User::factory()->role('supervisor')->create();
        $teacher = User::factory()->role('teacher')->create(['supervisor_id' => $current->id]);

        foreach ([$current, $other] as $supervisor) {
            $this->actingAs($admin)->from(route('admin.assignments.edit'))
                ->post(route('admin.assignments.update'), [
                    'teacher_id' => $teacher->id,
                    'supervisor_id' => $supervisor->id,
                ])
                ->assertRedirect(route('admin.assignments.edit'))
                ->assertSessionHasErrors(['teacher_id' => 'This teacher is already assigned to a supervisor.'])
                ->assertSessionMissing('success')
                ->assertSessionHasInput('teacher_id', $teacher->id);

            $this->assertEquals($current->id, $teacher->fresh()->supervisor_id);
            $this->withCookie(config('session.cookie'), session()->getId())->get(route('admin.assignments.edit'))->assertOk()
                ->assertSee('This teacher is already assigned to a supervisor.');
        }
        Notification::assertNothingSent();
    }

    public function test_already_assigned_message_is_displayed_in_arabic(): void
    {
        $admin = User::factory()->role('admin')->create();
        $supervisor = User::factory()->role('supervisor')->create();
        $teacher = User::factory()->role('teacher')->create(['supervisor_id' => $supervisor->id]);
        $this->actingAs($admin)->withCookie('afhm_locale', 'ar')
            ->from(route('admin.assignments.edit'))
            ->post(route('admin.assignments.update'), [
                'teacher_id' => $teacher->id,
                'supervisor_id' => $supervisor->id,
            ])->assertSessionHasErrors(['teacher_id' => 'هذا المعلم مسند بالفعل إلى مشرف.']);
        $this->withCookie(config('session.cookie'), session()->getId())->get(route('admin.assignments.edit'))->assertOk()->assertSee('هذا المعلم مسند بالفعل إلى مشرف.');
    }
}
