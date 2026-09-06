<?php
namespace App\Http\Controllers; use Illuminate\Http\Request;
class NotificationController extends Controller { public function index(Request $r){$notifications=$r->user()->notifications()->paginate(20);return view('notifications.index',compact('notifications'));} public function read(Request $r,string $id){$n=$r->user()->notifications()->findOrFail($id);$n->markAsRead();return $n->data['url']?redirect($n->data['url']):back();} public function readAll(Request $r){$r->user()->unreadNotifications->markAsRead();return back()->with('success','Notifications marked as read.');}}
