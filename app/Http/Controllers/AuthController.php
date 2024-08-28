<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Helpers\LdapHelper;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        Log::info('Tentativa de autenticação', ['username' => $username]);

        // Chama a função para autenticar via LDAP
        $response = LdapHelper::callLdapMicroservice($username, $password);

        Log::info('Resposta da autenticação', ['response' => $response]);

        if ($response['status_code'] == 200) {
            $user_info = json_decode($response['response'], true);

            if (isset($user_info['user_info']) && isset($user_info['systems_access'])) {
                $systems_access = $user_info['systems_access'];
                $hasAccess = false;
                $role = null;

                foreach ($systems_access as $system) {
                    if (strpos($system['Sistema'], 'SISMOBILIDADE') !== false) {
                        $role = $system['Sistema'];

                        if (strpos($role, 'ADMINISTRADOR') !== false) {
                            $hasAccess = true;
                            $role = 'ADMINISTRADOR';
                            break;
                        } elseif (strpos($role, 'OPERADOR') !== false) {
                            $hasAccess = true;
                            $role = 'OPERADOR';
                            break;
                        } elseif (strpos($role, 'BENEFICIARIO') !== false) {
                            $hasAccess = true;
                            $role = 'BENEFICIARIO';
                            break;
                        }
                    }
                }

                if ($hasAccess) {
                    // Verifica se o usuário já existe no banco de dados
                    $userRecord = DB::connection('usuarios')->table('Usuarios')->where('Login', $user_info['user_info']['username'])->first();

                    // Se o usuário não existir, cria um novo registro
                    if (!$userRecord) {
                        $newUser = [
                            'email' => $user_info['user_info']['email'],
                            'name' => $user_info['user_info']['displayName'],
                            'login' => $user_info['user_info']['username'],
                            // Adicione outros campos necessários
                        ];
                        DB::connection('usuarios')->table('Usuarios')->insert($newUser);
                        $user_id = DB::connection('usuarios')->getPdo()->lastInsertId();
                    } else {
                        $user_id = $userRecord->Id;
                    }

                    // Armazena as informações do usuário na sessão
                    Session::put([
                        'userId' => $user_id,
                        'role' => $role,
                        'email' => $user_info['user_info']['email'],
                        'name' => $user_info['user_info']['displayName'],
                        'isLoggedIn' => true,
                    ]);

                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Usuário não tem acesso ao SISMOBILIDADE.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Usuário não autorizado.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Usuário ou senha inválidos.']);
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login.page')->with('success', 'Você saiu com sucesso.');
    }
}
