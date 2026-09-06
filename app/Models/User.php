<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'password', 'role', 'subject', 'grade', 'supervisor_id', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public const ROLES = ['admin', 'school_manager', 'teacher', 'supervisor'];
    public function supervisor() { return $this->belongsTo(self::class, 'supervisor_id'); }
    public function supervisedTeachers() { return $this->hasMany(self::class, 'supervisor_id'); }
    public function timetables() { return $this->hasMany(Timetable::class, 'teacher_id'); }
    public function teachingReports() { return $this->hasMany(TeachingReport::class, 'teacher_id'); }
    public function uploadedMaterials() { return $this->hasMany(TeachingMaterial::class, 'uploaded_by'); }
    public function feedbackGiven() { return $this->hasMany(Feedback::class, 'supervisor_id'); }
    public function feedbackReceived() { return $this->hasMany(Feedback::class, 'teacher_id'); }
    public function evaluationsGiven() { return $this->hasMany(Evaluation::class, 'supervisor_id'); }
    public function evaluationsReceived() { return $this->hasMany(Evaluation::class, 'teacher_id'); }
    public function generatedPerformanceReports() { return $this->hasMany(PerformanceReport::class, 'generated_by'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeTeachers($q) { return $q->where('role', 'teacher'); }
    public function scopeSupervisors($q) { return $q->where('role', 'supervisor'); }
    public function scopeAdmins($q) { return $q->where('role', 'admin'); }
    public function scopeSchoolManagers($q) { return $q->where('role', 'school_manager'); }
    public function hasRole(string ...$roles): bool { return in_array($this->role, $roles, true); }
}
