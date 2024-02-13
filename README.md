# Infinite

A plugin boilerplate for creating a fully customizable backend admin portal and frontend user portal.

---

## FEATURES:

### Extensions

Extensions allow for advanced customization of the plugin. Extensions are classes that can be instantiated as part of the extension class code or it can be used as a callback for dynamic content loading.

### Partials

Partials are components that can be loaded view a view config definition. Allows for custom code implementations and pre-built standard components that can be used by any implementation. Pre-built partials are listed below:

| Partial | Slug        | Description                            | Required Array Args        |
| ------- | ----------- | -------------------------------------- | -------------------------- |
| Table   | admin_table | Displays array content in a table view | [Cols, Rows, Total, Pages] |

---

## TASKS:

TODO: Add a settings page with initial "General" view and enable adding additional dynamic settings to initial view and dynamic config views
