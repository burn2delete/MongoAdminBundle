# MongoAdminBundle

MongoAdminBundle offers a fully featured administrative tool for [MongoDB](http://www.mongodb.org) that can be dropped into any [Symfony2](http://symfony-reloaded.org) application. It takes advantage of the Doctrine [MongoDB ODM](http://www.doctrine-project.org/projects/mongodb_odm) project to offer dynamic functionality based on your document mappings.

## Dependencies

MongoAdminBundle requires the following bundles in order to work.

* [MongoDB ODM](https://github.com/doctrine/mongodb-odm)
* [Twig](https://github.com/fabpot/Twig)
  * You'll have to enable the TwigBundle in Symfony2