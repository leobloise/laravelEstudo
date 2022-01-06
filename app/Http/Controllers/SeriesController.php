<?php

namespace App\Http\Controllers;

use App\Episodio;
use App\Http\Requests\DeleteFormRequest;
use App\Http\Requests\SeriesFormRequest;
use App\Serie;
use App\Temporada;
use App\Services\CriadorDeSerie;
use App\Services\DeletorDeSerie;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request) {
        $series = Serie::query()
            ->orderBy('nome')
            ->get();
        $mensagem = $request->session()->get('mensagem');

        return view('series.index', compact('series', 'mensagem'));
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request, CriadorDeSerie $criadorDeSerie)
    {   
        
        $serie = $criadorDeSerie->criarSerie($request->nome, $request->qtd_temporadas, $request->ep_por_temporada);
        $mensagem = "";

        if($serie === false) {
            $mensagem = "A série {$request->nome} não foi criada com sucesso";
        } else {
            $mensagem = "Série {$serie->id} criada com sucesso | {$serie->nome}";
        }

        $request->session()
            ->flash(
                'mensagem',
                $mensagem
            );

        return redirect()->route('listar_series');
    }

    public function edit(int $id, Request $request)
    {
        $novoNome = $request->nome;
        $serie = Serie::find($id);
        $serie->nome = $novoNome;
        $serie->save();
    }

    public function destroy(Request $request, DeletorDeSerie $deletorDeSerie)
    {
        if($deletorDeSerie->remove($request->id)) {
            $request->session()
            ->flash(
                'mensagem',
                "Série removida com sucesso"
            );
            return redirect()->route('listar_series');
        } 
        
        $request
        ->session()
        ->flash(
                'mensagem',
                'Não foi possível deletar a série devido a um erro'
            );
        return redirect()->route('listar_series');
    }
}
