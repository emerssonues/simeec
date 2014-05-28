<?php
namespace Application\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContextInterface;

class LoginRequestListener
{
    protected $securityContext;
    protected $router;

    public function __construct(SecurityContextInterface $securityContext, RouterInterface $router)
    {
        $this->securityContext = $securityContext;
        $this->router = $router;
    }

    public function onLoginRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $_route  = $request->attributes->get('_route');

        if(($_route == "sonata_user_admin_security_login")) {
            if($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
                $event->setResponse(new RedirectResponse($this->router->generate('sonata_admin_dashboard')));
            }
        }
    }
}
