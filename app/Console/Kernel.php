<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\CardObjectMain;
use App\Models\CardWorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


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
                        }
                    }
                }
            }
           // Log::info('Планировщик Laravel выполняется каждую секунду');
        })->everySecond();

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
