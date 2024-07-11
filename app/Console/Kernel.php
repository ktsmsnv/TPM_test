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
    public function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $now = now();
            $objects = CardObjectMain::all();
            Log::info('Объекты 1: ' . $objects);
            foreach ($objects as $object) {
                foreach ($object->services as $service) {
                    $plannedMaintenanceDate = Carbon::parse($service->planned_maintenance_date);
                    $notificationDate = $plannedMaintenanceDate->subDays(14);
                //    Log::info('План дата: ' . $plannedMaintenanceDate);
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
                         //   Log::info('Был создан новый заказ');
                        }
                    }
                }
            }
            Log::info('Планировщик выполнен');
        })->everySecond();
    }

    public function sendNotifications($object, $service, $newWorkOrder)
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

    private function buildMessage($workOrder, $object, $service)
    {
        $message = "
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

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

//
//namespace App\Console;
//
//use App\Mail\WorkOrderNotification;
//use App\Models\Notification;
//use App\Models\User;
//use Illuminate\Console\Scheduling\Schedule;
//use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
//
//use App\Models\CardObjectMain;
//use App\Models\CardWorkOrder;
//use Carbon\Carbon;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Mail;
//
//
//class Kernel extends ConsoleKernel
//{
//    /**
//     * Define the application's command schedule.
//     */
//    protected function schedule(Schedule $schedule): void
//    {
//        $schedule->call(function () {
//            $now = now();
//            $objects = CardObjectMain::all();
//            foreach ($objects as $object) {
//                foreach ($object->services as $service) {
//                    // Проверяем, имеет ли услуга флаг checked
////                    if ($service->checked) {
////                        continue; // Пропускаем эту услугу, если она помечена checked
////                    }
//                    $plannedMaintenanceDate = Carbon::parse($service->planned_maintenance_date);
//                    $notificationDate = $plannedMaintenanceDate->subDays(14); // Получаем дату уведомления за 14 дней до плановой даты обслуживания
//                    if ($now->isSameDay($notificationDate)) {
//                        // Проверяем, был ли уже создан заказ-наряд для данного объекта и его обслуживания
//                        $existingWorkOrder = CardWorkOrder::where('card_id', $object->id)
//                            ->where('card_object_services_id', $service->id)
//                            ->exists();
//                        if (!$existingWorkOrder) {
//                            // Создаем новый заказ-наряд
//                            $newWorkOrder = new CardWorkOrder();
//                            $newWorkOrder->card_id = $object->id;
//                            $newWorkOrder->card_object_services_id = $service->id;
//                            $newWorkOrder->date_create = $now->format('d-m-Y');
//                            $newWorkOrder->status = 'В работе';
//                            // Также можно добавить другие поля, например, номер заказа
//                            $newWorkOrder->save();
//                            // Отправляем уведомления
//                            $this->sendNotifications($object, $service, $newWorkOrder);
//                        }
//                    }
//                }
//            }
//           // Log::info('Планировщик Laravel выполняется каждую секунду');
//            Log::info('Планировщик выполнен');
//        })->everySecond();
//
//    }
//    /**
//     * Send notifications.
//     *
//     * @param  mixed  $object
//     * @param  mixed  $service
//     * @param  mixed  $newWorkOrder
//     * @return void
//     */
//    protected function sendNotifications($object, $service, $newWorkOrder)
//    {
//        $performer = User::where('name', $service->performer)->first();
//        $responsible = User::where('name', $service->responsible)->first();
//        $curator = User::where('name', $object->curator)->first();
//
//        $recipients = collect([$performer, $responsible, $curator])->filter();
//
//        foreach ($recipients as $recipient) {
//            if ($recipient && $recipient->email) {
//                try {
//                    Mail::to($recipient->email)->send(new WorkOrderNotification($newWorkOrder, $object, $service));
//                    Log::info("Уведомление отправлено на {$recipient->email} для объекта {$object->name}");
//                } catch (\Exception $e) {
//                    Log::error("Ошибка при отправке почты на {$recipient->email}: " . $e->getMessage());
//                }
//            }
//        }
//    }
////    protected function sendNotifications($object, $service, $workOrder)
////    {
////        $performer = User::where('name', $service->performer)->first();
////        $responsible = User::where('name', $service->responsible)->first();
////        $curator = User::where('name', $object->curator)->first();
////
////        $recipients = collect([$performer, $responsible, $curator])->filter();
////
////        foreach ($recipients as $recipient) {
////            if ($recipient) {
////                Notification::create([
////                    'user_id' => $recipient->id,
////                    'title' => 'Новый заказ-наряд',
////                    'message' => "Создан новый заказ-наряд для объекта {$object->name} ({$object->location}). Тип работы: {$service->service_type}. Дата обслуживания: {$service->planned_maintenance_date}. Номер заказа-наряда: {$workOrder->number}.",
////                ]);
////            }
////        }
////    }
//
//
//    /**
//     * Register the commands for the application.
//     *
//     * @return void
//     */
//    protected function commands(): void
//    {
//        $this->load(__DIR__.'/Commands');
//
//        require base_path('routes/console.php');
//    }
//}
