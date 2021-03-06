<?php

namespace Directus\Console\Common;

use Directus\Application\Application;
use Directus\Console\Common\Exception\PasswordChangeException;
use Directus\Console\Common\Exception\UserUpdateException;
use Zend\Db\TableGateway\TableGateway;

class User
{
    private $directus_path;
    private $app;
    private $db;
    private $usersTableGateway;

    public function __construct($base_path)
    {
        if ($base_path == null) {
            $base_path = \Directus\base_path();
        }

        $this->directus_path = $base_path;
        $this->app = new Application($base_path, require $base_path. '/config/api.php');
        $this->db = $this->app->getContainer()->get('database');

        $this->usersTableGateway = new TableGateway('directus_users', $this->db);
    }

    /**
     *  Change the password of a user given their e-mail address
     *
     *  The function will change the password of a user given their e-mail
     *  address. If there are multiple users with the same e-mail address, and
     *  this should never be the case, all of their passwords would be changed.
     *
     *  The function will generate a new salt for every password change.
     *
     * @param string $email The e-mail of the user whose password is being
     *         changed.
     * @param string $password The new password.
     *
     * @return void
     *
     * @throws PasswordChangeException Thrown when password change has failed.
     *
     */
    public function changePassword($email, $password)
    {

        $auth = $this->app->getContainer()->get('auth');
        $hash = $auth->hashPassword($password);
        $user = $this->usersTableGateway->select(['email' => $email])->current();

        if (!$user) {
            throw new \InvalidArgumentException('User not found');
        }

        try {
            $update = [
                'password' => $hash
            ];

            $changed = $this->usersTableGateway->update($update, ['email' => $email]);
            if ($changed == 0) {
                throw new PasswordChangeException('Could not change password for ' . $email . ': ' . 'e-mail not found.');
            }
        } catch (\PDOException $ex) {
            throw new PasswordChangeException('Failed to change password' . ': ' . str($ex));
        }

    }

    /**
     *  Change the e-mail of a user given their ID.
     *
     *  The function will change the e-mail of a user given their ID. This may have
     *  undesired effects, this function is mainly useful during the setup/install
     *  phase.
     *
     *
     * @param string $id The ID of the user whose e-mail address we want to change.
     * @param string $email The new e-mail address for the use.
     *
     * @return void
     *
     * @throws UserUpdateException Thrown when the e-mail address change fails.
     *
     */
    public function changeEmail($id, $email)
    {

        $update = [
            'email' => $email
        ];

        try {
            $this->usersTableGateway->update($update, ['id' => $id]);
        } catch (\PDOException $ex) {
            throw new PasswordChangeException('Could not change email for ID ' . $id . ': ' . str($ex));
        }
    }

}
