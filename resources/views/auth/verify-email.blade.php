@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Verificación de Código</h2>
        <form action="{{ route('verify') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="verification_code">Ingresa el código de verificación</label>
                <input type="text" name="verification_code" id="verification_code" class="form-control" required>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Verificar</button>
        </form>
    </div>
@endsection
