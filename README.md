# Infinite

A plugin boilerplate for creating a fully customizable backend admin portal and frontend user portal. The goal of this plugin is to provide the base structures needed to integrate systems and architectures all in one place for clients needing a "single-source-of-truth" that allows for a single location to manage all operational tasks. It also allows for those operational tasks to carryover into the public side where users can get and manage details regarding their speciific accounts and transactions. All while keeping the plugin concise and nimble without any uneccessary code bloat. Each instance of the plugin can be taylored to the needs of the client.

This plugin allows for **INFINITE** possibilities within a simple code base.

---

## GLOBAL FEATURES

### Customizable Theme

The plugin uses [TailwindsCSS](https://tailwindcss.com/) as the framework for the plugin's theme. You can adjust the colors, spacing, sizing, fonts, and more by updating the TailwindCSS config file. There is seperate config files for the admin and public sides of the plugin for total versatility.

The plugin also allows for white labeling the admin side by simply replacing the icon_menu.svg and logo_header.png files with versions of your own logo.

### JSON Config

The plugin is built on allowing for quick and easy customizations via the config files located in the config folder. These files drive the UI.

### Custom DB Tables

The plugin allows for the creation of custom DB tables via the tables.json config file and a partnered SQL file created in the sql folder. An example customers table is provided as a starting point.

### Extensions

Extensions allow for advanced customization of the plugin. Extensions are classes that can be instantiated as part of the extension class code or it can be used as a callback for dynamic content loading. Extensions enable third-party integrations and API's. The possibilities are **INFINITE**.

## ADMIN FEATURES

### Partials

Partials are a mix of pages, templates, and components that can be loaded via a view config definition. Allows for custom code implementations and pre-built standard layouts that can be used by any implementation. Pre-built partials are listed by type below.

- **Pages:** these are base pre-built/custom layouts that are associated to admin menu links/pages.
- **Templates:** these are pre-built code used by the UI.
- **Components:** these are pre-built/custom layouts that are used by the UI and loading content onto pages.

| Type      | Slug            | Description                                                                |
| --------- | --------------- | -------------------------------------------------------------------------- |
| Page      | admin_dashboard | Handles the display of the plugin dashboard content                        |
| Page      | admin_page      | Handles the display of any custom page defined in the config               |
| Page      | admin_settings  | Handles the display of the plugin settings content                         |
| Template  | temp_header     | Handles the display of the page header content                             |
| Template  | temp_nav        | Handles the display of the page navigation; if it is defined in the config |
| Component | comp_pagination | Displays a responsive pagination nav                                       |
| Component | comp_filters    | Displays filters for sorting, searching, etc...                            |
| Component | comp_table      | Displays DB table array content in a table view                            |

## PUBLIC FEATURES

COMING SOON...

---

## ROADMAP

- TODO: Enable adding additional dynamic settings to initial view and dynamic config views
- SOMEDAY: Re-develop plugin to be react based instead of PHP - this will allow the admin/public portal to be a single page reactive environment that is much faster and more modular.
