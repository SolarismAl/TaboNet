<?php

namespace App\Actions\Fortify;

use App\Actions\Teams\CreateTeam;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function __construct(private CreateTeam $createTeam)
    {
        //
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            $role = $input['role'] ?? 'buyer';
            $user = User::create([
                'full_name' => $input['name'],
                'email' => $input['email'],
                'password_hash' => Hash::make($input['password']),
                'role' => $role,
                'phone_number' => $input['phone_number'] ?? null,
                'verification_status' => $role === 'farmer' ? 'pending' : 'verified',
            ]);

            if ($role === 'farmer') {
                $user->farmerProfile()->create([
                    'farm_name' => $user->full_name.' Farm Produce',
                    'farm_location' => $input['barangay'] ?? 'Linotan',
                    'farm_type' => 'Crops',
                    'valid_id_url' => $input['rsbsa_number'] ?? null,
                    'bio' => 'Cantilan registered smallholder producer.',
                ]);
            } else {
                $user->buyerProfile()->create([
                    'business_name' => $user->full_name.' Trade',
                    'delivery_address' => $input['barangay'] ?? 'Poblacion',
                    'buyer_type' => 'Individual Consumer',
                ]);
            }

            $this->createTeam->handle($user, $user->full_name."'s Team", isPersonal: true);

            return $user;
        });
    }
}
