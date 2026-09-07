<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\TeacherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_seeder_runs_alone_and_preserves_existing_account(): void
    {
        $this->seed(TeacherSeeder::class);
        $teacher = User::where('email', 'teacher@afhm.test')->firstOrFail();
        $this->assertSame('teacher', $teacher->role);
        $this->assertTrue($teacher->is_active);
        $this->assertTrue(Hash::check('password', $teacher->password));
        $this->post('/login', ['email' => $teacher->email, 'password' => 'password'])
            ->assertRedirect(route('teacher.dashboard'));
        $this->get(route('teacher.dashboard'))->assertOk()->assertSee('dashboard-shell role-teacher', false);

        $teacher->update(['name' => 'Updated Teacher', 'password' => 'changed-password']);
        $this->seed(TeacherSeeder::class);
        $this->assertSame(1, User::where('email', $teacher->email)->count());
        $this->assertSame('Updated Teacher', $teacher->fresh()->name);
        $this->assertTrue(Hash::check('changed-password', $teacher->fresh()->password));
    }

    public function test_main_seeder_includes_teacher_and_related_demo_records(): void
    {
        $this->seed(DatabaseSeeder::class);
        $teacher = User::where('email', 'teacher@afhm.test')->firstOrFail();
        $this->assertSame(4, User::teachers()->count());
        $this->assertSame('supervisor@afhm.test', $teacher->supervisor->email);
        $this->assertSame(1, $teacher->timetables()->count());
        $this->assertSame(2, $teacher->teachingReports()->count());
    }
}
