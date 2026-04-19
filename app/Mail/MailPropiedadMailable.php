<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Propiedad;

class MailPropiedadMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $propiedad; // Variable pública para que la vista la reconozca

    public function __construct(Propiedad $propiedad)
    {
        $this->propiedad = $propiedad;
    }

    public function build()
    {
        return $this->subject('Nueva Propiedad Cargada en el Sistema')
                    ->view('nueva_propiedad'); // Nombre de la vista HTML
    }
}
