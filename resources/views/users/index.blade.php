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

    <title>Lista de Usuários</title>
</head>

<body>
    <div class="container mt-5">
        <h1>Lista de Usuários</h1>

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
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->userId }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->login }}</td>
                        <td>{{ $user->cargo }}</td>
                        <td>{{ $user->setor }}</td>
                        <td>{{ $user->matricula }}</td>
                        <td>{{ $user->isDeleted ? 'Inativo' : 'Ativo' }}</td>
                        <td class="text-end">
                            <div class="mb-1">
                                <a href="{{ route('users.show', $user->userId) }}"
                                    class="btn btn-info btn-block">Ver</a>
                            </div>
                            <div class="mb-1">
                                <a href="{{ route('users.edit', $user->userId) }}"
                                    class="btn btn-warning btn-block">Editar</a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger btn-block" data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal{{ $user->userId }}">
                                    Deletar
                                </button>

                                <!-- Modal de confirmação -->
                                <div class="modal fade" id="confirmDeleteModal{{ $user->userId }}" tabindex="-1"
                                    aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Tem certeza que deseja excluir este usuário?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('users.destroy', $user->userId) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
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
