<?php

namespace App\Console;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\CardObjectMain;
use App\Models\CardWorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $this->checkForUpcomingMaintenances();
        })->everyMinute(); // Измените расписание в зависимости от ваших требований

        $schedule->call(function () {
            $this->checkForOverdueMaintenances();
        })->everyMinute(); // Измените расписание в зависимости от ваших требований
    }

    protected function checkForUpcomingMaintenances()
    {
        $now = now();
        $objects = CardObjectMain::all();
        Log::info('Проверка плановых обслуживаний. Объекты: ' . $objects);

        foreach ($objects as $object) {
            foreach ($object->services as $service) {
                $plannedMaintenanceDate = Carbon::parse($service->planned_maintenance_date);
                $notificationDate = $plannedMaintenanceDate->subDays(14);

                if ($now->isSameDay($notificationDate)) {
                    $existingWorkOrder = CardWorkOrder::where('card_id', $object->id)
                        ->where('card_object_services_id', $service->id)
                        ->exists();

                    if (!$existingWorkOrder) {
                        $newWorkOrder = new CardWorkOrder();
                        $newWorkOrder->card_id = $object->id;
                        $newWorkOrder->card_object_services_id = $service->id;
                        $newWorkOrder->date_create = $now->format('d-m-Y');
                        $newWorkOrder->status = 'В работе';
                        $newWorkOrder->save();
                        $this->sendNotifications($object, $service, $newWorkOrder);
                    }
                }
            }
        }

        Log::info('Проверка плановых обслуживаний завершена');
    }

    protected function checkForOverdueMaintenances()
    {
        $now = now();
        $workOrders = CardWorkOrder::where('status', '!=', 'Завершено')->get();
    // Log::info('Проверка просроченных обслуживаний. Заказ-наряды: ' . $workOrders);

        foreach ($workOrders as $workOrder) {
            $plannedMaintenanceDate = Carbon::parse($workOrder->planned_maintenance_date);
            $overdueDays = $now->diffInDays($plannedMaintenanceDate, false);

            if ($overdueDays < -7) {
                $object = $workOrder->cardObject;
                $service = $workOrder->service;
                $this->sendOverdueNotifications($object, $service, $workOrder);
                Log::info('Просроченные:' .  $service);
            }
            Log::info('Просроченных нет');
        }

        Log::info('Проверка просроченных обслуживаний завершена');
    }

    protected function sendNotifications($object, $service, $newWorkOrder)
    {
        $performer = User::where('name', $service->performer)->first();
        $responsible = User::where('name', $service->responsible)->first();
        $curator = User::where('name', $object->curator)->first();

        $recipients = collect([$performer, $responsible, $curator])->filter();

        foreach ($recipients as $recipient) {
            if ($recipient && $recipient->email) {
                $this->sendEmail($recipient->email, $newWorkOrder, $object, $service);
            }
        }
    }

    protected function sendOverdueNotifications($object, $service, $workOrder)
    {
        $performer = User::where('name', $service->performer)->first();
        $responsible = User::where('name', $service->responsible)->first();
        $curator = User::where('name', $object->curator)->first();

        $recipients = collect([$performer, $responsible, $curator])->filter();

        foreach ($recipients as $recipient) {
            if ($recipient && $recipient->email) {
                $this->sendOverdueEmail($recipient->email, $workOrder, $object, $service);
            }
        }
    }

    private function sendEmail($recipientEmail, $workOrder, $object, $service)
    {
        $subject = 'Уведомление о заказ-наряде';
        $message = $this->buildMessage($workOrder, $object, $service);
        $headers = $this->buildHeaders();

        if (mail($recipientEmail, $subject, $message, $headers)) {
            Log::info("Уведомление отправлено на {$recipientEmail} для объекта {$object->name}");
        } else {
            Log::error("Ошибка при отправке почты на {$recipientEmail}");
        }
    }

    private function sendOverdueEmail($recipientEmail, $workOrder, $object, $service)
    {
        $subject = 'Уведомление о просроченном заказ-наряде';
        $message = $this->buildOverdueMessage($workOrder, $object, $service);
        $headers = $this->buildHeaders();

        if (mail($recipientEmail, $subject, $message, $headers)) {
            Log::info("Уведомление о просрочке отправлено на {$recipientEmail} для объекта {$object->name}");
        } else {
            Log::error("Ошибка при отправке почты на {$recipientEmail}");
        }
    }

    private function buildMessage($workOrder, $object, $service)
    {
        return "
            <html>
            <head>
                <title>Уведомление о заказ-наряде</title>
            </head>
            <body>
                <h1>Уведомление о заказ-наряде</h1>
                <p><strong>ID заказ-наряда:</strong> {$workOrder->id}</p>
                <p><strong>Дата создания:</strong> {$workOrder->date_create}</p>
                <p><strong>Статус:</strong> {$workOrder->status}</p>
                <p><strong>Название объекта:</strong> {$object->name}</p>
                <p><strong>Куратор объекта:</strong> {$object->curator}</p>
                <p><strong>Исполнитель:</strong> {$service->performer}</p>
                <p><strong>Ответственный:</strong> {$service->responsible}</p>
                <p><strong>Плановая дата обслуживания:</strong> {$service->planned_maintenance_date}</p>
            </body>
            </html>
        ";
    }

    private function buildOverdueMessage($workOrder, $object, $service)
    {
        return "
            <html>
            <head>
                <title>Уведомление о просроченном заказ-наряде</title>
            </head>
            <body>
                <h1>Уведомление о просроченном заказ-наряде</h1>
                <p><strong>ID заказ-наряда:</strong> {$workOrder->id}</p>
                <p><strong>Дата создания:</strong> {$workOrder->date_create}</p>
                <p><strong>Статус:</strong> {$workOrder->status}</p>
                <p><strong>Название объекта:</strong> {$object->name}</p>
                <p><strong>Куратор объекта:</strong> {$object->curator}</p>
                <p><strong>Исполнитель:</strong> {$service->performer}</p>
                <p><strong>Ответственный:</strong> {$service->responsible}</p>
                <p><strong>Плановая дата обслуживания:</strong> {$service->planned_maintenance_date}</p>
                <p><strong>Просрочка:</strong> на более чем 7 дней</p>
            </body>
            </html>
        ";
    }

    private function buildHeaders()
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: webmaster@example.com' . "\r\n";
        $headers .= 'Reply-To: webmaster@example.com' . "\r\n";

        return $headers;
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
