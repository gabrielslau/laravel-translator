# Laravel Translator

Simple laravel translator inspired by Voyager

## Installation

Via Composer

``` bash
$ composer require okatsuralau/laravel-translator
```

## Usage

Import the translatable trait in your model and configure the fields to be translated

```
use \Okatsuralau\LaravelTranslator\Traits\Translatable;

protected $translatable = ['title', 'content'];
```
