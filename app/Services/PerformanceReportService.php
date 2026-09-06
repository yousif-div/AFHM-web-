<?php
namespace App\Services;
use App\Models\User;
class PerformanceReportService {
 public function metrics(?User $teacher=null, ?string $from=null, ?string $to=null): array { $teachers=User::teachers()->when($teacher,fn($q)=>$q->whereKey($teacher->id))->withCount(['teachingReports'=>fn($q)=>$q->when($from,fn($x)=>$x->whereDate('submitted_at','>=',$from))->when($to,fn($x)=>$x->whereDate('submitted_at','<=',$to)),'evaluationsReceived','feedbackReceived'])->withAvg('evaluationsReceived','score')->withAvg('feedbackReceived','rating')->get(); return ['teachers'=>$teachers->map(fn($t)=>['id'=>$t->id,'name'=>$t->name,'subject'=>$t->subject,'grade'=>$t->grade,'reports'=>$t->teaching_reports_count,'evaluations'=>$t->evaluations_received_count,'average_score'=>round((float)$t->evaluations_received_avg_score,2),'feedback'=>$t->feedback_received_count,'average_rating'=>round((float)$t->feedback_received_avg_rating,2)])->all(),'teacher_count'=>$teachers->count(),'report_count'=>$teachers->sum('teaching_reports_count'),'average_score'=>round((float)$teachers->avg('evaluations_received_avg_score'),2)]; }
}
