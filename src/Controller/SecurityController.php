<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        try {
            $token = $this->get('my_cellar.security.authentication_provider')->authenticateAndCreateJWT($data);
            $response = [
                'token' => $token->__toString(),
                'username' => $token->getClaim('username'),
                'email' => $token->getClaim('email'),
            ];
            return new JsonResponse($response);
        } catch (BadCredentialsException $e) {
            throw new UnauthorizedHttpException('None', 'Bad credentials', $e);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }
}
