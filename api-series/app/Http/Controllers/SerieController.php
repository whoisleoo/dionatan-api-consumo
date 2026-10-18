<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Http\Resources\SerieResource;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::all();
        return SerieResource::collection($series);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'genero' => 'required|string|max:100',
            'temporadas' => 'required|integer|min:1',
            'nota' => 'nullable|numeric|min:0|max:10'
        ]);

        $serie = Serie::create($dados);

        return (new SerieResource($serie))->response()->setStatusCode(201);
    }

    public function show(Serie $series)
    {
        return new SerieResource($series);
    }

    public function update(Request $request, Serie $series)
    {
        $dados = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'genero' => 'sometimes|string|max:100',
            'temporadas' => 'sometimes|integer|min:1',
            'nota' => 'nullable|numeric|min:0|max:10'
        ]);

        $series->update($dados);

        return new SerieResource($series);
    }

    public function destroy(Serie $series)
    {
        $series->delete();
        return response()->json(['mensagem' => 'Serie removida com sucesso']);
    }
}
