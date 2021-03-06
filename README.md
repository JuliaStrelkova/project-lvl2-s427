[![Maintainability](https://api.codeclimate.com/v1/badges/8332fd0067c294821851/maintainability)](https://codeclimate.com/github/JuliaStrelkova/project-lvl2-s427/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/8332fd0067c294821851/test_coverage)](https://codeclimate.com/github/JuliaStrelkova/project-lvl2-s427/test_coverage)
[![Build Status](https://travis-ci.org/JuliaStrelkova/project-lvl1-s252.svg?branch=master)](https://travis-ci.org/JuliaStrelkova/project-lvl2-s427)

# Проект "Вычислитель отличий"

## Описание
В рамках данного проекта необходимо реализовать утилиту для поиска отличий в конфигурационных файлах.

## Возможности утилиты:

- Поддержка разных форматов
- Генерация отчета в виде plain text, pretty и json

##Пример использования:

```
$ gendiff --format pretty first.json second.json

{
    common: {
        setting1: Value 1
      - setting2: 200
        setting3: true
      - setting6: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
    }
    group1: {
      + baz: bars
      - baz: bas
        foo: bar
    }
  - group2: {
        abc: 12345
    }
  + group3: {
        fee: 100500
    }
}
```

before.json:
```
{
  "common": {
    "setting1": "Value 1",
    "setting2": "200",
    "setting3": true,
    "setting6": {
      "key": "value"
    }
  },
  "group1": {
    "baz": "bas",
    "foo": "bar"
  },
  "group2": {
    "abc": "12345"
  }
}
```
after.json
```
{
  "common": {
    "setting1": "Value 1",
    "setting3": true,
    "setting4": "blah blah",
    "setting5": {
      "key5": "value5"
    }
  },

  "group1": {
    "foo": "bar",
    "baz": "bars"
  },

  "group3": {
    "fee": "100500"
  }
}
```

step 1: https://asciinema.org/a/8x4wDjVTg7Cv9LTkpDuuXmDvs

step 2: https://asciinema.org/a/zRXFVy4E9RdNhknXlkRS7TBPe

step 3:  https://asciinema.org/a/4ISzdOndjPNEUS4wYUX2TG0bm

step 2 with fixes: https://asciinema.org/a/bHPgSDc7Kozls1S2ipbZVpjHj

step 2 with fixes: https://asciinema.org/a/MuHNXUFvOnnlxXEmagl18PnM1

step 4: https://asciinema.org/a/nbKIEUb5jmhUZAF9odpBWdcQ4

step 4 with fixes: https://asciinema.org/a/VHyuw0pRe7SXoxLqgi0YtP0gH

step 5: https://asciinema.org/a/5So6k1uYuFdjrrmz1TsIwJQi9

step 6:  https://asciinema.org/a/ISNsaNbeaYMFz6uUOvsY3uAwl
