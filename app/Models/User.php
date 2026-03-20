<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'estado',
        'razon_rechazo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ultimo_acceso' => 'datetime',
        ];
    }

    /**
     * Relación: Un usuario tiene muchas acciones en el historial
     */
    public function acciones(): HasMany
    {
        return $this->hasMany(HistorialAccion::class, 'user_id');
    }

    /**
     * Verificar si el usuario es admin
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Verificar si el usuario es farmacéutica
     */
    public function esFarmaceutica(): bool
    {
        return $this->rol === 'farmaceutica';
    }

    /**
     * Verificar si el usuario es invitado
     */
    public function esInvitado(): bool
    {
        return $this->rol === 'invitado';
    }

    /**
     * Verificar si la cuenta está activa
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Verificar si la cuenta está pendiente de aprobación
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si tiene un permiso específico
     */
    public function puede(string $permiso): bool
    {
        return \App\Services\PermissionService::can($permiso, $this->rol);
    }

    /**
     * Verificar si el email ha sido verificado
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Marcar email como verificado
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Scope para obtener solo admins
     */
    public function scopeAdmin($query)
    {
        return $query->where('rol', 'admin');
    }

    /**
     * Scope para obtener usuarios pendientes de aprobación
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para obtener usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Actualizar último acceso
     */
    public function actualizarUltimoAcceso()
    {
        $this->update(['ultimo_acceso' => now()]);
    }
}
