@extends('layouts.app')

@section('content')
    <section class="card" style="margin-top: 2rem;">
        <article class="info-content">
            <livewire:contrato-menu-builder :eventoId="1" />
        </article>
    </section>
@endsection