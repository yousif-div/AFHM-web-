<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_switch_saves_language_and_returns_to_the_same_page(): void
    {
        foreach (['ar', 'en'] as $locale) {
            $this->from('/reports?report_type=weekly')
                ->post('/language', ['locale' => $locale])
                ->assertStatus(303)
                ->assertRedirect('/reports?report_type=weekly')
                ->assertCookie('afhm_locale', $locale);
        }
        $this->post('/language', ['locale' => 'fr'])->assertSessionHasErrors('locale');
    }

    public function test_login_renders_saved_language_and_direction(): void
    {
        $this->get('/login')->assertOk()->assertSee('lang="en" dir="ltr"', false)->assertSee('Welcome to AFHM');
        $this->withCookie('afhm_locale', 'ar')->get('/login')
            ->assertOk()->assertSee('lang="ar" dir="rtl"', false)->assertSee('مرحباً بك في AFHM')->assertSee('English');
        $this->withCookie('afhm_locale', 'en')->get('/login')
            ->assertOk()->assertSee('lang="en" dir="ltr"', false)->assertSee('Welcome to AFHM');
        $this->withCookie('afhm_locale', 'invalid')->get('/login')->assertSee('lang="en" dir="ltr"', false);
    }

    public function test_arabic_pages_keep_form_values_and_user_content_intact(): void
    {
        $admin = User::factory()->role('admin')->create(['name' => 'Example Admin']);
        $this->actingAs($admin)->withCookie('afhm_locale', 'ar');
        foreach (['admin.dashboard', 'admin.users.index', 'admin.users.create', 'admin.timetables.index', 'admin.timetables.create', 'admin.assignments.edit', 'materials.index', 'admin.materials.create', 'reports.index', 'performance.index', 'performance.create', 'profile.edit', 'notifications.index'] as $route) {
            $this->get(route($route))->assertOk()->assertSee('lang="ar" dir="rtl"', false)->assertSee('لوحة التحكم')->assertSee('Example Admin');
        }
        $this->get(route('admin.timetables.create'))->assertSee('value="Mon"', false)->assertSee('الاثنين');
        $this->get(route('reports.index'))->assertSee('value="weekly"', false)->assertSee('أسبوعي');
        $this->get(route('admin.users.create'))->assertSee('value="teacher"', false)->assertSee('المعلم');
        foreach (['teacher', 'supervisor', 'school_manager'] as $role) {
            $this->actingAs(User::factory()->role($role)->create())->get(route($role.'.dashboard'))
                ->assertOk()->assertSee('لوحة التحكم')->assertSee('مرحباً بعودتك');
        }
    }
}
