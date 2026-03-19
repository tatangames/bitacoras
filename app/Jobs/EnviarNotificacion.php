<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use OneSignal;
use Exception;

class EnviarNotificacion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $arrayOnesignal;
    protected $titulo;
    protected $descripcion;

    /**
     * Create a new job instance.
     */
    public function __construct($arrayOnesignal, $titulo, $descripcion)
    {
        $this->arrayOnesignal = $arrayOnesignal;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tituloNoti = $this->titulo;
        $mensajeNoti = $this->descripcion;
        $tokenOneSignal = $this->arrayOnesignal;

        Log::info("=== EnviarNotificacion JOB INICIADO ===");
        Log::info("Mensaje: " . $mensajeNoti);
        Log::info("Token: " . (is_array($tokenOneSignal) ? json_encode($tokenOneSignal) : $tokenOneSignal));

        try {
            $client = new Client();
            $response = $client->post('https://onesignal.com/api/v1/notifications', [
                'json' => [
                    'app_id'           => env('ONESIGNAL_APP_ID'),
                    'contents'         => ['en' => $mensajeNoti],
                    'headings'         => ['en' => $tituloNoti],
                    'android_channel_id' => 'a4d218c6-cfb3-4b3b-bf07-c76cf8d57b5e',
                    'target_channel'   => 'push',
                    'include_aliases'  => [
                        'onesignal_id' => is_array($tokenOneSignal)
                            ? $tokenOneSignal
                            : [$tokenOneSignal],
                    ],
                ],
                'headers' => [
                    'Authorization' => 'Key ' . env('ONESIGNAL_REST_API_KEY'),
                    'Content-Type'  => 'application/json',
                ],
            ]);

            // LOGUEAR RESPUESTA EXITOSA
            Log::info("OneSignal status: " . $response->getStatusCode());
            Log::info("OneSignal response: " . $response->getBody()->getContents());

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Error 4xx - respuesta de OneSignal con detalle
            Log::error("OneSignal 4xx: " . $e->getResponse()->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Error 5xx
            Log::error("OneSignal 5xx: " . $e->getResponse()->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Fallo de conexión / timeout
            Log::error("OneSignal conexión fallida: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Error general: " . $e->getMessage());
        }
    }
}
