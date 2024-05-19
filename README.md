# Breakdance Migrator

Breakdance Migrator is a WordPress plugin designed to facilitate the migration of Breakdance Builder content from a staging site to a production site. The primary challenge it addresses is the difficulty of deploying visual changes made in Breakdance Builder while new posts are added and existing ones are modified on the production site.

## Features

- Export and import Breakdance Builder content seamlessly.
- Export post types, postmeta, options, icons, and icon sets related to Breakdance.
- Compress exported JSON data to reduce file size and improve download performance.
- Interactive and user-friendly admin interface with confirmation prompts and loading indicators.
- Supports AJAX for efficient data export without page reloads.

## Installation

1. Download the Breakdance Migrator plugin.
2. Upload the plugin files to the `/wp-content/plugins/breakdance-migrator` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Navigate to the 'Breakdance Migrator' submenu under the 'Breakdance' menu in the WordPress admin dashboard.

## Usage

1. Go to the 'Breakdance Migrator' page in the WordPress admin dashboard.
2. Select the options you want to export (e.g., Export Icons).
3. Click the 'Export Data' button.
4. Download the exported JSON file or view it directly in your browser.

## Roadmap

### Completed

- Initial release with the ability to export and import Breakdance-related data.
- Added compression of JSON files using gzip.
- Interactive admin interface with AJAX support for data export.
- Support for exporting posts, postmeta, options, icons, and icon sets.

### Planned

- **Import Functionality**: Develop a seamless import mechanism to synchronize data between staging and production sites.
- **Selective Export**: Allow users to select specific post types or date ranges for export.
- **Advanced Filtering**: Provide more advanced filtering options to customize the export process.
- **Backup and Restore**: Integrate backup and restore functionality to protect against data loss during migration.
- **Multisite Support**: Extend support for WordPress multisite installations.
- **Performance Optimization**: Further optimize the export and import processes for large datasets.

## Contribution

We welcome contributions from the community to help improve Breakdance Migrator. Here's how you can get involved:

1. **Report Bugs**: If you encounter any issues, please report them on our [GitHub Issues](https://github.com/slawomiroruba/breakdance-migrator/issues) page.
2. **Feature Requests**: Have a great idea for a new feature? Let us know by opening a feature request.
3. **Submit Pull Requests**: If you'd like to contribute code, fork the repository and submit a pull request with your changes.
4. **Documentation**: Help us improve our documentation by suggesting enhancements or adding new content.
5. **Spread the Word**: Share Breakdance Migrator with your network and help us grow our community.

### How to Make This README Awesome

- **Detailed Documentation**: Provide step-by-step guides for installation, usage, and troubleshooting.
- **Screenshots and GIFs**: Include visuals to demonstrate the plugin's features and usage.
- **Testimonials**: Add user testimonials and case studies to highlight the plugin's benefits.
- **Active Community**: Foster an active community by regularly engaging with users and contributors.
- **Regular Updates**: Keep the README up-to-date with the latest features, improvements, and roadmap changes.

## License

Breakdance Migrator is licensed under the MIT License. See the [LICENSE](https://github.com/slawomiroruba/breakdance-migrator/blob/main/LICENSE) file for more information.

---

Thank you for using Breakdance Migrator! We look forward to your contributions and feedback to make this plugin even better. Happy migrating!
