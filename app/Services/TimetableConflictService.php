<?php
namespace App\Services;
use App\Models\Timetable;
class TimetableConflictService {
 public function exists(array $data, ?int $ignore=null): bool { return Timetable::query()->when($ignore,fn($q)=>$q->whereKeyNot($ignore))->where('day',$data['day'])->where(fn($q)=>$q->where('teacher_id',$data['teacher_id'])->orWhere('classroom',$data['classroom']))->where('start_time','<',$data['end_time'])->where('end_time','>',$data['start_time'])->exists(); }
}
