<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vale Transporte Combustível</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" />
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.7/css/buttons.bootstrap5.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <h1 class="my-4">Registros de Vale {{ $tipoNome }} {{ $mesAno }}</h1>
        <!-- Botão para abrir o modal de adição -->
        <button type="button" class="btn btn-outline-primary" id="btnCreate"
            data-bs-toggle="modal" data-bs-target="#addModal" title="Adicionar Registro">
            <i class="bi bi-floppy2"></i> Adicionar Novo Registro
        </button>
        <div id="responseMessage">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
        </div>
        @if($registros->isEmpty())
        <p>Nenhum registro encontrado.</p>
        @else
        <table id="registrosTable" class="table table-striped table-bordered nowrap" style="width:100%">
            <thead>
                <tr>
                    <!--<th>Id</th> -->
                    <!-- <th>MesAno</th> -->
                    <!-- <th>Data</th> -->
                    <!-- <th>EmpregadoId</th> -->
                    <th>Nome</th>
                    <th>Matricula</th>
                    <!-- <th>Cpf</th> -->
                    <!-- <th>Ação</th> <!-- Action column -->
                    <th>Cpf</th>
                    <th>Cartao</th>
                    <!--<th>Linha</th>-->
                    <th>Linha</th>
                    <th>Valor</th>
                    <th>Quantidade</th>
                    <th>QuantidadeExtra</th>
                    <!--<th>LiberaConsulta</th>-->
                    <th>ValorTotal</th>
                    <th>InclusaoManual</th>
                    <!-- <th>Tipo</th> -->
                    <!-- <th>Fechada</th> -->
                    <!-- Adicione outras colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $registro)
                <tr>
                    <!-- <td>{{ $registro->Id }}</td> -->
                    <!-- <td>{{ $registro->MesAno }}</td> -->
                    <!-- <td>{{ $registro->Data }}</td> -->
                    <!--<td>{{ $registro->EmpregadoId }}</td> -->
                    <td><button type="button" class="btn btn-outline-warning edit-btn" data-id="{{ $registro->Id }}" title="Botão de edição">
                            <i class="bi bi-pencil"></i>
                        </button>{{ $registro->No0144444444444444444444me }}</td>
                    <td>{{ $registro->Matricula }}</td>
                    <!--<td>{{ $registro->Cpf }}</td> -->

                    <td>{{ $registro->CpfM }}</td>
                    <td>{{ $registro->Cartao }}</td>

                    <td>{{ $registro->Linha."-".$registro->LinhaDescricao }}</td>
                    <td>{{ $registro->Valor }}</td>
                    <td>{{ $registro->Quantidade }}</td>
                    <td>{{ $registro->QuantidadeExtra }}</td>
                    <!--  <td>{{ $registro->LiberaConsulta }}</td> -->
                    <td>{{ $registro->ValorTotal }}</td>
                    <td>{{ $registro->InclusaoManual }}</td>
                    <!--<td>{{ $registro->Tipo }}</td> -->
                    <!-- <td>{{ $registro->Fechada }}</td> -->

                    <!-- Adicione outras colunas conforme necessário -->
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Modal for adding a new record -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Adicionar Novo Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" action="{{ route('valetransportecombustivel.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <div class="p-2 border bg-light">
                                    <strong>Nome:</strong>
                                    <div id="add-Nome" class="form-control-plaintext">Nome do Usuário</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-2 border bg-light">
                                    <strong>Matrícula:</strong>
                                    <div id="add-Matricula" class="form-control-plaintext">Matrícula do Usuário</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="add-CpfM">CpfM</label>
                                <input type="text" class="form-control" id="add-CpfM" name="CpfM" required>
                            </div>
                            <div class="col">
                                <label for="add-Cartao">Cartão</label>
                                <input type="text" class="form-control" id="add-Cartao" name="Cartao" required>
                            </div>
                            <div class="col">
                                <label for="add-Linha">Linha</label>
                                <input type="text" class="form-control" id="add-Linha" name="Linha" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="add-LinhaDescricao">Linha Descrição</label>
                                <input type="text" class="form-control" id="add-LinhaDescricao" name="LinhaDescricao" required>
                            </div>
                            <div class="col">
                                <label for="add-Valor">Valor</label>
                                <input type="number" class="form-control" id="add-Valor" name="Valor" required>
                            </div>
                            <div class="col">
                                <label for="add-Quantidade">Quantidade</label>
                                <input type="number" class="form-control" id="add-Quantidade" name="Quantidade" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="add-QuantidadeExtra">Quantidade Extra</label>
                                <input type="number" class="form-control" id="add-QuantidadeExtra" name="QuantidadeExtra" required>
                            </div>
                            <div class="col">
                                <label for="add-LiberaConsulta">Libera Consulta</label>
                                <select class="form-control" id="add-LiberaConsulta" name="LiberaConsulta" required>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="add-ValorTotal">Valor Total</label>
                                <input type="number" class="form-control" id="add-ValorTotal" name="ValorTotal" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="add-InclusaoManual">Inclusão Manual</label>
                                <select class="form-control" id="add-InclusaoManual" name="InclusaoManual" required>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="add-Tipo">Tipo</label>
                                <select class="form-control" id="add-Tipo" name="Tipo" required>
                                    <option value="Combustível">Combustível</option>
                                    <option value="Transporte">Transporte</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="add-Fechada">Fechada</label>
                                <select class="form-control" id="add-Fechada" name="Fechada" required>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="addForm" class="btn btn-primary"><i class="bi bi-floppy2"></i> Salvar Registro</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-square"></i> Cancelar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for editing -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- Aumentando a largura da modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="{{ route('valetransportecombustivel.update') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="edit-id" name="id">

                        <div class="row mb-3">
                            <div class="col">
                                <div class="p-2 border bg-light"> <!-- Destacando o conteúdo -->
                                    <strong>Nome:</strong>
                                    <div id="edit-Nome" name="Nome" class="form-control-plaintext">Nome do Usuário</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-2 border bg-light"> <!-- Destacando o conteúdo -->
                                    <strong>Matrícula:</strong>
                                    <div id="edit-Matricula" name="Matriula" class="form-control-plaintext">Matrícula do Usuário</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit-CpfM">CpfM</label>
                                <input type="text" class="form-control" id="edit-CpfM" name="CpfM">
                            </div>
                            <div class="col">
                                <label for="edit-Cartao">Cartão</label>
                                <input type="text" class="form-control" id="edit-Cartao" name="Cartao">
                            </div>
                            <div class="col">
                                <label for="edit-Linha">Linha</label>
                                <input type="text" class="form-control" id="edit-Linha" name="Linha">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit-LinhaDescricao">Linha Descrição</label>
                                <input type="text" class="form-control" id="edit-LinhaDescricao" name="LinhaDescricao">
                            </div>
                            <div class="col">
                                <label for="edit-Valor">Valor</label>
                                <input type="number" class="form-control" id="edit-Valor" name="Valor">
                            </div>
                            <div class="col">
                                <label for="edit-Quantidade">Quantidade</label>
                                <input type="number" class="form-control" id="edit-Quantidade" name="Quantidade">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit-QuantidadeExtra">Quantidade Extra</label>
                                <input type="number" class="form-control" id="edit-QuantidadeExtra" name="QuantidadeExtra">
                            </div>
                            <div class="col">
                                <label for="edit-LiberaConsulta">Libera Consulta</label>
                                <select class="form-control" id="edit-LiberaConsulta" name="LiberaConsulta">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="edit-ValorTotal">Valor Total</label>
                                <input type="number" class="form-control" id="edit-ValorTotal" name="ValorTotal">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit-InclusaoManual">Inclusão Manual</label>
                                <select class="form-control" id="edit-InclusaoManual" name="InclusaoManual">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="edit-Tipo">Tipo</label>
                                <select class="form-control" id="edit-Tipo" name="Tipo">
                                    <option value="Combustível">Combustível</option>
                                    <option value="Transporte">Transporte</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="edit-Fechada">Fechada</label>
                                <select class="form-control" id="edit-Fechada" name="Fechada">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="editForm" class="btn btn-primary">Salvar alterações</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Responsive JS -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.7/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.7/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.7/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.7/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#registrosTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json"
                }
            });

            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                $.get(`/valetransportecombustivel/${id}`, function(data) {
                    $('#edit-id').val(data.Id);
                    $('#edit-Nome').val(data.Nome);
                    $('#edit-Matricula').val(data.Matricula);
                    $('#edit-CpfM').val(data.CpfM);
                    $('#edit-Cartao').val(data.Cartao);
                    $('#edit-Linha').val(data.Linha);
                    $('#edit-LinhaDescricao').val(data.LinhaDescricao);
                    $('#edit-Valor').val(data.Valor);
                    $('#edit-Quantidade').val(data.Quantidade);
                    $('#edit-QuantidadeExtra').val(data.QuantidadeExtra);
                    $('#edit-LiberaConsulta').val(data.LiberaConsulta);
                    $('#edit-ValorTotal').val(data.ValorTotal);
                    $('#edit-InclusaoManual').val(data.InclusaoManual);
                    $('#edit-Tipo').val(data.Tipo);
                    $('#edit-Fechada').val(data.Fechada);
                    $('#editModal').modal('show');
                });
            });

             // Open add modal
             $('#addModal').on('show.bs.modal', function() {
                // Clear the fields for new entry
                $('#add-CpfM').val('');
                $('#add-Cartao').val('');
                $('#add-Linha').val('');
                $('#add-LinhaDescricao').val('');
                $('#add-Valor').val('');
                $('#add-Quantidade').val('');
                $('#add-QuantidadeExtra').val('');
                $('#add-LiberaConsulta').val('1'); // Default to "Sim"
                $('#add-ValorTotal').val('');
                $('#add-InclusaoManual').val('1'); // Default to "Sim"
                $('#add-Tipo').val('Combustível'); // Default option
                $('#add-Fechada').val('0'); // Default to "Não"
            });
        });
    </script>
</body>

</html>
