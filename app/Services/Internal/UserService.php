<?php

namespace App\Services\Internal;

use App\Helpers\UserHelper;
use App\Jobs\SendResetPasswordEmail;
use App\Models\PasswordResetToken;
use App\Repositories\Internal\UserRepository;
use App\Rules\UserRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $rule;

    protected $repository;

    public function __construct(
        UserRule $rule,
        UserRepository $repository
    ) {
        $this->rule = $rule;
        $this->repository = $repository;
    }

    public function login($request)
    {
        $this->rule->login($request);

        $data = $request->only(['password', 'email']);

        $user = $this->repository->findByEmail($data['email']);
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais não constam em nosso registro.'],
            ]);
        }
        if (! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Credenciais não constam em nosso registro.'],
            ]);
        }
        if ($user->active === 0) {
            throw ValidationException::withMessages([
                'active' => ['Este usuário está inativo e não pode acessar a conta. Por favor, entre em contato com o administrador.'],
            ]);
        }

        $this->repository->update($user->id, ['view_enterprise_id' => null]);

        $user = $this->repository->findByEmail($data['email']);

        UserHelper::clearTokenReset($user->email);

        return $user;
    }

    public function reset($request)
    {
        $this->rule->reset($request);
        $user = $this->repository->findByEmail($request->input('email'));

        if ($user) {
            $token = app('auth.password.broker')->createToken($user);
            SendResetPasswordEmail::dispatch($user, $token);
        } else {
            throw ValidationException::withMessages([
                'email' => ['O e-mail não está cadastrado.'],
            ]);
        }

        return 'O e-mail informado receberá um código para redefinir sua senha';
    }

    public function verify($request)
    {
        $this->rule->verify($request);
        $reset = PasswordResetToken::where('email', $request->input('email'))->first();

        if ($reset && $reset->code === $request->input('code')) {
            return ['valid' => true, 'message' => 'Código verificado com sucesso'];
        }

        return ['valid' => false, 'message' => 'Código incorreto ou expirado'];
    }

    public function resetPassword($request)
    {
        $this->rule->resetPassword($request);

        $data = ['password' => Hash::make($request->input('password'))];

        $result = $this->repository->resetPassword($request->input('email'), $data);

        $register = PasswordResetToken::where('email', $request->input('email'))->first();
        if ($register) {
            $register->delete();
        }

        return $result;
    }

    public function include($request)
    {
        $this->rule->include($request);

        $data = $request->only(['name', 'email', 'position', 'phone']);
        $data['password'] = Hash::make($request->input('password'));
        $data['department_id'] = $request->input('department');
        $data['created_by'] = $request->user()->id;

        return $this->repository->create($data);
    }

    public function updateMember($request)
    {
        $this->rule->updateMember($request);

        $data = $request->only(['name', 'email', 'position', 'phone']);
        $data['department_id'] = $request->input('department');

        return $this->repository->updateMember($request->id, $data);
    }

    public function update($request)
    {
        $this->rule->update($request);

        $data = $request->only(['name', 'email', 'phone']);
        $data['department_id'] = $request->input('department');

        return $this->repository->update($request->user()->id, $data);
    }

    public function updatePassword($request)
    {
        $this->rule->updatePassword($request);

        UserHelper::validUser($request->user()->email, $request->input('passwordActual'));

        $data = ['password' => Hash::make($request->input('passwordNew'))];

        return $this->repository->updatePassword($request->user()->id, $data);
    }
}
