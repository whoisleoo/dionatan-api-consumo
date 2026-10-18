<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'genero' => $this->genero,
            'temporadas' => $this->temporadas,
            'nota' => $this->nota,
            'cadastrado_em' => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
