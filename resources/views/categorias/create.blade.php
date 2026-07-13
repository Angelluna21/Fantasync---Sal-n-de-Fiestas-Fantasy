@extends('layouts.app')

@section('content')
<main class="dashboard-layout">
    <header class="dashboard-header">
        <h1 class="dashboard-title">Crear Nueva Categoría</h1>
        <p class="dashboard-description">Registra una nueva categoría para tus platillos.</p>
    </header>

    <section class="dashboard-actions">
        <livewire:categoria-manager />
    </section>
</main>
@endsection