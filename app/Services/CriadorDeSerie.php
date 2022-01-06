<?php

namespace App\Services;

use App\Serie;
use App\Temporada;
use Illuminate\Support\Facades\DB;
use Throwable;

class CriadorDeSerie
{

    public function criarSerie(string $nome, int $qtdTemporadas, int $epPorTemporada)
    {   

        DB::beginTransaction();
        $serie = Serie::create(['nome'  => $nome]);
        $this->createTemporada($serie, $qtdTemporadas, $epPorTemporada);
        DB::commit();

        return $serie;

    }

    private function createTemporada(Serie $serie, int $qtdTemporadas, int $epPorTemporada) 
    {
        for($i = 1; $i <= $qtdTemporadas; $i++) {
            $temporada = $serie->temporadas()->create(['numero'  => $i]);
            $this->createEpisodio($epPorTemporada, $temporada);
        }
    }

    private function createEpisodio(int $epPorTemporada, Temporada $temporada)
    {
        for($j = 1; $j < $epPorTemporada; $j++) {
            $temporada->episodios()->create(['numero'   => $j]);
        }
    }

}