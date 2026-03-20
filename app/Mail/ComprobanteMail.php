<?php

namespace App\Mail;

use App\Models\MovimientoInventario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailbox;
use Illuminate\Queue\SerializesModels;

class ComprobanteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public MovimientoInventario $movimiento,
        public string $pdfPath
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Mailbox
    {
        return new Mailbox(
            subject: "Comprobante #{$this->movimiento->id} - FarmaSys"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        $tipoMovimiento = $this->movimiento->tipo === 'entrada' ? 'Compra' : 'Salida/Venta';
        
        return $this
            ->markdown('emails.comprobante')
            ->with([
                'movimiento' => $this->movimiento,
                'usuario' => $this->movimiento->usuario,
                'medicamento' => $this->movimiento->medicamento,
                'tipoMovimiento' => $tipoMovimiento,
                'total' => $this->movimiento->cantidad * ($this->movimiento->precio_unitario ?? 0),
            ]);
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            $this->attach($this->pdfPath, [
                'as' => "comprobante-{$this->movimiento->id}-" . date('Y-m-d') . '.pdf',
                'mime' => 'application/pdf',
            ]),
        ];
    }
}
