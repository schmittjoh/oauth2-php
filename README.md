OAuth2 Server now implements draft 20 of OAuth 2.0

The client is still only draft-10.

This version of oauth2-php is a fork of https://github.com/quizlet/oauth2-php with the following changes:

 - Namespaced
 - No more require(_once)
 - [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) autoloading compatible
 - Uses [HttpFoundation](https://github.com/symfony/HttpFoundation) Request and Response for input/output
 - More testable design
 - Better test coverage

(pull request is pending)

https://github.com/quizlet/oauth2-php is a fork of http://code.google.com/p/oauth2-php/ updated against OAuth2.0 draft
20, with a better OO design.

http://code.google.com/p/oauth2-php/ is the original repository, which seems abandonned.