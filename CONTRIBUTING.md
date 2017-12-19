# How to Contribute

First of all, thank you for your interest in Laravel Hashids!
We'd love to accept your patches and contributions! 
It's a great way to learn more about new technologies, their ecosystems and how to make constructive, helpful bug reports, feature requests and the noblest of all contributions: a good, clean pull request.

## Code reviews

All submissions, including submissions by project members, require review. We
use GitHub pull requests for this purpose. Consult
[GitHub Help](https://help.github.com/articles/about-pull-requests/) for more
information on using pull requests.


### How to make a clean pull request

- Create a personal fork of the project on Github.
- Clone the fork on your local machine. Your remote repo on Github is called `origin`.
- Add the original repository as a remote called `upstream`.
- If you created your fork a while ago be sure to pull upstream changes into your local repository.
- Create a new branch to work on from `develop`!
- Implement/fix your feature, comment your code.
- Follow the code style of the project, including indentation.
- If the project has tests run them!
- Write or adapt tests as needed.
- Add or change the documentation as needed.
- Push your branch to your fork on Github, the remote `origin`.
- From your fork open a pull request in the correct branch. Target the project's `develop` branch.
- ...
- Once the pull request is approved and merged you can pull the changes from `upstream` to your local repo and delete
your extra branch(es).

And last but not least: Always write your commit messages in the present tense. Your commit message should describe what the commit, when applied, does to the code – not what you did to the code. 
Comments should be generally avoided. If the code would not be understood without comments, consider re-writing the code to make it self-explanatory

## Writing Documentation

All public functionalities must have a descriptive entry in the [README.md](https://github.com/bondacom/laravel-hashids/blob/master/README.md).

## Writing Tests

Every feature should be accompanied by a test. To run all tests:
```
vendor/bin/phpunit
```

- To filter tests by name:
```
vendor/bin/phpunit --filter={{testName}}
```