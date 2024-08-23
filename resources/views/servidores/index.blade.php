{{-- resources/views/users/index.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Lista de Usuários vw_servidores</title>
</head>

<body>
    <div class="container mt-5">
        <h1>Lista de Usuários vw_servidores</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Adicionar Usuário</a>

        <table class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 30%;">Email</th>
                    <th style="width: 25%;">Nome</th>
                    <th style="width: 15%;">Login</th>
                    <th style="width: 10%;">Cargo</th>
                    <th style="width: 10%;">Setor</th>
                    <th style="width: 10%;">Matrícula</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->ServidorId }}</td>
                        <td>{{ $user->Email }}</td>
                        <td>{{ $user->Nome }}</td>
                        <td>{{ $user->Login }}</td>
                        <td>{{ $user->Cargo }}</td>
                        <td>{{ $user->Setor }}</td>
                        <td>{{ $user->Matricula }}</td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
