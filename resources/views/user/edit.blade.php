@extends('template')

@section('title','Editar usuario')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('panel')}}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{route('users.index')}}">Usuarios</a></li>
        <li class="breadcrumb-item active">Editar usuario</li>
    </ol>
    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{route('users.update',['user'=>$user])}}" method="post" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre',$user->nombre)}}">

                    <div class="col-sm-6">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" value="{{old('apellido',$user->apellido)}}">

                    <div class="col-sm-6">
                        @error('apellido')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="text" name="email" id="email" class="form-control" value="{{old('email',$user->email)}}">

                    <div class="col-sm-6">
                        @error('email')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Celular:</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{old('telefono',$user->telefono)}}">

                    <div class="col-sm-6">
                        @error('telefono')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control" value="{{old('password')}}">
                    <div class="col-sm-4">
                        <div class="form-text">Escriba una contraseña segura.</div>
                    </div>
                    <div class="col-sm-6">
                        @error('password')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Confirmar Contraseña:</label>
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" value="{{old('password')}}">
                    <div class="col-sm-4">
                        <div class="form-text">Vuelva a escribir su contraseña</div>
                    </div>
                    <div class="col-sm-6">
                        @error('password_confirm')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <!---Permisos---->
                <div class="col-12">
                    <p class="text-muted">Seleccionar rol:</p>
                    <div class="col-sm-4">
                        <select name="role" id="role" class="form-select">
                            <option value="" selected disabled>Seleccione</option>
                            @foreach ($roles as $item )
                            @if (in_array($item->name,$user->roles->pluck('name')->toArray()))

                            <option selected value="{{$item->name}}" @selected(old('role')==$item->name)>{{$item->name}}</option>
                            @else

                            <option value="{{$item->name}}" @selected(old('role')==$item->name)>{{$item->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="comunidade" class="form-label">Comunidad</label>
                    <select class="form-select" id="comunidade" name="comunidade">
                        <option value="">Seleccione una comunidad</option>
                        @foreach($comunidades as $comunidad)
                        <option value="{{ $comunidad->id }}"
                            @selected((old('comunidad_id') ?? $user->comunidad_id) == $comunidad->id)>
                            {{ $comunidad->nombre }}
                        </option>
                        @endforeach
                    </select>
                    <small>Si no encuentra su comunidad, puede crear una nueva:</small>
                    <input type="text" class="form-control mt-2" name="nueva_comunidad"
                        value="{{ old('nueva_comunidad') }}" placeholder="Nombre de la nueva comunidad">
                </div>


                @error('role')
                <small class="text-danger">{{'*'.$message}}</small>
                @enderror
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@endpush