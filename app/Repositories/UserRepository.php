<?php

namespace App\Repositories;

use App\Exceptions\RepositoryInternalException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->setModel(User::class);
    }

    const VALID_CREATE_PARAMS = [
        'name',
        'email',
        'password',
        'type'
    ];

    const VALID_UPDATE_PARAMS = [
        'id',
        'name',
        'password',
        'status'
    ];

    /**
     * @return mixed
     */
    public function getAllUsers()
    {
        return $this->all()->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserByID($id)
    {
        return $this->find($id)->toArray();
    }

    /**
     * @param $request
     * @return bool
     * @throws RepositoryInternalException
     */
    public function createUser($request)
    {
        $params = $request->only(self::VALID_CREATE_PARAMS);

        $params['password'] = Hash::make($params['password']);

        try {
            DB::beginTransaction();

            if ($this->createNew($params)) {
                DB::commit();

                return true;
            }

            return false;
        } catch (\Exception $exception){
            DB::rollBack();

            throw new RepositoryInternalException(
                __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' . $exception->getMessage()
            );
        }
    }

    /**
     * @param $request
     * @return bool
     * @throws RepositoryInternalException
     */
    public function updateUser($request)
    {
        $params = $request->only(self::VALID_UPDATE_PARAMS);

        $id = $params['id'];
        unset($params['id']);

        if (isset($params['password']) && !empty($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        }

        try {
            DB::beginTransaction();

            if ($this->update($id, $params)) {
                DB::commit();

                return true;
            }

            return false;
        } catch (\Exception $exception){
            DB::rollBack();

            throw new RepositoryInternalException(
                __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' . $exception->getMessage()
            );
        }
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws RepositoryInternalException
     * @throws \App\Exceptions\RepositoryRequestException
     */
    public function deleteUser($id)
    {
        $this->verifyRecord($id);

        try {
            DB::beginTransaction();

            if ($this->delete($id)) {
                DB::commit();

                return true;
            }

            return false;
        } catch (\Exception $exception){
            DB::rollBack();

            throw new RepositoryInternalException(
                __CLASS__ . '::' . __METHOD__ . '(' . __LINE__ . ') -> ' . $exception->getMessage()
            );
        }
    }
}
