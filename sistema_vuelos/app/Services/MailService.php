<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private function mailer(): PHPMailer
    {
        $m = new PHPMailer(true);
        $m->isSMTP();
        $m->Host       = env('MAIL_HOST', 'smtp.gmail.com');
        $m->SMTPAuth   = true;
        $m->Username   = env('MAIL_USERNAME');
        $m->Password   = env('MAIL_PASSWORD');
        $m->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL puerto 465
        $m->Port       = (int) env('MAIL_PORT', 465);
        $m->CharSet    = 'UTF-8';
        $m->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Sincronía Aérea'));
        return $m;
    }

    // ── Verificación de correo al registrarse ──
    public function enviarVerificacion(string $email, string $nombre, string $token): bool
    {
        try {
            $m = $this->mailer();
            $m->addAddress($email, $nombre);
            $m->isHTML(true);
            $m->Subject = '✈ Verifica tu cuenta — Sincronía Aérea';
            $url = env('APP_URL') . '/verificar/' . $token;
            $m->Body = "
            <div style='font-family:sans-serif;max-width:520px;margin:auto;background:#0b1120;color:#f1f5f9;padding:32px;border-radius:12px'>
                <h2 style='color:#60a5fa'>✈ Sincronía Aérea BOA</h2>
                <p>Hola <strong>{$nombre}</strong>, gracias por registrarte.</p>
                <p>Haz clic en el botón para verificar tu correo electrónico:</p>
                <a href='{$url}'
                   style='display:inline-block;margin:20px 0;padding:12px 28px;background:#3b82f6;color:#fff;border-radius:8px;text-decoration:none;font-weight:bold'>
                   Verificar mi cuenta
                </a>
                <p style='color:#94a3b8;font-size:12px'>Si no creaste esta cuenta, ignora este correo.</p>
            </div>";
            $m->send();
            return true;
        } catch (Exception $e) {
            \Log::error('MailService::enviarVerificacion - ' . $e->getMessage());
            return false;
        }
    }

    // ── Confirmación de reserva ──
    public function enviarConfirmacionReserva(string $email, string $nombre, array $datos): bool
    {
        try {
            $m = $this->mailer();
            $m->addAddress($email, $nombre);
            $m->isHTML(true);
            $m->Subject = "✈ Reserva {$datos['codigo']} Confirmada — Sincronía Aérea";
            $m->Body = "
            <div style='font-family:sans-serif;max-width:560px;margin:auto;background:#0b1120;color:#f1f5f9;padding:32px;border-radius:12px'>
                <h2 style='color:#60a5fa'>✈ Reserva Confirmada</h2>
                <p>Hola <strong>{$nombre}</strong>, tu reserva fue procesada exitosamente.</p>
                <table style='width:100%;border-collapse:collapse;margin:20px 0'>
                    <tr><td style='padding:8px;border-bottom:1px solid #1e293b;color:#94a3b8'>Código</td>
                        <td style='padding:8px;border-bottom:1px solid #1e293b'><strong>{$datos['codigo']}</strong></td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #1e293b;color:#94a3b8'>Vuelo</td>
                        <td style='padding:8px;border-bottom:1px solid #1e293b'>{$datos['vuelo']}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #1e293b;color:#94a3b8'>Destino</td>
                        <td style='padding:8px;border-bottom:1px solid #1e293b'>{$datos['destino']}</td></tr>
                    <tr><td style='padding:8px;border-bottom:1px solid #1e293b;color:#94a3b8'>Pasajeros</td>
                        <td style='padding:8px;border-bottom:1px solid #1e293b'>{$datos['pasajeros']}</td></tr>
                    <tr><td style='padding:8px;color:#94a3b8'>Total</td>
                        <td style='padding:8px;color:#22c55e;font-size:1.2rem'><strong>Bs. {$datos['total']}</strong></td></tr>
                </table>
                <p style='color:#94a3b8;font-size:12px'>Sincronía Aérea BOA — Sistema de Control Operativo</p>
            </div>";
            $m->send();
            return true;
        } catch (Exception $e) {
            \Log::error('MailService::enviarConfirmacionReserva - ' . $e->getMessage());
            return false;
        }
    }

    // ── Reset de contraseña ──
    public function enviarResetPassword(string $email, string $nombre, string $token): bool
    {
        try {
            $m = $this->mailer();
            $m->addAddress($email, $nombre);
            $m->isHTML(true);
            $m->Subject = '🔐 Restablecer contraseña — Sincronía Aérea';
            $url = env('APP_URL') . '/reset-password/' . $token;
            $m->Body = "
            <div style='font-family:sans-serif;max-width:520px;margin:auto;background:#0b1120;color:#f1f5f9;padding:32px;border-radius:12px'>
                <h2 style='color:#f59e0b'>🔐 Restablecer Contraseña</h2>
                <p>Hola <strong>{$nombre}</strong>, recibimos una solicitud para restablecer tu contraseña.</p>
                <a href='{$url}'
                   style='display:inline-block;margin:20px 0;padding:12px 28px;background:#f59e0b;color:#000;border-radius:8px;text-decoration:none;font-weight:bold'>
                   Restablecer contraseña
                </a>
                <p style='color:#94a3b8;font-size:12px'>Este enlace expira en 60 minutos. Si no solicitaste esto, ignora el correo.</p>
            </div>";
            $m->send();
            return true;
        } catch (Exception $e) {
            \Log::error('MailService::enviarResetPassword - ' . $e->getMessage());
            return false;
        }
    }
}
