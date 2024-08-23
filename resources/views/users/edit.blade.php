{{-- resources/views/users/edit.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1>Editar Usuário</h1>

        <form action="{{ route('users.update', $user->userId) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}"
                    required>
            </div>
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}"
                    required>
            </div>
            <div class="form-group">
                <label>Ativo no Sistema</label>
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="isDeleted" id="isActive" value="false"
                            {{ $user->isDeleted ? '' : 'checked' }}>
                        <label class="form-check-label" for="isActive">Ativo</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="isDeleted" id="isInactive" value="true"
                            {{ $user->isDeleted ? 'checked' : '' }}>
                        <label class="form-check-label" for="isInactive">Inativo</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="createdDtm">Criado em</label>
                <input type="datetime-local" class="form-control" id="createdDtm" name="createdDtm"
                    value="{{ \Carbon\Carbon::parse($user->createdDtm)->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" class="form-control" id="login" name="login" value="{{ $user->login }}"
                    required>
            </div>
            <div class="form-group">
                <label for="matricula">Matrícula</label>
                <input type="text" class="form-control" id="matricula" name="matricula"
                    value="{{ $user->matricula }}" required>
            </div>
            <div class="form-group">
                <label for="setor">Setor</label>
                <input type="text" class="form-control" id="setor" name="setor" value="{{ $user->setor }}"
                    required>
            </div>
            <div class="form-group">
                <label for="cargo">Cargo</label>
                <input type="text" class="form-control" id="cargo" name="cargo" value="{{ $user->cargo }}"
                    required>
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
