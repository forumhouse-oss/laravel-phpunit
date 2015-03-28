Laravel-PHPUnit Helpers
====================================

 Metrics | _
---|---
Version | [![PHP version](https://badge.fury.io/ph/fhteam%2Flaravel-phpunit.svg)](http://badge.fury.io/ph/fhteam%2Flaravel-phpunit)
Compatibility | [![Laravel compatibility](https://img.shields.io/badge/laravel-5-green.svg)](http://laravel.com/)
Quality | [![Code Climate](https://codeclimate.com/github/fhteam/laravel-phpunit/badges/gpa.svg)](https://codeclimate.com/github/fhteam/laravel-phpunit)

This small library contains some helpers for phpUnit.

Base features
------------------------------------

TestBase class contains the following features:

 - Setup application type you need to use when testing via `$appContract` property
 - Setup bootstrap file path you need to use when testing via `$bootstrapPath` property
 - Ability to use self-configuring traits
 
Self-configuring traits
------------------------------------

Base test class allows you to use automatically configuring traits. It works in the following way:

When test setUp method is called, traits are initialized first. If a trait has any special methods, they are called at 
the appropriate time. Suppose your test uses trait named `HiddenMembersTestTrait`. Then the following trait methods
will be called on the test class instance:

 - `beforeAppHiddenMembersTestTrait()` - before Laravel application is created
 - `afterAppHiddenMembersTestTrait(Application $app)` - right after Laravel application is created, but before leaving 
 phpUnit `setUp()` method

Testing private and protected members of your classes
------------------------------------

WARNING: Testing an object's protected or private properties / methods is generally a bad idea. Use with caution.

If you need to test private or protected test members of your class, you can use HiddenMembersTestTrait in your test
classes. It adds the following methods:

 - `assertMembersEqual`. This method accepts an object and an associative array of object's expected property values 
 and tests if all of the given expected property values equal to those of the object
 - `assertMembersSame`. This is variant of the above, which tests for sameness instead of equality
 - `getObjectProperties`. Just returns an array of object's requested properties with their values 
 (disregarding visibility)
 - `getMethodCallResult`. Returns a result of the method call (disregarding visibility)
