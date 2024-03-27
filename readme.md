# Athena-PHP

Athena-PHP is a security library that provides an additional layer of functionality over the composer audit, specifically tailored for managing ignored advisories.

## Installation

Use composer to install Athena-PHP.

```bash
composer require sts-gaming-group/athena
```

## Usage

Athena-PHP can be easily accessed through the vendor binaries:

```bash
php vendor/bin/athena
```

## Ignoring Advisories

To ignore advisories, Athena-PHP utilizes a `.cveignore` file. This file should be created and maintained by the user.

The structure of the `.cveignore` file is as follows:

```json
{
    "PKSA-hn62-zkx4-1y5q": {
        "expiry": 1653908558,
        "notes": "Test notes"
    },
    "PKSA-8ds9-sp96-ghmb": {
        "expiry": 1704074400,
        "notes": "Test notes"
    }
}
```

Each advisory to be ignored is represented by a JSON object. The key is the identifier of the advisory, and it contains an `expiry` (UNIX timestamp) indicating when the ignore will cease to be effective, and `notes` for any additional context or information.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
