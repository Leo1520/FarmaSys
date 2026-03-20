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
     * Scope para obtener solo admins
     */
    public function scopeAdmin($query)
    {
        return $query->where('rol', 'admin');
    }

    /**
     * Actualizar último acceso
     */
    public function actualizarUltimoAcceso()
    {
        $this->update(['ultimo_acceso' => now()]);
    }
}
