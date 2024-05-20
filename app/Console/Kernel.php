<?php

namespace App\Console;

use App\Mail\WorkOrderNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\CardObjectMain;
use App\Models\CardWorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $now = now();
            $objects = CardObjectMain::all();
            foreach ($objects as $object) {
                foreach ($object->services as $service) {
                    $plannedMaintenanceDate = Carbon::parse($service->planned_maintenance_date);
                    $notificationDate = $plannedMaintenanceDate->subDays(14); // Получаем дату уведомления за 14 дней до плановой даты обслуживания
                    if ($now->isSameDay($notificationDate)) {
                        // Проверяем, был ли уже создан заказ-наряд для данного объекта и его обслуживания
                        $existingWorkOrder = CardWorkOrder::where('card_id', $object->id)
                            ->where('card_object_services_id', $service->id)
                            ->exists();
                        if (!$existingWorkOrder) {
                            // Создаем новый заказ-наряд
                            $newWorkOrder = new CardWorkOrder();
                            $newWorkOrder->card_id = $object->id;
                            $newWorkOrder->card_object_services_id = $service->id;
                            $newWorkOrder->date_create = $now->format('d-m-Y');
                            $newWorkOrder->status = 'В работе';
                            // Также можно добавить другие поля, например, номер заказа
                            $newWorkOrder->save();
                            // Отправляем уведомления
                            $this->sendNotifications($object, $service, $newWorkOrder);
                        }
                    }
                }
            }
           // Log::info('Планировщик Laravel выполняется каждую секунду');
            Log::info('Планировщик выполнен');
        })->everySecond();

    }

//    protected function sendNotifications($object, $service, $workOrder)
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
//                    Mail::to($recipient->email)->send(new WorkOrderNotification($workOrder, $object, $service));
//                    Log::info("Уведомление отправлено на {$recipient->email} для объекта {$object->name}");
//                } catch (\Exception $e) {
//                    Log::error("Ошибка при отправке почты на {$recipient->email}: " . $e->getMessage());
//                }
//            }
//        }
//    }
    protected function sendNotifications($object, $service, $workOrder)
    {
        $performer = User::where('name', $service->performer)->first();
        $responsible = User::where('name', $service->responsible)->first();
        $curator = User::where('name', $object->curator)->first();

        $recipients = collect([$performer, $responsible, $curator])->filter();

        foreach ($recipients as $recipient) {
            if ($recipient) {
                Notification::create([
                    'user_id' => $recipient->id,
                    'title' => 'Новый заказ-наряд',
                    'message' => "Создан новый заказ-наряд для объекта {$object->name} ({$object->location}). Тип работы: {$service->service_type}. Дата обслуживания: {$service->planned_maintenance_date}. Номер заказа-наряда: {$workOrder->number}.",
                ]);
            }
        }
    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
