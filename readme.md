# WP Valet Boilerplate

This is designed as a quick boilerplate for working in WordPress on Laravel Valet. It is primarily built to facilitate 
my own workflow and provides the following: 

- Array-based configuration from a single file
- WordPress in a sub-directory
- Remote image loading where a local version of the file isn't found
- WP debug log moved outside of the wp-content directory
- A development log and utility for dumping data to a separate log file while working
- A set of WP-CLI commands under `wp valetbp` for easy synchronisation with remote sites

Please note: this is a fairly opinionated set up and that really comes down to the fact that this was designed to aid my 
own existing workflow. Feel free to fork and mod to suit your own needs.

## Getting Started

- Make sure you have installed all dependencies (see **dependencies** section further down)
- In your terminal, head to your Valet directory:
    - `cd ~/path/to/valet`
- Clone this puppy into a new project under your valet directory: 
    - `git clone https://github.com/mishterk/wp-valet-boilerplate my-new-project`
- Move into the project directory: 
    - `cd my-new-project`
- Create a local config file: 
    - `cp valetbp-config-sample.php valetbp-config.php`
- Modify the config file accordingly.
- Run the installation: 
    - `php bin/install.php`  

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

### Speeding up Composer

Hate long waits for Composer? Check out the [Prestissimo](https://github.com/hirak/prestissimo) package for parallel 
package installation.

## WP CLI Commands

#### `wp valetbp sync`

Carries out a full sync and local config based on settings in valetbp-config.php under sync. This includes:

- Pulling the DB via WP Migrate DB Pro 
- Registering ACF Pro serial key
- Installing any dev-only plugins
- De/Activating plugins to suit the development environment
- Flushing rewrite rules
- Logging in

#### `wp valetbp post-sync`

Carries out all post database sync tasks listed in the `sync` command

#### `wp valetbp login`

Generates a one-time login URL and opens that URL in the browser. The authenticated user will be that configured in valetbp-config.php under auth.username.

#### `wp valetbp pull-db`

Pulls the DB via **WP Migrate DB Pro** with **CLI Addon**

#### `wp valetbp register-acf`

Registers the ACF Pro key

#### `wp valetbp install-plugins`

Installs any plugins configured in valetbp-config.php under sync.plugins.activate

#### `wp valetbp toggle-plugins`

De/activates any plugins as per the valetbp-config.php under sync.plugins