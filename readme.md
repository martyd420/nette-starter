Nette Web Project
=================

Welcome to the Nette Starter Template! This is a basic skeleton application built using
[Nette](https://nette.org), Doctrine, Contributte, and more, with an optimized directory 
structure for ISPConfig (and presenters are NOT part of the user interface). 
Ideal for kick-starting your new web projects.
This package also includes **simple user registration and login**.

Nette is a renowned PHP web development framework, celebrated for its user-friendliness,
robust security, and outstanding performance. It's among the safest choices
for PHP frameworks out there.

If Nette helps you, consider supporting it by [making a donation](https://nette.org/donate).
Thank you for your generosity!


Requirements
------------

This Web Project is compatible with Nette 3.2 and requires PHP 8.1.


Installation
------------

To install the Web Project, Composer is the recommended tool. If you're new to Composer,
follow [these instructions](https://doc.nette.org/composer). Then, run:

	composer create-project martyd420/nette-starter path/to/install
	cd path/to/install

Ensure the `temp/` and `log/` directories are writable.


Web Server Setup
----------------

To quickly dive in, use PHP's built-in server:

	php -S localhost:8000 -t web

Then, open `http://localhost:8000` in your browser to view the welcome page.


TODO
----------------
 - [x] ~~Update to latest Nettrine~~
 - [ ] Try to automatically fix all constants (convert to uppercase)
