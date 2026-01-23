### Hexlet tests and linter status:
[![Actions Status](https://github.com/flythq/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/flythq/php-project-48/actions)
[![main](https://github.com/flythq/php-project-48/actions/workflows/main.yml/badge.svg)](https://github.com/flythq/php-project-48/actions/workflows/main.yml)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=flythq_php-project-48&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=flythq_php-project-48)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=flythq_php-project-48&metric=bugs)](https://sonarcloud.io/summary/new_code?id=flythq_php-project-48)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=flythq_php-project-48&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=flythq_php-project-48)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=flythq_php-project-48&metric=coverage)](https://sonarcloud.io/summary/new_code?id=flythq_php-project-48)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=flythq_php-project-48&metric=duplicated_lines_density)](https://sonarcloud.io/summary/new_code?id=flythq_php-project-48)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=flythq_php-project-48&metric=ncloc)](https://sonarcloud.io/summary/new_code?id=flythq_php-project-48)

## Difference calculator

### Prerequisites

* Linux, Macos, WSL
* PHP >=8.2
* Make.

## Setup

Setup [SSH](https://docs.github.com/en/authentication/connecting-to-github-with-ssh) before clone:

```bash
git clone git@github.com:flythq/php-project-48.git
cd php-project-48

make install
```

## Description

A difference calculator is a program that determines the difference between two data structures. This is a popular task, and there are many online services available to solve it, such as http://www.jsondiff.com/. A similar mechanism is used when running tests or automatically tracking changes in configuration files.

```php
genDiff(string $finame, string $filename, string $format = 'stylish'): string
```

## Parameters

- `$filename` - path to the file
- `$format` - output format (default: `stylish`). Use `gendiff --help` or `-h` for more information

## Examples

> <a href="https://asciinema.org/a/MSDGh2sxhCUxyqQZ">Json plain example</a>
> 
> <a href="https://asciinema.org/a/0lOKzsLqOyIw8MwN">Yaml plain example</a>
> 
> <a href="https://asciinema.org/a/2ZuihzPjGlo5p3bb">Stylish format example</a>
> 
> <a href="https://asciinema.org/a/vEoXR7UhsO6w2qDZ">Plain format example</a>
> 
> <a href="https://asciinema.org/a/DGlQ5nc8EKZq7Zc8">Json format example</a>
>
