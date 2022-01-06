@extends('layout')

@section('cabecalho')
Séries
@endsection

@section('conteudo')
@if(!empty($mensagem))
<div class="alert alert-success">
    {{ $mensagem }}
</div>
@endif

<a href="{{ route('form_criar_serie') }}" class="btn btn-dark mb-2">Adicionar</a>

<ul class="list-group">
    @foreach($series as $serie)
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span id="nome-serie-{{$serie->id}}">{{$serie->nome}}</span>
        <div class="input-group w-50" hidden id="input-nome-serie-{{$serie->id}}">
            <input type="text" class="form-control" value="{{$serie->nome}}">
            <button class="btn btn-primary" onclick="editarSerie({{$serie->id}})">
                <i class="fas fa-check"></i>
            </button>
            @csrf
        </div>
        <span class="d-flex">
            <button class="btn btn-info btn-sm mr-1" onclick="toggleInput({{$serie->id}})">
                <i class="fas fa-edit"></i>
            </button>
            <a href="/series/{{$serie->id}}/temporada" class="btn btn-info btn-sm"> Temporadas </a>
            <form method="post" action="/series/{{ $serie->id }}"
                onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($serie->nome) }}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">
                    <i class="far fa-trash-alt"></i>
                </button>
            </form>
        </span>
       
    </li>
    @endforeach
</ul>
<script>
    function editarSerie(id) {
        const formData = new FormData();
        const inputFromSerie = document.querySelector(`#input-nome-serie-${id} > input`)
        const nomeSerie = inputFromSerie.value;
        const url = `/series/${id}/editaNome`;
        const token = document.querySelector(`#input-nome-serie-${id} > input[name="_token"]`).value
        formData.append('nome', nomeSerie);
        formData.append('_token', token)
        fetch(url, {
            body: formData,
            method: 'POST'
        }).then(() => {
            toggleInput(id)
            document.querySelector(`#nome-serie-${id}`).textContent = nomeSerie;
        })
        
    }
    function toggleInput(id) {

        const nomeSerieEl = document.querySelector(`#nome-serie-${id}`)
        const inputSerieEl =  document.querySelector(`#input-nome-serie-${id}`);
        
        if(nomeSerieEl.hasAttribute("hidden")) {
            nomeSerieEl.removeAttribute("hidden");
            inputSerieEl.hidden = true;
        } else {    
            inputSerieEl.removeAttribute('hidden')
            nomeSerieEl.hidden = true;
        }
        
    }
</script>
@endsection