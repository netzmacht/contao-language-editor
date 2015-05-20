Language variable editor
========================

[![Build Status](http://img.shields.io/travis/netzmacht/contao-language-editor/master.svg?style=flat-square)](https://travis-ci.org/netzmacht/contao-language-editor)
[![Version](http://img.shields.io/packagist/v/netzmacht/contao-language-editor.svg?style=flat-square)](http://packagist.com/packages/netzmacht/contao-language-editor)
[![License](http://img.shields.io/packagist/l/netzmacht/contao-language-editor.svg?style=flat-square)](http://packagist.com/packages/netzmacht/contao-language-editor)
[![Downloads](http://img.shields.io/packagist/dt/netzmacht/contao-language-editor.svg?style=flat-square)](http://packagist.com/packages/netzmacht/contao-language-editor)
[![Contao Community Alliance coding standard](http://img.shields.io/badge/cca-coding_standard-red.svg?style=flat-square)](https://github.com/contao-community-alliance/coding-standard)


This editor allow modification of languages variables from the Contao backend.

The language variable editor was developed by [Tristan Lins](https://github.com/bit3archive/contao-language-editor). 
This fork continues the development for Contao 3.2+.
 
 
Install
-------

The language editor is only available using Composer:

```
$ php composer.phar require netzmacht/contao-language-editor:~1.0
```

Migration
---------

If you are already using the language editor there are small changes which you have to made:

 - Remove the translation editor content from `system/config/langconfig.php` (Wrapped by `### TRANSLATION EDITOR START|STOP###`)
   This extension use the `loadLanguageFile` hook now.

License
-------

This extension is licensed under the [LGPL](LGPL.txt). 

This software using the [Silk Icons](http://www.famfamfam.com/lab/icons/silk/).
