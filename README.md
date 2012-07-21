# F3Sonda
Polish lightweight system for creating polls on a website that stores data in .php files.

## Requirements
- PHP 5.2+
- web server like Apache, Nginx...
- no database needed

## Installation
1. Clone or download this repository.
2. Copy all files to web server.
3. Set CHMOD 777 or CHOWN to proper user to folders:
	* cfg
	* list
	* style
4. Set CHMOD 666 or CHOWN to proper user to all files inside:
	* cfg
	* list
	* style

## First use
1. Navigate to folder ``admin`` in web browser.
2. Set new password to admin panel.
3. Create a first poll.

## How to insert poll on website?
To show latest poll, put the following code on your website:

```php
<?php
include 'sonda/sonda.php';
echo sonda();
?>
```

Make sure that path to ``sonda.php`` is correct.

You can also insert specific poll:

```php
<?php
include 'sonda/sonda.php';
echo sonda(5); // poll with ID 5
echo sonda(2, true); // poll results with ID 2
?>
```

## Key features
* admin panel
* polls archive
* one choice (radio) or multi choice (checkbox)
* cookies and IP multi-voting protection
* bar and cake (SVG) charts
* custom colors for answers
* editable HTML templates

## History
This project was started in 2004 to easily insert and manage voting polls on websites written in PHP. The main idea was to create a polls system that is highly customizable from admin panel and works without database. The last version was released in 2012.

| Year | Version | Major changes |
|------|---------|------------|
| 2004 | 1.0 | 🟢 Initial version |
| 2005 | 1.1 | 🟢 Bug fixes |
| 2005 | 1.2 | 🟢 Bug fixes |
| 2005 | 1.3 | 🟢 Bug fixes |
| 2005 | 1.4 | 🟢 Bug fixes |
| 2011 | 2.0 | 🟢 AJAX<br>🟢 IP multi-vote protection<br>🟢 Bar widths relative to widest one<br>🟢 Sorting answers in results<br>🟢 Colors for each answer<br>🟢 Improved security<br>🟢 Voting in archival polls<br>🟢 Putting specific poll on website |
| 2012 | 2.1 | 🟢 Cake charts in SVG<br>🟢 Polls preview in admin panel<br>🟢 Character set conversion<br>🟢 Extended help topics<br>🟢 HTML5 color picker<br>🟢 UTF-8 as default charset<br>🟢 CSRF protection by token |

## License
GPL version 3

## Support
This project is no more maintained since 2012 and will not receive security updates. The source code has been published to save the history of Internet. It is strongly discouraged to use outdated scripts in modern websites. 