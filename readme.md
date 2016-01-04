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
}
```