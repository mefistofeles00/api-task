<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;

class ContentController extends Controller
{
    public function index()
    {
        return "Api rotalari icin route/api.php dosyasini ziyaret edin!!   
        1- Oncelikler Register olmaniz gerek
        2- api keyini postmana ekleyin
        ";
    }
    public function createTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'task_time' => ['required', 'date_format:Y-d-m'],
            'email' => 'required|email',


        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Tam bir şekilde doldurunuz'], 400);
        }

        $data = $validator->validated();

    if ($data) {

        $task = new Task();
        $task->title = $data['title'];
        $task->subject = $data['subject'];
        $task->task_time = $data['task_time'];
        $task->save();
        $message = $task->subject;
        $sender_email = "info@perdebogazici.com";
        $sender_user_name = "Inanc Eroglu";
        $mail  = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPDebug  = 1;
        $mail->Host       = "mail.perdebogazici.com";
        $mail->Port       = 587;
        $mail->Username   = "info@perdebogazici.com";
        $mail->Password   = "2004derman01";
//End mail config
        $mail->From       = $sender_email;
        $mail->FromName   = $sender_user_name;
        $mail->Subject    = $task->title;
        $mail->MsgHTML($message);
        $mail->AddAddress($request->email, 'inanc Eroglu');
        $mail->IsHTML(); // send as HTML
        if(!$mail->Send()) {//to see if we return a message or a value bolean
            return response()->json(['message' => 'mail gonderilemedi mail kpnfigurasyonlarinizi ayarlayiniz!!'], 401);

        }
        return response()->json(['message' => 'mail gonderildi'], 200);


    }
    return response()->json([
       'message' => "data was created successfull kardesssssss and mail de sended brommm"
    ]);
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task deleted or not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject' => 'required|string',
            'task_time' => 'required|date'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $task->title = $request->title;
        $task->subject = $request->subject;
        $task->task_time = $request->task_time;
        $task->save();

        return response()->json(['message' => 'Task updated successfully'], 200);

    }

    public function delete(Request $request, $id)
    {
        if ($request->user()->is_admin) {
            $task = Task::find($id);
        }else{
            return response()->json(['message' => 'you are not admin shet']);
        }
        if (!$task) {
            return response()->json(['message' => 'Task silinemedi']);
        }

        $task->delete();
        return response()->json(['message' => 'task basari ile silindi']);
    }
}