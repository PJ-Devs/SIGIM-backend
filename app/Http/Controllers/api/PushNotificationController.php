<?php

namespace App\Http\Controllers\api;

use App\Services\PushNotificationService;
use Illuminate\Routing\Controller;

class PushNotificationController extends Controller
{
    protected $pushNotificationService;

    public function __construct()
    {
        $this->pushNotificationService = new PushNotificationService();
    }

    public function sendPushNotification()
    {
        $this->pushNotificationService->sendNotification(
            'Alerta de stock bajo',
            "Quedan 5 existencias del producto comida!",
            ['product_id' => 1],
            env('FIREBASE_TOKEN') // Asegúrate de que este token sea válido.
        );

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
