<?php

namespace App\Services;

use App\Serie;
use Throwable;

class CriadorDeSerie
{

    public function criarSerie(string $nome, int $qtdTemporadas, int $epPorTemporada)
    {   

        try {
            $serie = Serie::create(['nome'  => $nome]);
        
            for($i = 1; $i <= $qtdTemporadas; $i++) {
                $temporada = $serie->temporadas()->create(['numero'  => $i]);
                for($j = 1; $j < $epPorTemporada; $j++) {
                    $temporada->episodios()->create(['numero'   => $j]);
                }
            }

            return $serie;

        } catch (Throwable $error) {
            echo $error->getMessage();
            return false;
        } 

    }

}