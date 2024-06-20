<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkOrderNotification;

class MailTestController extends Controller
{
    public function sendTestEmail()
    {
        $recipientEmail = 'e.samsonova@kst-energo.ru'; // Замените на нужный адрес
        $workOrder = [
            'id' => 1,
            'date_create' => '2024-06-01',
            'status' => 'В работе',
        ];
        $object = [
            'name' => 'Test Object',
            'curator' => 'Curator Name',
        ];
        $service = [
            'performer' => 'Performer Name',
            'responsible' => 'Responsible Name',
            'planned_maintenance_date' => '2024-06-15',
        ];

        try {
            Mail::to($recipientEmail)->send(new WorkOrderNotification($workOrder, $object, $service));
            return 'Email sent successfully';
        } catch (\Exception $e) {
            return 'Failed to send email: ' . $e->getMessage();
        }
    }
}
