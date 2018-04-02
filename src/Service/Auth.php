<?php

namespace App\Service;

use App\Entity\Manager;
use App\Entity\Permissions;
use App\Form\Data\ChangePassword;
use App\Form\Data\Login;
use App\Form\Data\ResetPassword;
use App\MRMException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use App\Form\Data\Manager as ManagerData;


/**
 * @author: igor.popravka
 * Date: 21.02.2018
 * Time: 10:50
 */
class Auth extends AbstractService {
    private $admin_info;
    private $manager;
    private $session;
    private $token;
    private $encoder;

    public function __construct(SessionInterface $session, ManagerRegistry $doctrine, PasswordEncoder $encoder, MRMToken $token, $admin_info) {
        parent::__construct($doctrine);

        $this->session = $session;
        $this->token = $token;
        $this->encoder = $encoder;
        $this->admin_info = $admin_info;

        $this->loadSession();
    }

    private function getAdminInfo($name = null) {
        if (isset($name) && isset($this->admin_info[$name])) {
            return $this->admin_info[$name];
        }
        return $this->admin_info;
    }


    /**
     * @param Login $data
     * @return bool
     * @throws MRMException
     */
    public function login(Login $data) {
        $encoder = $this->getEncoder();
        if (($data->getLogin() == $this->getAdminInfo('login')) && ($encoder->isValidPassword($data->getPassword(), $this->getAdminInfo('password')))) {
            $this->manager = new Manager();
            $this->manager->setId(-1);
            $this->manager->setFirstName('Admin');
            $this->manager->setLastName('');
            $this->manager->setLogin($this->getAdminInfo('login'));
            $this->manager->setPassword($this->getAdminInfo('password'));
            $this->manager->setRole(Manager::ROLE_ADMIN);
            $this->manager->setPermissions(new Permissions(true, true, true, true, true, true));

            $this->updateSession();
            return true;
        } else if (($manager = $this->findManager($data->getLogin())) instanceof Manager) {
            if ($encoder->isValidPassword($data->getPassword(), $manager->getPassword())) {
                if ($manager->getStatus() == Manager::STATUS_ACTIVE) {
                    $this->manager = $manager;
                    $this->updateSession();
                    return true;
                }

                MRMException::throwNew('Your account was deactivated. Please contact with your administrator');
            }
        }

        MRMException::throwNew('Incorrect login or password');
        return false;
    }

    /**
     * @param string $login
     *
     * @return null|Manager
     */
    public function findManager(string $login) {
        /** @var Manager|null $manager */
        $manager_repository = $this->getDoctrine()->getRepository(Manager::class);
        return $manager_repository->findOneBy(['login' => $login]);
    }

    public function getManagerByID(int $id) {
        /** @var Manager|null $manager */
        $manager_repository = $this->getDoctrine()->getRepository(Manager::class);
        return $manager_repository->find($id);
    }

    public function logout() {
        $this->endSession();
        unset($this->manager);
    }

    public function updateSession() {
        $this->getSession()->set('manager', serialize($this->getManager()));
    }

    public function endSession() {
        $this->getSession()->invalidate();
    }

    public function loadSession() {
        $manager = unserialize($this->getSession()->get('manager'));
        if ($manager instanceof Manager) {
            $this->manager = $manager;
        }
    }

    public function isAdmin() {
        return $this->isAuthenticated() && $this->getManager()->getRole() == Manager::ROLE_ADMIN;
    }

    public function isAuthenticated() {
        return isset($this->manager);
    }

    /**
     * @return Manager
     */
    public function getManager() {
        return $this->manager;
    }

    /**
     * @return mixed
     */
    public function getUserFullName() {
        return $this->isAuthenticated() ? $this->getManager()->getFullName() : '';
    }

    /**
     * @return SessionInterface
     */
    private function getSession(): SessionInterface {
        return $this->session;
    }

    /**
     * @return MRMToken
     */
    private function getToken(): MRMToken {
        return $this->token;
    }

    /**
     * @return PasswordEncoder
     */
    private function getEncoder(): PasswordEncoder {
        return $this->encoder;
    }

    public function canDo(...$permissions) {
        if ($this->isAuthenticated()) {
            if ($this->isAdmin()) {
                return true;
            }
            $managerPermissions = $this->getManager()->getPermissions();
            foreach ($permissions as $permission) {
                if ($managerPermissions->is($permission)) {
                    continue;
                }
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * @param ResetPassword $data
     * @return MRMToken|null
     */
    public function resetPassword(ResetPassword $data) {
        if (($manager = $this->findManager($data->getLogin())) instanceof Manager) {
            return $this->getToken()
                ->generate([
                    'login' => $manager->getLogin(),
                    'full_name' => $manager->getFullName()
                ], new \DateTime('+4 hours'));
        }
        return null;
    }

    /**
     * @param ChangePassword $data
     * @param string $hash
     *
     * @return bool
     */
    public function changePassword(ChangePassword $data, string $hash = '') {
        if ($this->getToken()->load($hash)->isValid()) {
            if (($manager = $this->findManager($this->getToken()->getData()['login'])) instanceof Manager) {
                $manager->setPassword($this->getEncoder()->hashPassword($data->getPassword()));

                $em = $this->getDoctrine()->getManager();
                $em->persist($manager);
                $em->flush();

                $this->getToken()->reset();

                return true;
            }
        }
        return false;
    }

    /**
     * @param ManagerData $managerData
     * @return MRMToken|null
     */
    public function createNewManager(ManagerData $managerData) {
        if ($this->isAdmin()) {
            $uniqueLogin = ($this->getManager()->getLogin() != $managerData->getLogin());
        } else {
            $uniqueLogin = !($this->findManager($managerData->getLogin()) instanceof Manager);
        }

        if ($uniqueLogin) {
            $em = $this->getDoctrine()->getManager();

            $manager = new Manager();
            $managerData->handleEntity($manager);

            $passwordHash = $this->getEncoder()->hashPassword($this->getEncoder()->generateRaw());
            $manager->setPassword($passwordHash);

            $permissions = new Permissions();
            $managerData->handleEntity($permissions);

            $em->persist($permissions);
            $em->flush();

            $manager->setPermissions($permissions);

            $em->persist($manager);
            $em->flush();

            $token = $this->getToken()->generate([
                'login' => $manager->getLogin(),
                'full_name' => $manager->getFullName()
            ], new \DateTime('+4 hours'));

            return $token;
        }
        return null;
    }

    public function updateManager(ManagerData $managerData) {
        if (($manager = $this->findManager($managerData->getLogin())) instanceof Manager) {
            $em = $this->getDoctrine()->getManager();

            $managerData->handleEntity($manager);
            $em->persist($manager);
            $em->flush();

            $managerData->handleEntity($manager->getPermissions());
            $em->persist($manager->getPermissions());
            $em->flush();

            if ($this->getManager()->getId() == $manager->getId()) {
                $this->manager = $manager;
                $this->updateSession();
            }

            return true;
        }
        return false;
    }
}