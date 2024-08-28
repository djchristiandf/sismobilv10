<?php

namespace App\Http\Controllers;

use App\Models\ValeTransporteCombustivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ValeTransporteCombustivelExport;
use App\Models\ValeTransporte;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;

class ValeTransporteCombustivelController extends Controller
{
    /**
     * Listar todos os registros da view.
     */
    public function index()
    {
        $userName = Session::get('name');
        $tipoNome = 'COMBUSTÍVEL-TRANSPORTE';

        // Obtém o valor de mesAno a partir da consulta
        $mesAnoResult = DB::connection('gestaorh')->select('SELECT valetransp.maisrecente() AS resultado');

        // Verifica se o resultado não está vazio e obtém o valor
        $mesAno = !empty($mesAnoResult) ? $mesAnoResult[0]->resultado : null;

        // Se $mesAno não for nulo, separa o mês e o ano
        if ($mesAno) {
            list($ano, $mes) = explode('-', $mesAno);
        } else {
            $mes = 0;
            $ano = 0;
        }

        // Busca os registros no banco de dados pelo campo MesAno, limitado a 100 resultados
        $registros = ValeTransporteCombustivel::where('MesAno', $mesAno)->take(1)->get();

        // Chama o método getTodasLinhas() para obter todas as linhas
        $linhas = $this->getTodasLinhas();
        $tipo = null;

        // Retorna a view com os registros encontrados
        return view('valetransportecombustivel.index', compact('registros', 'tipoNome', 'mesAno', 'linhas', 'mes', 'ano', 'tipo'));
    }

    public function getTodasLinhas()
    {
        // Usando a conexão 'gestaorh' para buscar as linhas
        $linhas = DB::connection('gestaorh')->select('SELECT Id, Codigo, Descricao, Valor FROM valetransp.linhas ORDER BY Codigo');

        if (!empty($linhas)) {
            return $linhas; // Retorna todas as linhas
        } else {
            return []; // Retorna um array vazio se não encontrar linhas
        }
    }

    /**
     * Buscar registros por Matricula.
     */
    public function getByMatricula($matricula)
    {
        $registro = ValeTransporteCombustivel::where('Matricula', $matricula)->first();
        return response()->json($registro);
    }

    public function getByMatriculaServidores($matricula)
    {
        // Usando a conexão 'gestaorh'
        $registro = DB::connection('gestaorh')->select('SELECT * FROM valetransp.pesquisamatricula(?)', [$matricula]);

        if (!empty($registro)) {
            return response()->json($registro[0]); // Retorna o primeiro registro
        } else {
            return response()->json(['message' => 'Matricula não localizada'], 404);
        }
    }

    public function getDadosLinha($linha)
    {
        // Usando a conexão 'gestaorh'
        $registro = DB::connection('gestaorh')->select('SELECT * FROM valetransp.pesquisalinha(?)', [$linha]);

        if (!empty($registro)) {
            return response()->json($registro[0]); // Retorna o primeiro registro
        } else {
            return response()->json(['message' => 'Linha não localizada'], 404);
        }
    }


    /**
     * Buscar registros por Nome.
     */
    public function getByNome($nome)
    {
        $registros = ValeTransporteCombustivel::where('Nome', 'LIKE', "%$nome%")->get();
        return view('valetransportecombustivel.index', compact('registros'));
    }

    /**
     * Buscar registros por Cpf.
     */
    public function getByCpf($cpf)
    {
        $registros = ValeTransporteCombustivel::where('Cpf', $cpf)->get();
        return view('valetransportecombustivel.index', compact('registros'));
    }

    /**
     * Função para buscar registros pelo campo MesAno.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getByMesAno($mes, $ano)
    {
        // Recebe os parâmetros mes e ano da requisição
        //$mes = $request->input('mes');
        //$ano = $request->input('ano');

        // Concatena o mes e ano no formato esperado (MM/YYYY)
        $mesAno = $mes . '/' . $ano;
        //dd($mesAno)/
        // Busca os registros no banco de dados pelo campo MesAno, limitado a 100 resultados
        $registros = ValeTransporteCombustivel::where('MesAno', $mesAno)->take(100)->get();

        // Retorna a view com os registros encontrados
        return view('valetransportecombustivel.index', compact('registros'));
    }

    /**
     * Função para buscar registros pelo campo MesAno.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getByMesAnoTipo($mes, $ano, $tipo)
    {
        // Recebe os parâmetros mes e ano da requisição
        //$mes = $request->input('mes');
        //$ano = $request->input('ano');
        // Garantindo que o mês tenha sempre dois dígitos
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

        // Concatena o mes e ano no formato esperado (YYYY-MM)
        $mesAno = $mes . '/' . $ano; // Corrigido para YYYY-MM

        // Verifica o valor de $tipo e converte para a palavra correspondente
        if ($tipo === 'C') {
            $tipoNome = 'COMBUSTÍVEL';
        } elseif ($tipo === 'T') {
            $tipoNome = 'TRANSPORTE';
        } else {
            $tipoNome = 'DESCONHECIDO'; // Caso o tipo seja outro valor inesperado
        }
        //dd($mesAno)/
        // Busca os registros no banco de dados pelo campo MesAno, limitado a 100 resultados
        $registros = ValeTransporteCombustivel::where('MesAno', $mesAno)->where('Tipo', $tipo)->get();

        $mesAno = $ano . '-' . $mes; // Corrigido para YYYY-MM
        // Chama o método getTodasLinhas() para obter todas as linhas
        $linhas = $this->getTodasLinhas();

        // Retorna a view com os registros encontrados
        return view('valetransportecombustivel.index', compact('registros', 'tipoNome', 'mesAno', 'linhas', 'mes', 'ano', 'tipo'));
    }

    public function update(Request $request)
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'id' => 'required',
            'EmpregadoId' => 'required',
            'Cartao' => 'nullable|numeric',
            'LinhaId' => 'required', // Verifica se é um inteiro
            'Quantidade' => 'nullable|integer',
            'QuantidadeExtra' => 'nullable|integer',
        ]);

        // Cast manual dos inputs para garantir que sejam inteiros
        $id = (int) $validatedData['id'];
        $empregadoId = (int) $validatedData['EmpregadoId'];
        $linhaId = (int) $validatedData['LinhaId'];
        $cartao = $validatedData['Cartao']; // Pode ser numérico ou null
        $quantidade = isset($validatedData['Quantidade']) ? (int) $validatedData['Quantidade'] : null; // Garantido como inteiro ou null
        $quantidadeExtra = isset($validatedData['QuantidadeExtra']) ? (int) $validatedData['QuantidadeExtra'] : null; // Garantido como inteiro ou null

        try {
            // Monta o array de atualização
            $updateData = [
                'LinhaId' => $linhaId,
                'Quantidade' => $quantidade,
                'QuantidadeExtra' => $quantidadeExtra,
            ];

            // Atualiza o registro usando DB
            DB::connection('gestaorh')->table('valetransp.valetransporte')->where('Id', $id)->update($updateData);

            // Se Cartao não é nulo, executa a procedure
            if ($cartao !== null) {
                DB::connection('gestaorh')->statement('EXEC valetransp.atualizanumerocartao ?, ?', [$empregadoId, $cartao]);
            }

            return redirect()->back()->with('success', 'Registro atualizado com sucesso!');
        } catch (\Exception $e) {
            // Retorna erro em caso de falha
            return redirect()->back()->withErrors(['error' => 'Erro ao atualizar o registro: '.$e]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'EmpregadoId' => 'required|integer',
            'Linha' => 'required|integer',
            'Valor' => 'nullable|numeric',
            'Cartao' => 'nullable|numeric',
            'Quantidade' => 'required|integer',
            'QuantidadeExtra' => 'nullable|integer',
            'Data' => 'required|date',
            'Tipo' => 'nullable|string',
        ]);

        // Verifique se 'QuantidadeExtra' não está preenchido e defina como 0
        if (empty($validatedData['QuantidadeExtra'])) {
            $validatedData['QuantidadeExtra'] = 0;
        }

        // Mapear 'Linha' para 'LinhaId'
        $validatedData['LinhaId'] = $validatedData['Linha'];
        unset($validatedData['Linha']); // Remover o campo 'Linha' do array

        // Obter o mês e o ano da data
        $data = \Carbon\Carbon::parse($validatedData['Data']);
        $mesAno = $data->format('Y-m'); // Formato YYYY-MM

        // Verificar se o registro já existe
        $registroExistente = ValeTransporte::where('EmpregadoId', $validatedData['EmpregadoId'])
            ->whereYear('Data', $data->year)
            ->whereMonth('Data', $data->month)
            ->first();

        if ($registroExistente) {
            return redirect()->back()->withErrors(['error' => 'Registro já existe para este empregado no '.$mesAno.' especificado.']);
        }

        try {
            // Start a transaction
            DB::connection('gestaorh')->beginTransaction();

            // Create a new record in ValeTransporte
            $registro = ValeTransporte::create($validatedData);

            // Execute the update on the servidores table
            DB::connection('gestaorh')->table('servidores')
                ->where('id', $validatedData['EmpregadoId'])
                ->update(['valetransportecartao' => $validatedData['Cartao']]);

            // Commit the transaction
            DB::connection('gestaorh')->commit();

            return redirect()->back()->with('success', 'Beneficiário adicionado com sucesso!');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::connection('gestaorh')->rollBack();

            return redirect()->back()->withErrors(['error' => 'Erro ao adicionar o registro: ' . $e->getMessage()]);
        }
    }


    public function show($id)
    {
        // Encontra o registro pelo ID ou lança uma exceção se não for encontrado
        $registro = ValeTransporteCombustivel::findOrFail($id);

        // Obtém todas as linhas disponíveis
        $linhasDisponiveis = $this->getTodasLinhas();

        // Retorna o registro junto com as linhas disponíveis em formato JSON
        return response()->json([
            'registro' => $registro,
            'linhasDisponiveis' => $linhasDisponiveis,
        ]);
    }

    public function destroy(string $id)
    {
        try {
            // Log para indicar que a exclusão será realizada diretamente no banco de dados
            Log::info("Tentando excluir o registro com ID: {$id} usando a conexão gestaorh");

            // Executa o DELETE diretamente no banco de dados
            DB::connection('gestaorh')->table('valetransp.valetransporte')->where('id', $id)->delete();

            Log::info("Registro com ID: {$id} excluído com sucesso.");

            return response()->json(['message' => 'Registro excluído com sucesso!']);
        } catch (\Exception $e) {
            Log::error("Erro ao excluir o registro: " . $e->getMessage());

            return response()->json(['message' => 'Erro ao excluir o registro.'], 500);
        }
    }

    public function export(Request $request)
    {

        $mesAno = $request->query('mesAno');
        return Excel::download(new ValeTransporteCombustivelExport($mesAno), 'combustivel.xlsx');
    }

    public function exportXmlTransport(Request $request)
    {
        // Obtém o valor do parâmetro 'mesAno'
        $mesAno = $request->query('mesAno');

        // Consulta ao banco de dados para obter registros
        $registros = DB::connection('gestaorh')->select('SELECT Nome, CPF, Cartao, Valor, MesAno FROM valetransp.dadosxml WHERE MesAno = ? ORDER BY Nome', [$mesAno]);

        // Verifica se há registros sem o valor de Cartao
        $incompleteRecords = collect($registros)->filter(function ($vl) {
            return empty($vl->Cartao);
        });

        // Se existirem registros incompletos, retorna uma mensagem de erro
        if ($incompleteRecords->isNotEmpty()) {
            $errors = $incompleteRecords->map(function ($vl) {
                return "Nome: {$vl->Nome}, CPF: {$vl->CPF}";
            })->implode(', ');

            return response()->json([
                'message' => "Erro: Os seguintes registros estão sem número de cartão: $errors. Preencha os números de cartão antes de exportar."
            ], 400);
        }

        // Criação do XML usando XMLWriter
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument();
        $xml->startElement('DSImpCEValor');

        foreach ($registros as $vl) {
            $xml->startElement('CE');

            // Adiciona o elemento CPF
            $xml->startElement('CPF');
            $xml->text($vl->CPF);
            $xml->endElement();

            // Adiciona o elemento Cartao
            $xml->startElement('Cartao');
            $xml->text($vl->Cartao);
            $xml->endElement();

            // Adiciona o elemento vi
            $xml->startElement('vi');

            // Adiciona o elemento Nome dentro de vi
            $xml->startElement('Nome');
            $xml->text($vl->Nome);
            $xml->endElement();

            // Adiciona o elemento Valor dentro de vi
            $xml->startElement('Valor');
            $xml->text($vl->Valor);
            $xml->endElement();

            $xml->endElement(); // Fecha o elemento vi

            $xml->endElement(); // Fecha o elemento CE
        }

        $xml->endElement(); // Fecha o elemento DSImpCEValor
        $xml->endDocument();

        $contents = $xml->outputMemory();
        $xml = null;

        // Salva o XML no storage
        $filename = 'transporte.xml';
        Storage::disk('local')->put($filename, $contents);

        return response()->json([
            'status' => 'success',
            'message' => 'Arquivo XML exportado com sucesso.',
            'download_url' => route('download.xml', ['filename' => $filename])
        ]);
    }

    public function importarArquivo(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|mimes:xlsx,xls',
            'tipo' => 'required|in:1,2',
            'periodo' => 'required|string',
        ]);

        $tipo = $request->input('tipo');
        $mesAno = $request->input('periodo');
        Log::info("Valor de mesAno recebido: {$mesAno}");
        $file = $request->file('arquivo');

        if ($file->isValid()) {
            // Renomeia o arquivo conforme o tipo
            $novoNome = $tipo == 1 ? 'valetransp.xlsx' : 'valecomb.xlsx';

            // Caminho no servidor remoto
            $remotePath = '\\\\10.233.208.200\\temp\\';

            // Verifica se o arquivo já existe no servidor remoto
            $existingFile = $remotePath . $novoNome;
            if (File::exists($existingFile)) {
                // Exclui o arquivo existente antes de salvar o novo
                File::delete($existingFile);
                Log::info("Arquivo existente {$existingFile} excluído.");
            }

            // Salva o arquivo na pasta temporária local
            $file->storeAs('temp', $novoNome);

            // Caminho completo do arquivo na máquina local
            $localPath = storage_path('app/temp/' . $novoNome);

            // Verifica se o arquivo local foi salvo corretamente
            if (!File::exists($localPath)) {
                Log::error("Arquivo local não encontrado: {$localPath}");
                return redirect()->back()->with('error', 'Falha ao salvar o arquivo local. Tente novamente.');
            }

            // Comando para conectar ao compartilhamento de rede com usuário e senha
            $username = env('REMOTE_USERNAME'); // Substitua pelo nome de usuário
            $password = env('REMOTE_PASSWORD');   // Substitua pela senha
            $netUseCommand = "net use \\\\10.233.208.200\\temp /user:$username $password";

            // Executa o comando net use e loga o resultado
            exec($netUseCommand, $output, $return_var);

            Log::info("Comando executado: {$netUseCommand}");
            Log::info("Resultado do comando: " . implode("\n", $output));

            if ($return_var !== 0) {
                Log::error("Falha ao conectar ao servidor remoto com o código de retorno: {$return_var}");
                return redirect()->back()->with('error', 'Falha ao conectar ao servidor remoto. Verifique as credenciais e tente novamente.');
            }

            // Copia o arquivo para o servidor remoto
            if (!File::copy($localPath, $remotePath . $novoNome)) {
                Log::error("Erro ao copiar o arquivo para o servidor remoto. De: {$localPath} Para: {$remotePath}{$novoNome}");
                return redirect()->back()->with('error', 'Erro ao copiar o arquivo para o servidor remoto.');
            }

            Log::info("Arquivo copiado com sucesso para o servidor remoto: {$remotePath}");

            // Rodar a stored procedure correspondente
            $procedure = $tipo == 1 ? 'carregavaletransp' : 'carregavalecomb';
            Log::info("Executando procedimento: {$procedure} com mesAno: {$mesAno}");
            $result = DB::connection('gestaorh')->select("EXEC valetransp.$procedure ?", [$mesAno]);
            Log::info("Resultado da execução da stored procedure: ", (array)$result);
            Log::info('Stored Procedure Result:', $result);

            // Verifica se o resultado não é nulo e se contém ao menos um item
            if (!empty($result) && isset($result[0]->Mensagem)) {
                $mensagem = $result[0]->Mensagem;

                if (preg_match('/(\d+) REGISTROS INSERIDOS/', $mensagem, $matches)) {
                    $registrosInseridos = intval($matches[1]);

                    if ($registrosInseridos == 0) {
                        return response()->json(['status' => 'warning', 'message' => "Para esse período $mesAno, todos os registros foram importados. "]);
                    } else {
                        return response()->json(['status' => 'success', 'message' => "Arquivo processado com sucesso! $registrosInseridos registros inseridos."]);
                    }
                } else {
                    Log::error("Formato de mensagem inesperado: {$mensagem}");
                    return response()->json(['status' => 'error', 'message' => 'Erro ao processar o arquivo. Verifique o retorno da stored procedure.']);
                }
            } else {
                Log::error("Nenhum registro retornado ou propriedade 'Mensagem' não encontrada.");
                return response()->json(['status' => 'error', 'message' => 'Erro ao processar o arquivo. Verifique o retorno da stored procedure.']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Falha no upload do arquivo. Tente novamente.']);
        }
    }
}
