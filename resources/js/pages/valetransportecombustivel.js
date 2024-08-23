$(document).ready(function () {
    $('.matricula').mask('0000000A', {
        translation: {
            'A': {
                pattern: /[0-9X]/, // Allow only numbers or the letter 'X'
                optional: false
            }
        }
    });
    // Inicializar o DataTable
    var table = $('#registrosTable').DataTable({
        ordering: true, // Habilita a ordenação
        order: [[0, 'asc']], // Ordena pela primeira coluna (Nome) em ordem ascendente
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        }
    });


    $('.edit-btn').on('click', function () {
        var id = $(this).data('id');
        $.get(`/valetransportecombustivel/${id}`, function (data) {
            var registro = data.registro; // Dados do registro
            var linhasDisponiveis = data.linhasDisponiveis; // Dados das linhas disponíveis

            //console.log(registro);
            //console.log(registro.Linha); // Verifique o valor

            //console.log(linhasDisponiveis);
            // Acesse os dados de 'registro'
            $('#edit-Nome').text(registro.Nome);
            $('#edit-Matricula').val(registro.Matricula);
            $('#edit-id').val(registro.Id);
            $('#edit-CpfM').val(registro.CpfM);
            $('#edit-Cartao').val(registro.Cartao);
            $('#edit-Linha').val(registro.Linha);
            $('#edit-LinhaDescricao').val(registro.LinhaDescricao);
            $('#edit-Valor').val(registro.Valor);
            $('#edit-Quantidade').val(registro.Quantidade);
            $('#edit-QuantidadeExtra').val(registro.QuantidadeExtra);
            $('#edit-LiberaConsulta').val(registro.LiberaConsulta);
            $('#edit-ValorTotal').val(registro.ValorTotal);

            // Preenche o select de LinhasDisponiveis
            var selectLinhas = $('#edit-Linha');
            selectLinhas.empty(); // Limpa o select
            $.each(linhasDisponiveis, function (index, linha) {
                selectLinhas.append(`<option value="${linha.Id}" edit-data-valor="${linha.Valor}" ${linha.Codigo == registro.Linha ? 'selected' : ''}>${linha.Codigo} - ${linha.Descricao} (${linha.Valor})</option>`);
            });

            // Exibe o modal de edição
            $('#editModal').modal('show');
        });
    });

    // Set autofocus on add-Matricula input
    $('#add-Matricula').trigger("focus");

    // Open add modal
    $('#addModal').on('show.bs.modal', function () {
        // Clear the fields for new entry
        $('#add-Matricula').val('');
        $('#add-CpfM').val('');
        $('#add-Cartao').val('');
        $('#add-Linha').val('');
        $('#add-LinhaDescricao').val('');
        $('#add-Valor').val('');
        $('#add-Quantidade').val('');
        $('#add-QuantidadeExtra').val('');

        $('#add-ValorTotal').val('');

        $('#add-Tipo').val('Combustível'); // Default option

    });


    setTimeout(function () {
        document.getElementById("spinner").style.display = "none";
    }, 4000);
});



let registroId; // Variável global para armazenar o ID do registro a ser excluído

// Evento de clique no botão de exclusão
$('.delete-btn').on('click', function () {
    registroId = $(this).data('id'); // Pega o ID do registro
    const mesAno = $(this).data('mesano'); // Pega o valor de mesAno
    $('#mesAnoDelete').text(mesAno); // Atualiza o texto no modal

    const nome = $(this).data('nome'); // Pega o valor de nome
    $('#nomeDelete').text(nome); // Atualiza o texto no modal
    // Exibe o modal
    $('#deleteModal').modal('show');
});

// Evento de clique no botão de confirmação de exclusão
$('#confirmDelete').on('click', function () {
    $.ajax({
        url: `/valetransportecombustivel/${registroId}`,
        method: 'DELETE',
        success: function (response) {
            Swal.fire({
                title: 'Sucesso!',
                text: response.message || 'Registro excluído com sucesso!',
                icon: 'success',
                confirmButtonText: 'Fechar'
            }).then(() => {
                location.reload(); // Recarrega a página para atualizar a lista
            });
        },
        error: function (xhr) {
            Swal.fire({
                title: 'Erro!',
                text: xhr.responseJSON.message || 'Erro ao excluir o registro.',
                icon: 'error',
                confirmButtonText: 'Fechar'
            });
        }
    });

    // Fecha o modal
    $('#deleteModal').modal('hide');
});

