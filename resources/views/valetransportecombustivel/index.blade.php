<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vale {{ $tipoNome }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" />

     <!-- DataTables Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css" />
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.7/css/buttons.bootstrap5.min.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>



    <!-- Estilo CSS para ocultar o div "servicos" inicialmente -->
    <style>
        #servicos {
            display: none; /* Oculta o div servicos inicialmente */
        }
        /* Esconde o input file original */
        input[type="file"] {
            display: none;
        }
        .spinner-border {
            width: 2rem;
            height: 2rem;
            vertical-align: middle;
            margin-left: 10px;
        }

        .d-none {
            display: none;
        }
    </style>

</head>
<body>

    <header style="padding-bottom: 3%;">
        <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">SISMOBILIDADE - Sistema de controle do vale transporte e combustível</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                {{-- <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">INÍCIO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">EXPORTAR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">IMPORTAR</a>
                    </li>
                    </ul>
                    <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">procurar</button>
                    </form>
                </div> --}}
            </div>
        </nav>
    </header>

    <!-- Begin page content -->
    <main class="flex-shrink-0">
        <div class="container" id="containerPrincipal">
            <div class="row">
                <div class="col-md-12">
                    <!-- Formulário de Pesquisa -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pesquisar Por Período e Tipo ({{ $tipoNome }} {{ $mesAno }})</h5>
                            <button type="button" class="btn-close btn-close-white" aria-label="Fechar"
                                onclick="this.closest('.card').style.display='none';"></button>
                        </div>
                        <div class="card-body">
                            <form id="searchForm" class="row g-3">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="mesAno" class="form-label">Selecione Mês e Ano</label>
                                        <input type="month" id="mesAno" class="form-control" name="mesANo" value="{{ sprintf('%04d-%02d', $ano, $mes) }}" required autofocus>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tipo</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo" id="combustivel" value="C" {{ $tipo === 'C' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="combustivel">COMBUSTÍVEL</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipo" id="transporte" value="T" {{ $tipo === 'T' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="transporte">TRANSPORTE</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 align-self-end">
                                        <button type="submit" class="btn btn-outline-primary" title="Buscar Vale">
                                            <i class="bi bi-search"></i> Buscar Período Tipo <i class="bi bi-card-list"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- Fim Formulário de Pesquisa -->
                        </div>
                    </div>
                </div>
            </div>

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
                <p>Nenhum registro foi encontrado no periodo {{ $mesAno }} , importe a planilha abaixo.</p>
                <button id="openServicos" class="btn btn-outline-success btn-lg" onclick="mostrarServicos()">
                    <i class="bi bi-folder2-open"></i> ABRIR
                </button>
            @else
                <div id="conteudo" class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Painel Listagem de Registros (<span style="font-size: 18px;color:gray;">Obs.:Quando finalizar a gestão de usuários, clique no X ao lado para continuar.</span>)</h4>
                    <button type="button" class="btn btn-outline-primary" id="btnCreate"
                        data-bs-toggle="modal" data-bs-target="#addModal" title="Cadastrar Registro">
                        <i class="bi bi-floppy2"></i> Adicionar Novo Registro <i class="bi bi-fuel-pump"></i>&nbsp;<i class="bi bi-credit-card-2-front"></i>
                    </button>
                    <div id="spinner">
                        <div class="lds-ripple">Processando...<div></div><div></div></div>
                    </div>
                    <table id="registrosTable" class="table table-striped table-bordered nowrap display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Matricula</th>
                                <th>Cpf</th>
                                <th>Cartao</th>
                                <th>Linha</th>
                                <th>Valor</th>
                                <th>Quantidade</th>
                                <th>QuantidadeExtra</th>
                                <th>ValorTotal</th>
                                <th>InclusaoManual</th>
                                <th>Periodo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros as $registro)
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-outline-warning edit-btn"
                                            data-id="{{ $registro->Id }}" title="Botão de edição">
                                            <i class="bi bi-pencil"></i>
                                        </button><button type="button" class="btn btn-outline-danger delete-btn"
                                                data-id="{{ $registro->Id }}" data-mesAno="{{ $registro->MesAno }}" data-Nome="{{ $registro->Nome }}" title="Botão de exclusão">
                                            <i class="bi bi-trash"></i>
                                        </button>&nbsp;{{ $registro->Nome }}
                                    </td>
                                    <td>{{ $registro->Matricula }}</td>
                                    <td>{{ $registro->CpfM }}</td>
                                    <td>{{ $registro->Cartao }}</td>
                                    <td>{{ $registro->Linha."-".$registro->LinhaDescricao }}</td>
                                    <td>{{ $registro->Valor }}</td>
                                    <td>{{ $registro->Quantidade }}</td>
                                    <td>{{ $registro->QuantidadeExtra }}</td>
                                    <td>{{ $registro->ValorTotal }}</td>
                                    <td>{{ $registro->InclusaoManual }}</td>
                                    <td>{{ $registro->MesAno }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button id="closetab1" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            @endif
        </div>
    </main>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="servicos" class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="alert-heading">Painel de Serviços {{ $mesAno }}</h4>
                        </div>
                        <div class="card-body">

                            @if (isset($mensagem))
                                <!-- Div de alerta para mostrar a mensagem recebida da controller -->
                                <div
                                    class="alert {{ $success ? 'alert-success' : 'alert-danger' }} alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ $mensagem }}
                                </div>
                            @endif

                            <!-- Inclusão do SweetAlert2 para exibir as mensagens -->
                            @if (session('swal_success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: '{{ session('swal_success') }}',
                                    background: '#d4edda',
                                    iconHtml: '<i class="bi bi-check-circle-fill"></i>'
                                });
                            </script>
                            @endif

                            @if (session('swal_error'))
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: '{{ session('swal_error') }}',
                                    background: '#f8d7da',
                                    iconHtml: '<i class="bi bi-x-circle-fill"></i>'
                                });
                            </script>
                            @endif

                            <div class="row">
                                <!-- Importar Arquivo Transporte -->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card card-warning card-outline h-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <h4>Importar Planilha TRANSPORTE <i class="bi bi-credit-card-2-front"></i></h4>
                                                <p>Importe um arquivo Excel com os dados de transporte.</p>

                                                <form id="formTransporte" action="{{ route('importar.arquivo') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="tipo" value="1"> <!-- 1 para transporte -->
                                                    <input type="text" name="periodo" value="{{ $mesAno }}" class="form-control">
                                                    <div>
                                                        <label for="formFileTransporte" class="form-label">Escolha o excel</label>
                                                        <input class="form-control form-control-lg" id="formFileTransporte" type="file" name="arquivo" accept=".xls, .xlsx" required>
                                                        <label id="fileLabelTransporte" for="formFileTransporte" class="btn btn-outline-success btn-xl"><i class="bi bi-file-earmark-excel"></i></label>
                                                        <div id="spinnerTransporte" class="spinner-border text-primary d-none" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                    <button type="submit" name="submitTransporte" class="btn btn-warning btn-block w-100 mt-3">IMPORTAR&nbsp;<i class="bi bi-cloud-arrow-up"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Importar Arquivo Combustível -->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card card-warning card-outline h-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <h4>Importar Planilha COMBUSTÍVEL <i class="bi bi-fuel-pump"></i></h4>
                                                <p>Importe um arquivo Excel com os dados de combustível.</p>

                                                <form id="formCombustivel" action="{{ route('importar.arquivo') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="tipo" value="2"> <!-- 2 para combustível -->
                                                    <input type="hidden" name="periodo" value="{{ $mesAno }}" class="form-control">
                                                    <div>
                                                        <label for="formFileCombustivel" class="form-label">Escolha o excel</label>
                                                        <input class="form-control form-control-lg" id="formFileCombustivel" type="file" name="arquivo" accept=".xls, .xlsx" required>
                                                        <label id="fileLabelCombustivel" for="formFileCombustivel" class="btn btn-outline-success btn-xl"><i class="bi bi-file-earmark-excel"></i></label>
                                                        <div id="spinnerCombustivel" class="spinner-border text-primary d-none" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                    <button type="submit" name="submit" class="btn btn-warning btn-block w-100 mt-3">IMPORTAR&nbsp;<i class="bi bi-cloud-arrow-up"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 3 - Exportar transporte (xml) -->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card card-success card-outline h-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <h4>Exportar Planilha TRANSPORTE <i class="bi bi-credit-card-2-front"></i></h4>
                                                <p>Exporte os dados de transporte em formato XML.</p>
                                            </div>
                                            <!-- Adicione um ID ao botão para vincular ao evento de clique no JavaScript -->
                                            <a href="javascript:void(0);" id="exportXml" class="btn btn-success mt-3">EXPORTAR&nbsp;<i class="bi bi-cloud-arrow-down"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 4 - Exportar combustivel (excel) -->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="card card-success card-outline h-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <h4>Exportar Planilha COMBUSTÍVEL <i class="bi bi-fuel-pump"></i></h4>
                                                <p>Exporte os dados de combustível em formato Excel.</p>
                                            </div>
                                            <a href="{{ route('exportar.combustivel', ['mesAno' => $mesAno]) }}" class="btn btn-success mt-3">EXPORTAR&nbsp;<i class="bi bi-cloud-arrow-down"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.row -->
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                    <button id="closetab2" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <center><span class="text-muted text-center">NOVACAP {{ date('Y') }} - (Desenvolvido pela DEINF)</span></center>
        </div>
    </footer>



    <!-- Modal for adding a new record -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog"
        aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Adicionar Novo Registro ({{ $mesAno }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="spinner-grow" role="status" id="spinner-add" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <form id="addForm" action="{{ route('valetransportecombustivel.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-3">
                                <label for="add-Matricula">Procurar dados pela Matrícula</label>
                                <input type="text" class="form-control matricula" id="add-Matricula" name="Matricula"
                                    required onchange="buscarPorMatricula(this.value)" maxlength="8" autofocus>
                            </div>
                            <div class="col-6">
                                <label for="add-Nome">Nome</label>
                                <input type="text" class="form-control" id="add-Nome" name="Nome" disabled>
                            </div>
                            <div class="col-3">
                                <label for="add-CpfM">CpfM</label>
                                <input type="text" class="form-control" id="add-CpfM" name="CpfM" disabled>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="add-Tipo">Tipo</label>
                                <select class="form-control" id="add-Tipo" name="Tipo" required>
                                    <option value="Combustível">Combustível</option>
                                    <option value="Transporte">Transporte</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="add-Cartao">Cartão</label>
                                <input type="number" class="form-control" id="add-Cartao" name="Cartao">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col">
                                <label for="select-Linha">Linha</label>
                                <select class="form-control" id="select-Linha" name="Linha" required>
                                    <option value="">Selecione uma linha</option>
                                    @foreach ($linhas as $linha)
                                        <option value="{{ $linha->Id }}" data-valor="{{ $linha->Valor }}">
                                            {{ $linha->Codigo }} - {{ $linha->Descricao }} - {{ $linha->Valor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2">
                                <label for="add-Quantidade">Quantidade</label>
                                <input type="number" class="form-control" id="add-Quantidade" name="Quantidade" required>
                            </div>
                            <div class="col-2">
                                <label for="add-QuantidadeExtra">Extra</label>
                                <input type="number" class="form-control" id="add-QuantidadeExtra" name="QuantidadeExtra" required>
                            </div>
                            <div class="col-2">
                                <label for="add-Valor">Valor</label>
                                <input type="number" class="form-control" id="add-Valor" name="Valor" disabled>
                            </div>
                            <div class="col-2">
                                <label for="add-ValorTotal">Valor Total</label>
                                <input type="number" class="form-control" id="add-ValorTotal" name="ValorTotal" disabled>
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar ({{ $tipoNome }} {{ $mesAno }})  <div id="edit-Nome" class="form-control-plaintext"></div></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="{{ route('valetransportecombustivel.update') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="edit-id" name="id">

                        <div class="row mb-3">
                            <div class="col">
                                <label for="edit-Cartao">Cartão</label>
                                <input type="number" class="form-control" id="edit-Cartao" name="Cartao">
                            </div>
                            <div class="col">
                                <label for="edit-CpfM">CPF</label>
                                <input type="text" class="form-control" id="edit-CpfM" name="CpfM" disabled>
                            </div>
                            <div class="col">
                                <label for="edit-Matricula">Matricula</label>
                                <input type="text" class="form-control" id="edit-Matricula" name="Matricula" disabled>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col">
                                <label for="edit-Linha">Linha</label>
                                <select id="edit-Linha" class="form-control" name="LinhaId">
                                    <option value="">Selecione uma Linha</option>
                                    <!-- Outras opções serão inseridas dinamicamente aqui -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2">
                                <label for="edit-Quantidade">Quantidade</label>
                                <input type="number" class="form-control" id="edit-Quantidade" name="Quantidade" required>
                            </div>
                            <div class="col-2">
                                <label for="edit-QuantidadeExtra">Extra</label>
                                <input type="number" class="form-control" id="edit-QuantidadeExtra" name="QuantidadeExtra" required>
                            </div>
                            <div class="col-2">
                                <label for="edit-Valor">Valor</label>
                                <input type="number" class="form-control" id="edit-Valor" name="Valor" disabled>
                            </div>
                            <div class="col-2">
                                <label for="edit-ValorTotal">Valor Total</label>
                                <input type="number" class="form-control" id="edit-ValorTotal" name="ValorTotal" disabled>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="editForm" class="btn btn-success"><i class="bi bi-floppy2"></i> Salvar alterações</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-square"></i> Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão ({{ $tipoNome }} {{ $mesAno }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Deseja realmente excluir
                        <b><span style="color: red;" id="nomeDelete"></span></b> de receber vale no mês
                        <b><span style="color: red;" id="mesAnoDelete"></span></b>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-square"></i> Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <!-- JSZip and PDFMake -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
        document.getElementById('select-Linha').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const linhaValor = selectedOption.getAttribute('data-valor');

            const valorInput = document.getElementById('add-Valor');
            const valorTotalInput = document.getElementById('add-ValorTotal');
            const quantidadeInput = document.getElementById('add-Quantidade');

            if (valorInput && valorTotalInput) {
                valorInput.value = linhaValor || 0;

                const quantidade = parseFloat(quantidadeInput.value) || 0;
                valorTotalInput.value = (quantidade * linhaValor).toFixed(2);
            }
        });

        document.getElementById('add-Quantidade').addEventListener('change', function() {
            const valorInput = parseFloat(document.getElementById('add-Valor').value) || 0;
            const quantidade = parseFloat(this.value) || 0;
            const quantidadeExtra = parseFloat(document.getElementById('add-QuantidadeExtra').value) || 0;

            const valorTotalInput = document.getElementById('add-ValorTotal');

            if (valorTotalInput) {
                const valorTotal = (quantidade + quantidadeExtra) * valorInput;
                valorTotalInput.value = valorTotal.toFixed(2);
            }
        });

        document.getElementById('add-QuantidadeExtra').addEventListener('change', function() {
            const valorInput = parseFloat(document.getElementById('add-Valor').value) || 0;
            const quantidade = parseFloat(document.getElementById('add-Quantidade').value) || 0;
            const quantidadeExtra = parseFloat(this.value) || 0;

            const valorTotalInput = document.getElementById('add-ValorTotal');

            if (valorTotalInput) {
                const valorTotal = (quantidade + quantidadeExtra) * valorInput;
                valorTotalInput.value = valorTotal.toFixed(2);
            }
        });

        document.getElementById('edit-Linha').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const linhaValor = selectedOption.getAttribute('edit-data-valor');

            const valorInput = document.getElementById('edit-Valor');
            const valorTotalInput = document.getElementById('edit-ValorTotal');
            const quantidadeInput = document.getElementById('edit-Quantidade');

            if (valorInput && valorTotalInput) {
                valorInput.value = linhaValor || 0;

                const quantidade = parseFloat(quantidadeInput.value) || 0;
                valorTotalInput.value = (quantidade * linhaValor).toFixed(2);
            }
        });

        document.getElementById('edit-Quantidade').addEventListener('change', function() {
            const valorInput = parseFloat(document.getElementById('edit-Valor').value) || 0;
            const quantidade = parseFloat(this.value) || 0;
            const quantidadeExtra = parseFloat(document.getElementById('edit-QuantidadeExtra').value) || 0;

            const valorTotalInput = document.getElementById('edit-ValorTotal');

            if (valorTotalInput) {
                const valorTotal = (quantidade + quantidadeExtra) * valorInput;
                valorTotalInput.value = valorTotal.toFixed(2);
            }
        });

        document.getElementById('edit-QuantidadeExtra').addEventListener('change', function() {
            const valorInput = parseFloat(document.getElementById('edit-Valor').value) || 0;
            const quantidade = parseFloat(document.getElementById('edit-Quantidade').value) || 0;
            const quantidadeExtra = parseFloat(this.value) || 0;

            const valorTotalInput = document.getElementById('edit-ValorTotal');

            if (valorTotalInput) {
                const valorTotal = (quantidade + quantidadeExtra) * valorInput;
                valorTotalInput.value = valorTotal.toFixed(2);
            }
        });

        function buscarPorMatricula(matricula) {
            const spinner = document.getElementById("spinner-add");
            if (matricula) {
                // Mostra o spinner
                spinner.style.display = "block";

                $.ajax({
                    url: `/valetransportecombustivel/servidor/${matricula}`,
                    method: 'GET',
                    success: function(data) {
                        // Supondo que o retorno seja um objeto JSON com os dados
                        $('#add-Nome').val(data.Nome);
                        $('#add-CpfM').val(data.Cpf);
                        $('#add-Cartao').val(data.Cartao);
                        $('#add-Matricula').val(data.Matricula);
                    },
                    error: function(xhr) {
                        // Usando SweetAlert2 para exibir a mensagem de erro
                        Swal.fire({
                            title: 'Erro!',
                            text: xhr.responseJSON.message || 'Erro ao buscar dados.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        });
                    },
                    complete: function() {
                        // Esconde o spinner após a requisição
                        spinner.style.display = "none";
                    }
                });
            }
        }

        function buscarPorLinha(linha) {
            const spinner = document.getElementById("spinner-add");
            if (linha) {
                spinner.style.display = "block";

                $.ajax({
                    url: `/valetransportecombustivel/linha/${linha}`,
                    method: 'GET',
                    success: function(data) {
                        $('#add-Linha').val(data.Codigo);
                        $('#add-LinhaDescricao').val(data.Descricao);
                        $('#add-Valor').val(data.Valor);
                        $('#add-ValorTotal').val((parseFloat(data.Valor) * parseFloat($('#add-Quantidade').val())).toFixed(2));
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Erro!',
                            text: xhr.responseJSON.message || 'Erro ao buscar linha.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        });
                    },
                    complete: function() {
                        spinner.style.display = "none";
                    }
                });
            }
        }

        $(document).ready(function() {
            $('#searchForm').on('submit', function(event) {
                event.preventDefault(); // Previne o envio padrão do formulário

                var mesAno = $('#mesAno').val();
                var tipo = $('input[name="tipo"]:checked').val();

                if (mesAno && tipo) {
                    var [ano, mes] = mesAno.split('-'); // Divide o valor do input month em ano e mês
                    var url = `/valetransportecombustivel/${mes}/${ano}/${tipo}`;

                    // Redireciona para a rota correta
                    window.location.href = url;
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Por favor, selecione o mês/ano e o tipo.',
                        icon: 'error',
                        confirmButtonText: 'Fechar'
                    });

                }
            });
        });
    </script>

    <!-- Script JavaScript para mostrar o div "servicos" -->
    <script>
        document.getElementById('closetab1').addEventListener('click', function() {
            document.getElementById('servicos').style.display = 'block'; // Mostra o div "servicos"
        });
        document.getElementById('closetab2').addEventListener('click', function() {
            console.log("abriu");
            location.reload();
        });
    </script>

    <script>
        function mostrarServicos() {
            document.getElementById('servicos').style.display = 'block'; // Mostra o div "servicos"
            document.getElementById('containerPrincipal').style.display = 'none'; // Mostra o div "servicos"containerPrincipal
            console.log("Serviços abertos"); // Para verificar se a função foi chamada
        }
    </script>

    <script>
        document.getElementById('formFileTransporte').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : 'Clique aqui para escolher';
            var spinner = document.getElementById('spinnerTransporte');
            var label = document.getElementById('fileLabelTransporte');

            // Mostrar o spinner
            spinner.classList.remove('d-none');

            // Simular o tempo de carregamento
            setTimeout(function() {
                // Esconder o spinner
                spinner.classList.add('d-none');
                // Atualizar o label
                label.textContent = fileName;
            }, 2000); // Ajuste o tempo conforme necessário
        });

        document.getElementById('formFileCombustivel').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : 'Clique aqui para escolher';
            var spinner = document.getElementById('spinnerCombustivel');
            var label = document.getElementById('fileLabelCombustivel');

            // Mostrar o spinner
            spinner.classList.remove('d-none');

            // Simular o tempo de carregamento
            setTimeout(function() {
                // Esconder o spinner
                spinner.classList.add('d-none');
                // Atualizar o label
                label.textContent = fileName;
            }, 2000); // Ajuste o tempo conforme necessário
        });

        // Mostrar o Spinner durante o Submit
        document.getElementById('formTransporte').addEventListener('submit', function () {
            document.getElementById('spinnerTransporte').classList.remove('d-none');
        });

        document.getElementById('formCombustivel').addEventListener('submit', function () {
            document.getElementById('spinnerCombustivel').classList.remove('d-none');
        });
    </script>

    <script>
        document.getElementById('exportXml').addEventListener('click', function () {
            var mesAno = '{{ $mesAno }}'; // Pegando o valor de mesAno

            $.ajax({
                url: "{{ route('exportar.transporte') }}",
                method: 'GET',
                data: {
                    mesAno: mesAno
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Fechar'
                        }).then(() => {
                            window.location.href = response.download_url; // Redireciona para o download do XML
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Erro!',
                        text: xhr.responseJSON.message || 'Erro ao gerar o arquivo XML.',
                        icon: 'error',
                        confirmButtonText: 'Fechar'
                    });
                }
            });
        });
    </script>

</body>
</html>
