@extends('layouts.app')

@section('title', 'Listas de Compra')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Listas de Compra</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('lista-compra.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Nueva Lista
            </a>
        </div>
    </div>

    <!-- Mensajes Flash -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabla de listas -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>Fecha de Creación</th>
                    <th>Medicamentos</th>
                    <th>Total Estimado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($listas as $lista)
                    <tr>
                        <td>
                            <span class="badge bg-secondary">{{ $lista->id }}</span>
                        </td>
                        <td>
                            {{ $lista->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $lista->detalles()->count() }} medicamentos</span>
                        </td>
                        <td>
                            <strong class="text-success">${{ number_format($lista->totalEstimado(), 2) }}</strong>
                        </td>
                        <td>
                            @if ($lista->estado === 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif ($lista->estado === 'comprada')
                                <span class="badge bg-success">Comprada</span>
                            @else
                                <span class="badge bg-danger">Cancelada</span>
                            @endif
                        </td>
                        <td>
                            <!-- Botón Ver -->
                            <a href="{{ route('lista-compra.show', $lista->id) }}" 
                               class="btn btn-sm btn-info" 
                               title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>

                            <!-- Botón Editar -->
                            <a href="{{ route('lista-compra.edit', $lista->id) }}" 
                               class="btn btn-sm btn-warning" 
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('lista-compra.destroy', $lista->id) }}" 
                                  method="POST" 
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="return confirm('¿Estás seguro?')"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox"></i> No hay listas de compra aún.
                            <br>
                            <a href="{{ route('lista-compra.create') }}" class="btn btn-sm btn-primary mt-2">
                                Crear la primera
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if ($listas->count())
        <div class="d-flex justify-content-center mt-4">
            {{ $listas->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
