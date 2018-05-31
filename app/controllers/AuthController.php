<?php
/**
 * Controlleur permetant la gestion des jeton d'accès à l'api
 *
 * PHP version 5.5
 *
 * @category   Controller
 * @package    worldcup\app\controllers
 * @author     Clément Hémidy <clement@hemidy.fr>, Fabien Côté <fabien.cote@me.com>
 * @copyright  2014 Clément Hémidy, Fabien Côté
 * @version    1.0
 * @since      0.1
 */

class AuthController extends BaseController {


    /**
     * Retourne un token si les informations sont bonnes
     *
     * @return mixed
     */
    public function login()
    {
        $input = Input::only('login', 'password');

        $user = User::getUserWithLogin($input['login']);

        if ($user != null && Input::has('login') && Input::has('password') && Hash::check($input['password'], $user->password))
        {
            return Response::json(
                array('success' => true,
                    'payload' => $user->getNewToken(),
                ));
        }else{
            return Response::json(
                array('success' => false,
                    'payload' => array(),
                    'error' => 'Informations incorrects (Login / mot de passe) !'
                ),
                404);
        }
    }

    public function loginWithoutPassword()
    {
        $input = Input::only('login');

        $user = User::getUserWithLogin($input['login']);

        if ($user != null && Input::has('login'))
        {
            return Response::json(
                array('success' => true,
                    'payload' => $user->getNewToken(),
                ));
        }else{
            return Response::json(
                array('success' => false,
                    'payload' => array(),
                    'error' => 'Informations incorrects (Login) !'
                ),
                404);
        }
    }

    /**
     * Déconnecte l'utilisateur
     *
     * @return Redirect
     */
    public function logout()
    {
        DB::table('token')->where('id', '=', $_GET['token'])->delete();
        return Response::json(
            array('success' => true,
                'payload' => null,
            ));
    }
}