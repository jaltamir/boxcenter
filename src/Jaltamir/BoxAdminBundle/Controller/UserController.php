<?php

namespace Jaltamir\BoxAdminBundle\Controller;

use Jaltamir\BoxCoreBundle\Entity\User;
use Jaltamir\BoxCoreBundle\Form\Type\UserChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/change-password/user/{id}", name="change_password")
     * @Template("BoxAdminBundle:UserAdmin:change_password.html.twig")
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showChangePasswordAction(User $user)
    {
        $passwordForm = $this->createForm(UserChangePasswordType::class, null, [
            'action'     => $this->generateUrl('change_password_action', ['id' => $user->getId()], true),
            'method'     => 'POST',
        ]);

        return [
            'user' => $user,
            'form' => $passwordForm->createView()
        ];
    }

    /**
     * @Route("/change-password-action/user/{id}", name="change_password_action")
     * @Template("BoxAdminBundle:UserAdmin:change_password.html.twig")
     *
     * @Method("post")
     * @param User $user
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request, User $user)
    {
        $passwordEncoder = $this->get('security.password_encoder');
        $em = $this->getDoctrine()->getManager();
        $passwordForm = $this->createForm(UserChangePasswordType::class, null, [
            'action'     => $this->generateUrl('change_password_action', ['id' => $user->getId()], true),
            'method'     => 'POST',
        ]);

        $passwordForm->handleRequest($request);

        if($passwordForm->isValid())
        {
            $rawPassword = $passwordForm->getData()['password'];
            $user->setPassword($passwordEncoder->encodePassword($user,$rawPassword));
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_jaltamir_boxcore_user_list');
        }
        else
        {
            return [
                'user' => $user,
                'form' => $passwordForm->createView()
            ];
        }
    }
}
