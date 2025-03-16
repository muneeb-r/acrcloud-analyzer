# ACRCloud Song Analyzer

## Description
ACRCloud Song Analyzer is a WordPress plugin that analyzes uploaded songs using the ACRCloud API. If a song contains copied parts, an email notification is sent to a predefined email address.

## Features
- Automatically analyzes uploaded songs through the ACRCloud API.
- Sends an email notification if the uploaded song contains copied content.
- Seamlessly integrates with WordPress Media Library.

## Installation
1. Download the plugin files and upload them to the `/wp-content/plugins/acrcloud-song-analyzer/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure the required API keys and notification email in the plugin file.

## Configuration
The following constants need to be defined in the plugin file:

```php
// Define ACRCloud API credentials
define('ACRCLOUD_API_HOST', 'https://identify-eu-west-1.acrcloud.com');
define('ACRCLOUD_ACCESS_KEY', 'your_access_key');
define('ACRCLOUD_ACCESS_SECRET', 'your_access_secret');
define('NOTIFICATION_EMAIL', 'example@mail.com');
```

Replace `'your_access_key'`, `'your_access_secret'`, and `'example@mail.com'` with your actual ACRCloud credentials and notification email.

## Usage
1. Upload a song via the WordPress Media Library.
2. The plugin will automatically analyze the song using the ACRCloud API.
3. If the song contains copied parts, an email notification will be sent to the configured email address.
4. The analysis result will be displayed.

## Hooks and Filters
- **`wp_handle_upload`**: Filters file uploads to analyze audio files using ACRCloud API.

## Security Considerations
- Do not expose your ACRCloud API credentials publicly.
- Ensure secure handling of API responses and errors.

## Changelog
### Version 1.0.1
- Initial release of ACRCloud Song Analyzer.

## License
This plugin is licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Author
**Muneeb Rana**

For any inquiries, contact at `muneebjs.css@gmail.com`.
