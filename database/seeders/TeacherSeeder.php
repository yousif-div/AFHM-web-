<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'teacher@afhm.test'],
            [
                'name' => 'Mariam Ali',
                'phone' => '+218910000005',
                'password' => 'password',
                'role' => 'teacher',
                'subject' => 'English Grammar',
                'grade' => 'Grade 7',
                'is_active' => true,
                'supervisor_id' => User::supervisors()->where('email', 'supervisor@afhm.test')->value('id'),
            ],
        );
    }
}
