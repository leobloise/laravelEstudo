<?php

namespace App\Services;

use App\Episodio;
use App\Serie;
use App\Temporada;
use Illuminate\Support\Facades\DB;

class DeletorDeSerie
{

    public function remove(int $id)
    {
        DB::transaction(function() use ($id) {
            $serie = Serie::find($id);
            $serie->temporadas->each(function (Temporada $temporada) {
                $this->removeTemporadas($temporada);
            });
            Serie::destroy($id);
            return 0;
        });

        return true;
            
        
    }

    private function removeTemporadas(Temporada $temporada)
    {
        $this->removeEpisodios($temporada);
        $temporada->delete();
    }

    private function removeEpisodios(Temporada $temporada)
    {
        $temporada->episodios()->each(function (Episodio $episodio) {
            $episodio->delete();
        });
    }

}