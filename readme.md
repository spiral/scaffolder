Scaffolder
==========

Available scaffolding declarations:
* ControllerDeclaration
* EntityDeclaration
    * RequestDeclaration
    * RecordDeclaration
    * DocumentDeclaration
* SourceDeclaration
* ServiceDeclaration (model)
* MiddlewareDeclaration
* CommandDeclaration

Usage Example (outside of console)
----------------

```php
$file = new FileDeclaration('App\Controllers', 'My project');

$controller = new ControllerDeclaration('SampleController');
$controller->action('index')->setSource([
    'return "hello world";'
]);

$method = $controller->method('internal);
$method->setAccess(MethodDeclaration::ACCESS_PRIVATE)->setComment('This is internal method');

$file->addElement($controller);

dump($file->render());
```     

Output:

```php
<?php
/**
 * My project
 */
namespace App\Controllers;

use Spiral\Core\Controller;

class SampleController extends Controller
{
    protected function indexAction()
    {
        return "hello world";
    }

    /**
     * This is internal method
     */
    private function internal()
    {
    }
}
```