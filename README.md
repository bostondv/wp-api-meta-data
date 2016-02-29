# WordPress REST API User Meta

A WordPress plugin that adds user meta data to the WP API user endpoints. Supports getting and setting user meta.

Requires WP API Version 2.0 Beta 12 or higher.

## Usage

### Listing and Retrieving a User

A `meta` field will be added to the response data containing an array of all user meta data.

### Creating and Updating a User

Add a `meta` object to the `POST` data of your request containing key / value pairs of the meta data you want to update or add. Response will contain the new meta data.