# MongoAdminBundle

MongoAdminBundle offers a fully featured administrative tool for [MongoDB](http://www.mongodb.org) that can be dropped into any [Symfony2](http://symfony-reloaded.org) application. It takes advantage of the Doctrine [MongoDB ODM](http://www.doctrine-project.org/projects/mongodb_odm) project to offer dynamic functionality based on your document mappings.

## Status

MongoAdminBundle is still in very early development. The UI is very crude and its functionality limited and potentially buggy.

## Dependencies

MongoAdminBundle requires the following bundles in order to work. A [sample application](https://github.com/steves/MongoAdminApplication) is available that can be used as a guide.

* [Twig](https://github.com/fabpot/Twig)
  * You'll have to enable the TwigBundle in Symfony2

## Features

### Current

* Administer multiple servers at a time
* Browse a servers databases, collections and documents

### Planned

* Support for custom IDs as well as the default MongoID IDs
* Search
* Edit documents
* Add databases and collections
* Delete databases, collections and documents
* Read-only mode for the UI