<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Declarations;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Http\MiddlewareInterface;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\ClassDeclaration\MethodDeclaration;
use Spiral\Reactor\UseRequesterInterface;

/**
 * Middleware declaration.
 */
class MiddlewareDeclaration extends ClassDeclaration implements UseRequesterInterface
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct($name, $comment = '')
    {
        parent::__construct($name, '', ['MiddlewareInterface'], $comment);
        $this->declareStructure();
    }

    /**
     * {@inheritdoc}
     */
    public function requestsUses()
    {
        return [
            MiddlewareInterface::class    => null,
            ServerRequestInterface::class => 'Request',
            ResponseInterface::class      => 'Response'
        ];
    }

    /**
     * Invoke method source.
     *
     * @return MethodDeclaration
     */
    public function invokeMethod()
    {
        return $this->method('__invoke');
    }

    /**
     * Declare default __invoke method body.
     */
    private function declareStructure()
    {
        $invoke = $this->method('__invoke')->setAccess(MethodDeclaration::ACCESS_PUBLIC);

        $invoke->parameter('request')->setType('Request');
        $invoke->parameter('response')->setType('Response');
        $invoke->parameter('next')->setType('callable');

        $invoke->comment()->setString("{@inheritdoc}");
        $invoke->source()->setString('return $next($request, $response);');
    }
}