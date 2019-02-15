# WP Valet Boilerplate

This is designed as a quick boilerplate for working in WordPress on Laravel Valet. This is fairly opinionated and that 
really comes down to the fact that this was designed to aid my own existing workflow instead of trying to support other 
people's preferences. Feel free to fork and mod to suit your own needs.  

## Dependencies

- [Homebrew](https://brew.sh/)
- [Composer](https://getcomposer.org/)
- [Laravel Valet](https://laravel.com/docs/5.7/valet)

### Nice-to-haves

Aside from the above-mentioned dependencies, this boilerplate is also geared towards the following:

- [MailHog via Homebrew](https://pascalbaljetmedia.com/en/blog/setup-mailhog-with-laravel-valet)
- [WP Migrate DB Pro WordPress plugin](https://deliciousbrains.com/wp-migrate-db-pro/)
- [WP Migrate DB Pro WordPress plugin CLI Addon](https://deliciousbrains.com/wp-migrate-db-pro/doc/cli-addon/)
- [Advanced Custom Fields Pro WordPress plugin](https://www.advancedcustomfields.com/pro/)

#### Speeding up Composer

Hate longs waits for Composer? Check out the [Prestissimo](https://github.com/hirak/prestissimo) package for parallel 
package installation.

## Getting Started

- Clone this puppy into a new project under your valet directory: `git clone https://github.com/mishterk/wp-valet-boilerplate my-new-project`
- Create a local config file: `cp valetbp-config-sample.php valetbp-config.php`
- Configure your project via `valetbp-config.php`
- Run the installation: `php bin/install.php`