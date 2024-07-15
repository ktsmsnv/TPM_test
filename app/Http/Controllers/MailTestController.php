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



    public function sendEmail()
    {
        $recipientEmail = 'h.@kst-energo.ru'; // Замените на нужный адрес
        $subject = 'Уведомление о заказ-наряде';
        $message = $this->buildMessage();
        $headers = $this->buildHeaders();

        if (mail($recipientEmail, $subject, $message, $headers)) {
            return 'Email sent successfully';
        } else {
            return 'Failed to send email';
        }
    }

    private function buildMessage()
    {
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

        $message = "
            <html>
            <head>
                <title>Уведомление о заказ-наряде</title>
            </head>
            <body>
                <h1>Уведомление о заказ-наряде</h1>
                <p><strong>ID заказ-наряда:</strong> {$workOrder['id']}</p>
                <p><strong>Дата создания:</strong> {$workOrder['date_create']}</p>
                <p><strong>Статус:</strong> {$workOrder['status']}</p>
                <p><strong>Название объекта:</strong> {$object['name']}</p>
                <p><strong>Куратор объекта:</strong> {$object['curator']}</p>
                <p><strong>Исполнитель:</strong> {$service['performer']}</p>
                <p><strong>Ответственный:</strong> {$service['responsible']}</p>
                <p><strong>Плановая дата обслуживания:</strong> {$service['planned_maintenance_date']}</p>
            </body>
            </html>
        ";

        return $message;
    }

    private function buildHeaders()
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: webmaster@example.com' . "\r\n";
        $headers .= 'Reply-To: webmaster@example.com' . "\r\n";

        return $headers;
    }
}
