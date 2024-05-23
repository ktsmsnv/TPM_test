<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $workOrder;
    public $object;
    public $service;
    /**
     * Create a new message instance.
     *
     * @param  mixed  $workOrder
     * @param  mixed  $object
     * @param  mixed  $service
     * @return void
     */
    public function __construct($workOrder, $object, $service)
    {
        $this->workOrder = $workOrder;
        $this->object = $object;
        $this->service = $service;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Уведомление о заказ-наряде')
            ->view('mail.workOrderNotification');
//            ->with([
//                'workOrder' => $this->workOrder,
//                'object' => $this->object,
//                'service' => $this->service,
//            ]);
    }
}
