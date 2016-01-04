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
$file = new FileDeclaration('namespace');

$controller = new ControllerDeclaration('SampleController');
$controller->action('index')->setSource([
   'return "hello world";'
]);

$file->addElement($controller);

dump($file->render());
```     