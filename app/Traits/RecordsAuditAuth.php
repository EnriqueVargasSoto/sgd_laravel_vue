<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\SessionAudit;
use App\Models\User;

trait RecordsAuditAuth
{
    /**
     * Registrar auditoría de inicio de sesión.
     */
    public static function logLogin()
    {
        self::recordSessionAudit('login');
    }

    /**
     * Registrar auditoría de cierre de sesión.
     */
    public static function logLogout()
    {
        self::recordSessionAudit('logout');
    }

    /**
     * Registrar un evento de sesión en la tabla session_audits.
     */
    protected static function recordSessionAudit(string $action)
    {
        $user = Auth::user();
        $ipAddress = Request::ip();
        $hostname = gethostname();
        $macAddress = self::getMacAddress();//strtok(exec('getmac'), ' '); // Funciona en Windows, ajusta para Linux si es necesario.

        SessionAudit::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'ip_address' => $ipAddress,
            'hostname' => $hostname,
            'mac_address' => $macAddress,
            'performed_at' => now(),
        ]);

        $usuario = User::find($user->id);

        if ($action === 'login') {

            $usuario->host = $hostname;
            $usuario->ip = $ipAddress;
            $usuario->mac = $macAddress;
            $usuario->save();
        } else {
            $usuario->host = null;
            $usuario->ip = null;
            $usuario->mac = null;
            $usuario->save();
        }

    }

    protected static function getMacAddress()
    {
        $os = PHP_OS_FAMILY;

        if ($os === 'Windows') {
            $output = shell_exec('getmac');
            // Filtrar las líneas válidas que contienen direcciones MAC
            $lines = explode(PHP_EOL, $output);
            foreach ($lines as $line) {
                if (preg_match('/^([0-9A-F]{2}(-[0-9A-F]{2}){5})/', $line, $matches)) {
                    return $matches[1]; // Retorna la primera dirección MAC válida
                }
            }
        } elseif ($os === 'Linux' || $os === 'Darwin') {
            $output = shell_exec('ip link show');
            preg_match('/ether ([0-9a-f:]{17})/', $output, $matches);
            return $matches[1] ?? 'N/A';
        }

        return 'N/A'; // Por defecto, si no se encuentra una dirección
    }

}
