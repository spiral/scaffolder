Spiral, scaffolder module
=========================
[![Latest Stable Version](https://poser.pugx.org/spiral/scaffolder/v/stable)](https://packagist.org/packages/spiral/scaffolder) 
[![License](https://poser.pugx.org/spiral/scaffolder/license)](https://packagist.org/packages/spiral/scaffolder)
[![Build Status](https://travis-ci.org/spiral/scaffolder.svg?branch=master)](https://travis-ci.org/spiral/scaffolder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiral/scaffolder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spiral/scaffolder/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/spiral/scaffolder/badge.svg?branch=master)](https://coveralls.io/github/spiral/scaffolder?branch=master)

<b>[Documentation](http://spiral-framework.com/guide)</b> | [CHANGELOG](/CHANGELOG.md) | [Framework Bundle](https://github.com/spiral/spiral)

## Installation
If you do not have scaffolder module installed execute following commands:

```
$ composer require spiral/scaffolder
```

## Configuration
You can customize scaffolder component by replacing declaration generators and their options using `scaffolder` configuration file.
Default configuration is located in the `ScaffolderBootloader`

## Usage
Add `ScaffolderBootloader` to a list of application bootloaders in order to enable all commands.

## Available Commands
Command            | Description
---                | ---
create:command     | Create command declaration
create:controller  | Create controller declaration
create:filter      | Create HTTP Request Filter declaration
create:middleware  | Create middleware declaration
create:migration   | Create migration declaration
create:repository  | Create Entity Repository declaration
create:entity      | Create Entity declaration

### Create command
```
$ php app.php create:command <name> [<alias>]
```
`<Name>Command` class will be created. Command executable name will be set to `name` or `alias` if alias is set.

### Create controller
```
$ php app.php create:controller <name>
```
`<Name>Controller` class will be created.
You can optionally specify controller actions using `action (a)` option (multiple values allowed).

### Create HTTP request filter
```
$ php app.php create:filter <name>
```
`<Name>Filter` class will be created.
You can optionally specify filter schema using `field (f)` option (multiple values allowed).<br/>
Full field format is `name:type(source:origin)`. `type`, `origin` and `source:origin` are optional and can be omitted, defaults are:
* type=string
* source=data
* origin=\<name\>
> See more about filters in [filters](https://github.com/spiral/filters) package

### Create middleware
```
$ php app.php create:middleware <name>
```
`<Name>Middlweare` class will be created.

### Create migration
```
$ php app.php create:migration <name>
```
`<Name>Migration` class will be created.
You can optionally specify table name using `table (t)` option and columns using `column (col)` option (multiple values allowed).
Column format is `name:type`.
> See more about migrations in [migrations](https://github.com/spiral/migrations) package

### Create repository
```
$ php app.php create:repository <name>
```
`<Name>Repository` class will be created.
 
### Create entity
```
$ php app.php create:repository <name> [<format>]
```
`<Name>Entity` class will be created.
`format` is responsible for the declaration format, currently only [annotations](https://github.com/cycle/annotated) is supported. 
Available options:
* `role (r)` - Entity role, defaults to lowercase class name without a namespace
* `mapper (m)` - Mapper class name, defaults to Cycle\ORM\Mapper\Mapper
* `table (t)` - Entity source table, defaults to plural form of entity role
* `accessibility (a)` - accessibility accessor (public, protected, private), defaults to public
* `inflection (i)` - Optional column name inflection, allowed values: tableize (or t), camelize (or c). See [Doctrine inflector](https://github.com/doctrine/inflector)
* `field (f)` - Add field in a format "name:type" (multiple values allowed)
* `repository (repo)` - Repository class to represent read operations for an entity, defaults to `Cycle\ORM\Select\Repository`
* `database (db)` - Database name, defaults to null (default database)

