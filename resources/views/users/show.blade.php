{{-- resources/views/users/show.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Usuário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1>Detalhes do Usuário</h1>

        <div class="card">
            <div class="card-header">
                Informações do Usuário
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $user->name }}</h5>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Login:</strong> {{ $user->login }}</p>
                <p><strong>Matrícula:</strong> {{ $user->matricula }}</p>
                <p><strong>Setor:</strong> {{ $user->setor }}</p>
                <p><strong>Cargo:</strong> {{ $user->cargo }}</p>
                <p><strong>Servidor ID:</strong> {{ $user->ServidorId }}</p>
                <p><strong>Criado em:</strong> {{ $user->createdDtm }}</p>
                <p><strong>Atualizado em:</strong> {{ $user->updatedDtm }}</p>
                <p><strong>Deletado:</strong> {{ $user->isDeleted ? 'Sim' : 'Não' }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-primary">Voltar para a lista</a>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
