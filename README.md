# devnix/belfiore-code

Official Italian Belfiore code list (cadastral code) which represents a 
[comune](https://en.wikipedia.org/wiki/Comune).

## Update

```
composer install
bin/console update
```

and you are golden.

Anyway, if you think that the crawled data is outdated, please 
[file an issue](https://github.com/devnix/belfiore-code/issues/new). I will 
update the new data ASAP.

# Usage

```
TODO
```

# Roadmap

- [ ] Write unit tests to verify that the data sources maintain the same format
- [ ] Write unit tests to cover all classes
- [ ] Write documentation of each column available in English
- [ ] Investigate to run the update command automatically using Github Actions
once in a month

# Contributing

You can contribute by forking the project and doing a pull request. Please, do 
all your work on the `develop` branch, or your PR will be rejected.

As I would love to get some feedback, specially from people more familiar than 
me with this kind of data, I will consider it as a WIP, and the API/column names
may change in the short term.

# Attribution

- Cities List of Values: CC BY 4.0 Ministero dell'interno
- Regions List of Values: CC BY 3.0 Istituto nazionale di statistica

Inspired by [Marketto/codice-fiscale-utils](https://github.com/Marketto/codice-fiscale-utils), done to use in
conjunction with [DavidePastore/codice-fiscale](https://github.com/DavidePastore/codice-fiscale)
