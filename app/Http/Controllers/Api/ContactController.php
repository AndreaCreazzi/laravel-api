<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function message(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string'
        ], [
            'email.required' => 'La mail è obbligatoria',
            'email.email' => "L'indirizzo della email non è valido",
            'subject.required' => 'Il messaggio deve contenere un oggetto',
            'message.required' => 'Il messaggio deve avere un contenuto',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $mail = new ContactMessageMail(
            sender: $data['email'],
            subject: $data['subject'],
            content: $data['message'],
        );
        Mail::to('admin@projects.it')->send($mail);
        return response(null, 204);
    }
}
