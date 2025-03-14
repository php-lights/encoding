# Encoding
[![License][license-badge]][license-url]
[![Docs][docs-badge]][docs-url]
[![CI][ci-badge]][ci-url]

[license-badge]: https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square
[license-url]: #license
[docs-badge]: https://img.shields.io/github/deployments/php-lights/encoding/github-pages?label=docs&style=flat-square
[docs-url]: https://php-lights.github.io/encoding/
[ci-badge]: https://img.shields.io/github/actions/workflow/status/php-lights/encoding/.github/workflows/php.yml?style=flat-square
[ci-url]: https://github.com/php-lights/encoding/actions/workflows/php.yml

A text encoding library for PHP, implementing the [WHATWG Encoding Standard](https://encoding.spec.whatwg.org). Currently a work-in-progress (not published).

## Install
```sh
composer install neoncitylights/encoding
```

## Usage
### Encoding
```php
<?php

use Neoncitylights\Encoding\Encode\TextEncoder;

$encoder = new TextEncoder();
$encoder->encode( $s );

```

### Decoding
```php
<?php

use Neoncitylights\Encoding\Decode\TextDecoder;
use Neoncitylights\Encoding\Decode\TextDecoderOptions;

$decoder = TextDecoder::tryNew( 'utf8', new TextDecoderOptions() );
$decoder->decode( [] );
```

## License
This software is licensed under the MIT license ([`LICENSE`](./LICENSE) or <http://opensource.org/licenses/MIT>).

### Contribution
Unless you explicitly state otherwise, any contribution intentionally submitted for inclusion in the work by you, as defined in the MIT license, shall be licensed as above, without any additional terms or conditions.
