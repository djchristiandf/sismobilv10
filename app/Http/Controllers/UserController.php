<?php

namespace App\Http\Controllers;

use App\Helpers\LdapHelper;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Servidor;
use App\Models\UsuarioAcesso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    /** Metodo para logar no sistema apiLDAP */
    public function authenticateApi(Request $request)
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

        if($response['status_code'] == 200) {
            $user_info = json_decode($response['response'], true);

            if(isset($user_info['user_info']) && isset($user_info['systems_access'])) {
                $systems_access = $user_info['systems_access'];
                $hasAccess = false;
                $roleText = null;
                $redirectUrl = null;

                foreach($systems_access as $system) {
                    if(strpos($system['Sistema'], 'SISMOBILIDADE') !== false) {
                        $role = $system['Sistema'];

                        if (strpos($role, 'ADMINISTRADOR') !== false) {
                            $redirectUrl = 'comprovaPagamento'; // Redirecionar para a página do administrador
                            $hasAccess = true;
                            $role = 1;
                            $roleText = 'ADMINISTRADOR';
                            break;
                        } elseif (strpos($role, 'OPERADOR') !== false) {
                            $redirectUrl = 'comprovaPagamento'; // Redirecionar para a página do operador
                            $hasAccess = true;
                            $role = 2;
                            $roleText = 'OPERADOR';
                            break;
                        } elseif (strpos($role, 'BENEFICIARIO') !== false) {
                            $redirectUrl = 'comprovaPagamentoUsuario'; // Redirecionar para a página do beneficiário
                            $hasAccess = true;
                            $role = 3;
                            $roleText = 'BENEFICIARIO';
                            break;
                        }
                    }
                }

                if($hasAccess) {
                    // Configurar a sessão
                    $userRecord = DB::connection('usuarios')->table('Usuarios')->where('Login', $user_info['user_info']['username'])->first();

                    // Se o usuário não existir, adicione-o ao banco
                    if(!$userRecord) {
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

                    // Configurar a sessão com os dados do usuário
                    Session::put([
                        'userId' => $user_id,
                        'role' => $role,
                        'roleText' => $roleText,
                        'email' => $user_info['user_info']['email'],
                        'name' => $user_info['user_info']['displayName'],
                        'isLoggedIn' => TRUE,
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get user all
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //show user view to create
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //used to save data user in database
        $validatedData = $request->validate([
            'email' => 'required|email|max:128',
            //'password' => 'required|string|max:128',
            'name' => 'required|string|max:128',
            //'mobile' => 'required|string|max:20',
            //'roleId' => 'required|integer',
            'isDeleted' => 'required|integer',
            //'createdBy' => 'required|integer',
            'createdDtm' => 'required|date',
            'login' => 'required|string|max:100',
            'matricula' => 'required|string|max:9',
            'setor' => 'required|string|max:45',
            'cargo' => 'required|string|max:128'
        ]);

        User::create($validatedData);
        return redirect('/users')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //to show data user
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //get data user and send to view
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //update data user in database
        $validatedData = $request->validate([
            'email' => 'required|email|max:128',
            //'password' => 'required|string|max:128',
            'name' => 'required|string|max:128',
            //'mobile' => 'required|string|max:20',
            //'roleId' => 'required|integer',
            'isDeleted' => 'required|integer',
            //'createdBy' => 'required|integer',
            'createdDtm' => 'required|date',
            'login' => 'required|string|max:100',
            'matricula' => 'required|string|max:9',
            'setor' => 'required|string|max:45',
            'cargo' => 'required|string|max:128'
        ]);

        User::where('userId', $id)->update($validatedData);
        return redirect('/users')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //to delete userbyId
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/users')->with('success', 'User deleted successfully');

    }

    /**
     * Get users by Matricula from vw_servidores.
     */
    public function getByMatriculaVwServidores($matricula)
    {
        $users = Servidor::where('Matricula', $matricula)->get();
        return view('servidores.index', compact('users'));
    }

    /**
     * Get users by Matricula from vw_usuariosacesso.
     */
    public function getByMatriculaVwUsuariosAcesso($matricula)
    {
        $users = UsuarioAcesso::where('Matricula', $matricula)->get();
        return view('usuariosacesso.index', compact('users'));
    }

    /**
     * Get users by Nome from vw_servidores.
     */
    public function getByNomeVwServidores($nome)
    {
        $users = Servidor::where('Nome', 'LIKE', "%$nome%")->get();
        return view('servidores.index', compact('users'));
    }

    /**
     * Get users by Nome from vw_usuariosacesso.
     */
    public function getByNomeVwUsuariosAcesso($nome)
    {
        $users = UsuarioAcesso::where('Nome', 'LIKE', "%$nome%")->get();
        return view('usuariosacesso.index', compact('users'));
    }

    /**
     * Get users by Login from vw_usuariosacesso.
     */
    public function getByLoginVwUsuariosAcesso($login)
    {
        $users = UsuarioAcesso::where('Login', 'LIKE', "%$login%")->get();
        return view('usuariosacesso.index', compact('users'));
    }

    /**
     * Get users by Login from vw_servidores (se necessário).
     */
    public function getByLoginVwServidores($login)
    {
        $users = Servidor::where('Login', 'LIKE', "%$login%")->get();
        return view('servidores.index', compact('users'));
    }

    /**
     * Get all users from vw_servidores.
     */
    public function getAllFromServidores()
    {
        $users = Servidor::all();
        return view('servidores.index', compact('users'));
    }

    /**
     * Get all users from vw_usuariosacesso.
     */
    public function getAllFromUsuariosAcesso()
    {
        $users = UsuarioAcesso::all();
        return view('usuariosacesso.index', compact('users'));
    }
}
