<?php
namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait RecordsAuditLogs
{
    protected static function bootRecordsAuditLogs()
    {
        // Registrar creación
        static::created(function ($model) {
            if (Auth::check()) {
                // Registrar log de creación
                $model->recordAuditLog('create', $model->getAttributes());
            }
        });

        // Registrar actualización
        static::updated(function ($model) {
            if (Auth::check()) {
                $changes = $model->getChanges();
                if (!empty($changes)) {
                    // Registrar log de actualización con valores antiguos y nuevos
                    $model->recordAuditLog('update', [
                        'old' => $model->getOriginal(), // Valores originales
                        'new' => $changes, // Nuevos valores
                    ]);
                }
            }
        });

        // Registrar eliminación
        static::deleted(function ($model) {
            if (Auth::check()) {
                // Registrar log de eliminación con los valores originales
                $model->recordAuditLog('delete', $model->getOriginal());
            }
        });
    }

    protected function recordAuditLog(string $action, $changes = null)
    {

        AuditLog::create([
            'user_id' => Auth::id() ?? 1, // Usuario logueado
            'action' => $action, // Acción realizada
            'model_type' => get_class($this), // Modelo afectado
            'model_id' => $this->getKey(), // ID del registro afectado
            'changes' => isset($changes['new']) == true ? json_encode($changes['new']) : null, // Cambios estructurados (antiguos y nuevos)
            'changes_old' => isset($changes['old']) == true ? json_encode($changes['old']) : null, // Valores antiguos por separado
            'performed_at' => now(), // Fecha y hora de la acción
        ]);
    }
}
