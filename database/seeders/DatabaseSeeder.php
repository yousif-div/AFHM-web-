<?php

namespace Database\Seeders;

use App\Models\{Evaluation,Feedback,PerformanceReport,TeachingMaterial,TeachingReport,Timetable,User};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin=User::factory()->role('admin')->create(['name'=>'AFHM Administrator','email'=>'admin@afhm.test','phone'=>'+218910000001','subject'=>null,'grade'=>null]);
        User::factory()->role('school_manager')->create(['name'=>'School Manager','email'=>'manager@afhm.test','phone'=>'+218910000002','subject'=>null,'grade'=>null]);
        $s1=User::factory()->role('supervisor')->create(['name'=>'Sarah Ahmed','email'=>'supervisor@afhm.test','phone'=>'+218910000003','subject'=>'English Language','grade'=>null]);
        $s2=User::factory()->role('supervisor')->create(['name'=>'Omar Salem','email'=>'supervisor2@afhm.test','phone'=>'+218910000004','subject'=>'Literature','grade'=>null]);
        $this->call(TeacherSeeder::class);
        $teachers=collect([
          ['name'=>'Yousef Hassan','email'=>'yousef@afhm.test','phone'=>'+218910000006','subject'=>'Conversation','grade'=>'Grade 8','supervisor_id'=>$s1->id],
          ['name'=>'Lina Faraj','email'=>'lina@afhm.test','phone'=>'+218910000007','subject'=>'Literature','grade'=>'Grade 9','supervisor_id'=>$s2->id],
          ['name'=>'Adam Nouri','email'=>'adam@afhm.test','phone'=>'+218910000008','subject'=>'Writing','grade'=>'Grade 7','supervisor_id'=>$s2->id],
        ])->map(fn($x)=>User::factory()->role('teacher')->create($x))
          ->prepend(User::where('email','teacher@afhm.test')->firstOrFail());
        foreach($teachers as $i=>$t){Timetable::create(['admin_id'=>$admin->id,'teacher_id'=>$t->id,'day'=>['Sun','Mon','Tue','Wed'][$i],'start_time'=>'08:00','end_time'=>'09:00','classroom'=>'Room '.($i+1),'subject'=>$t->subject]);foreach(['weekly','monthly'] as $type){TeachingReport::create(['teacher_id'=>$t->id,'title'=>ucfirst($type).' teaching progress — '.$t->name,'content'=>'Students completed the planned language objectives. The next lessons will reinforce vocabulary through guided practice and collaborative work.','report_type'=>$type,'submitted_at'=>now()->subDays($i+1)]);}Evaluation::create(['supervisor_id'=>$t->supervisor_id,'teacher_id'=>$t->id,'score'=>4.0+($i/10),'comments'=>'Strong lesson preparation and positive student engagement. Continue using differentiated practice tasks.','evaluated_at'=>now()->subDays($i+2)]);Feedback::create(['supervisor_id'=>$t->supervisor_id,'teacher_id'=>$t->id,'rating'=>4+($i%2),'content'=>'Clear classroom routines and good alignment with the weekly learning goals.','submitted_at'=>now()->subDay()]);}
        foreach(['Grade 7 Grammar Practice','Conversation Activities','Literature Reading Guide'] as $i=>$title){TeachingMaterial::create(['uploaded_by'=>$admin->id,'title'=>$title,'description'=>'A department resource prepared for practical classroom use.','subject'=>['Grammar','Conversation','Literature'][$i],'grade'=>'Grade '.(7+$i),'material_type'=>'PDF','file_path'=>'demo/'.$i.'.pdf','uploaded_at'=>now()->subDays($i)]);}
    }
}
